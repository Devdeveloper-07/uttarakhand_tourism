<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingPackage;
use App\Models\BookingRoom;
use App\Models\Hotel;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Room;
use App\Services\GstCalculator;
use App\Services\RoomAvailability;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingPaymentController extends Controller
{
    public function startHotel(Request $request, Hotel $hotel)
    {
        abort_unless($hotel->status, 404);

        $rules = [
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['required', 'integer', 'min:1', 'max:20'],
            'rooms' => ['nullable', 'integer', 'min:1', 'max:20'],
            'room_allocations' => ['nullable', 'array'],
            'room_allocations.*' => ['integer', 'min:0', 'max:20'],
            'checkout_token' => ['nullable', 'uuid'],
        ];
        if ($request->user()) {
            $rules += [
                'name' => ['required', 'string', 'min:2', 'max:120'],
                'phone' => ['required', 'string', 'max:24', 'regex:/^(?=.*[0-9])[0-9+\-\s()]+$/'],
                'email' => ['required', 'email', 'max:255'],
            ];
        }
        $validated = $request->validate($rules);

        if (! $request->user()) {
            $request->session()->put('booking.pending', [
                'type' => 'hotel',
                'id' => $hotel->id,
                'form' => collect($validated)->only([
                    'check_in', 'check_out', 'guests', 'rooms', 'room_allocations', 'checkout_token',
                ])->all(),
            ]);
            $request->session()->put('url.intended', route('hotel.details', $hotel->id));

            return redirect()->route('login')->with('status', 'Please login to continue your booking.');
        }

        $this->forgetPendingBooking($request, 'hotel', $hotel->id);

        if ($redirect = $this->gatewayConfigurationError()) {
            return $redirect;
        }

        $checkoutToken = $validated['checkout_token'] ?? (string) Str::uuid();
        if ($existing = $this->existingCheckout($checkoutToken, 'hotel', $hotel->id)) {
            return $existing;
        }

        $nights = (int) Carbon::parse($validated['check_in'])->diffInDays(
            Carbon::parse($validated['check_out'])
        );
        $availableRooms = $hotel->rooms()
            ->where('status', true)
            ->with('roomType')
            ->get();
        $bookingRooms = [];
        $inventoryAllocations = [];
        $gstCalculator = app(GstCalculator::class);
        $lines = [];

        if ($availableRooms->isNotEmpty()) {
            $availability = app(RoomAvailability::class)->forRooms(
                $availableRooms,
                $validated['check_in'],
                $validated['check_out']
            );
            $allocations = collect($validated['room_allocations'] ?? [])
                ->map(fn ($quantity) => (int) $quantity)
                ->filter(fn ($quantity) => $quantity > 0);

            if ($allocations->isEmpty()) {
                return back()->withInput()->withErrors([
                    'room_allocations' => 'Select at least one available room.',
                ]);
            }

            if ($allocations->sum() > 20) {
                return back()->withInput()->withErrors([
                    'room_allocations' => 'You can book up to 20 rooms at a time.',
                ]);
            }

            foreach ($allocations as $roomId => $quantity) {
                $room = $availableRooms->firstWhere('id', (int) $roomId);
                if (! $room || $quantity > ($availability[$room->id]['available'] ?? 0)) {
                    return back()->withInput()->withErrors([
                        'room_allocations' => 'Sorry, this room is no longer available for your selected dates. Please select another room or date.',
                    ]);
                }

                $inventoryAllocations[$room->id] = $quantity;
                $bookingRooms[] = [
                    'room_id' => $room->id,
                    'quantity' => $quantity,
                    'price' => $room->price_per_night,
                ];
                $lines[] = $gstCalculator->hotelLine(
                    (int) round((float) $room->price_per_night * 100),
                    $quantity,
                    $nights,
                    $room->room_name,
                    $room->id,
                    $room->roomType?->name
                );
            }

            $roomCount = $allocations->sum();
            $capacity = 0;
            foreach ($allocations as $roomId => $quantity) {
                $capacity += (int) $quantity * (int) $availableRooms->firstWhere('id', (int) $roomId)->capacity;
            }

            if ((int) $validated['guests'] > $capacity) {
                return back()->withInput()->withErrors([
                    'guests' => 'The selected rooms can accommodate up to '.$capacity.' guests.',
                ]);
            }
        } else {
            $hasInventory = $hotel->rooms()->exists();
            if ($hasInventory) {
                return back()->withInput()->withErrors([
                    'room_allocations' => 'No rooms are currently available at this hotel.',
                ]);
            }

            $roomCount = (int) ($validated['rooms'] ?? 1);
            $unitPricePaise = (int) round((float) $hotel->starting_price * 100);
            $lines[] = $gstCalculator->hotelLine(
                $unitPricePaise,
                $roomCount,
                $nights,
                'Standard room'
            );
        }

        $pricing = $gstCalculator->summarize($lines, 'hotel_accommodation_tariff');

        return $this->createCheckout(
            $request,
            itemType: 'hotel',
            itemId: $hotel->id,
            itemName: $hotel->hotel_name,
            pricing: $pricing,
            guestCount: $validated['guests'],
            roomCount: $roomCount,
            contact: $validated,
            checkoutToken: $checkoutToken,
            inventoryAllocations: $inventoryAllocations,
            bookingRooms: $bookingRooms,
            dates: [
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'travel_date' => null,
            ],
        );
    }

    public function startPackage(Request $request, Package $package)
    {
        abort_unless($package->status, 404);

        $rules = [
            'travel_date' => ['required', 'date', 'after_or_equal:today'],
            'travelers' => ['required', 'integer', 'min:1', 'max:20'],
            'checkout_token' => ['nullable', 'uuid'],
        ];
        if ($request->user()) {
            $rules += [
                'name' => ['required', 'string', 'min:2', 'max:120'],
                'phone' => ['required', 'string', 'max:24', 'regex:/^(?=.*[0-9])[0-9+\-\s()]+$/'],
                'email' => ['required', 'email', 'max:255'],
            ];
        }
        $validated = $request->validate($rules);

        if (! $request->user()) {
            $request->session()->put('booking.pending', [
                'type' => 'package',
                'id' => $package->id,
                'form' => collect($validated)->only([
                    'travel_date', 'travelers', 'checkout_token',
                ])->all(),
            ]);
            $request->session()->put('url.intended', route('package.details', $package->id));

            return redirect()->route('login')->with('status', 'Please login to continue your booking.');
        }

        $this->forgetPendingBooking($request, 'package', $package->id);

        if ($package->max_person && $validated['travelers'] > $package->max_person) {
            return back()
                ->withInput()
                ->withErrors(['travelers' => 'This package is limited to '.$package->max_person.' travelers.']);
        }

        if ($redirect = $this->gatewayConfigurationError()) {
            return $redirect;
        }

        $checkoutToken = $validated['checkout_token'] ?? (string) Str::uuid();
        if ($existing = $this->existingCheckout($checkoutToken, 'package', $package->id)) {
            return $existing;
        }

        $pricing = app(GstCalculator::class)->summarize([
            app(GstCalculator::class)->packageLine(
                (int) round($package->booking_price * 100),
                (int) $validated['travelers'],
                (float) $package->gst_percentage,
                $package->gst_category,
                $package->package_name
            ),
        ], $package->gst_category);

        return $this->createCheckout(
            $request,
            itemType: 'package',
            itemId: $package->id,
            itemName: $package->package_name,
            pricing: $pricing,
            guestCount: $validated['travelers'],
            roomCount: 1,
            contact: $validated,
            checkoutToken: $checkoutToken,
            dates: [
                'check_in' => null,
                'check_out' => null,
                'travel_date' => $validated['travel_date'],
            ],
        );
    }

    public function verify(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'razorpay_payment_id' => ['required', 'string', 'max:100'],
            'razorpay_order_id' => ['required', 'string', 'max:100'],
            'razorpay_signature' => ['required', 'string', 'size:64'],
        ]);

        $booking = $payment->booking;

        abort_unless(
            $booking &&
            $payment->gateway_name === 'razorpay' &&
            $payment->gateway_order_id &&
            hash_equals($payment->gateway_order_id, $validated['razorpay_order_id']),
            404
        );

        $expectedSignature = hash_hmac(
            'sha256',
            $validated['razorpay_order_id'].'|'.$validated['razorpay_payment_id'],
            (string) config('services.razorpay.secret')
        );

        if (! hash_equals($expectedSignature, $validated['razorpay_signature'])) {
            return $this->result($booking, $payment, 'failed', 'Payment verification failed. No payment has been recorded.');
        }

        if ($payment->payment_status === 'paid') {
            if (
                $payment->gateway_payment_id !== $validated['razorpay_payment_id'] ||
                $booking->payment_status !== 'paid'
            ) {
                return $this->result($booking, $payment, 'failed', 'A different payment has already been recorded for this booking.');
            }

            if ($booking->booking_status === 'cancelled') {
                return $this->result(
                    $booking,
                    $payment,
                    'failed',
                    'Your payment was received for a booking that has since been cancelled. Contact support to arrange a refund.'
                );
            }

            return $this->result($booking, $payment, 'success', 'Your booking is confirmed and payment was received.');
        }

        if ($payment->payment_status !== 'paid') {
            try {
                $gatewayPayment = $this->razorpayRequest()
                    ->get('/payments/'.$validated['razorpay_payment_id']);
            } catch (ConnectionException $exception) {
                Log::warning('Razorpay payment verification could not connect.', [
                    'payment_id' => $payment->id,
                ]);

                return $this->result(
                    $booking,
                    $payment,
                    'pending',
                    'Your payment is being checked. Please refresh this page later or contact support.'
                );
            }

            if (! $gatewayPayment->successful()) {
                Log::warning('Razorpay payment lookup failed.', [
                    'payment_id' => $payment->id,
                    'http_status' => $gatewayPayment->status(),
                ]);

                return $this->result(
                    $booking,
                    $payment,
                    'pending',
                    'Your payment could not be confirmed yet. Please contact support before trying again.'
                );
            }

            $gatewayData = $gatewayPayment->json();
            $expectedAmount = (int) round((float) $payment->amount * 100);

            if (
                ! is_array($gatewayData) ||
                ($gatewayData['id'] ?? null) !== $validated['razorpay_payment_id'] ||
                ($gatewayData['order_id'] ?? null) !== $payment->gateway_order_id ||
                (int) ($gatewayData['amount'] ?? 0) !== $expectedAmount ||
                strtoupper((string) ($gatewayData['currency'] ?? '')) !== $payment->currency
            ) {
                return $this->result($booking, $payment, 'failed', 'Payment details did not match this booking.');
            }

            if (($gatewayData['status'] ?? null) === 'authorized') {
                try {
                    $captureResponse = $this->razorpayRequest()->post(
                        '/payments/'.$validated['razorpay_payment_id'].'/capture',
                        ['amount' => $expectedAmount, 'currency' => $payment->currency]
                    );
                } catch (ConnectionException $exception) {
                    Log::warning('Razorpay payment capture could not connect.', [
                        'payment_id' => $payment->id,
                    ]);

                    return $this->result(
                        $booking,
                        $payment,
                        'pending',
                        'Your payment was authorized and is awaiting capture confirmation.'
                    );
                }

                if (! $captureResponse->successful() || ! is_array($captureResponse->json())) {
                    Log::warning('Razorpay payment capture failed.', [
                        'payment_id' => $payment->id,
                        'http_status' => $captureResponse->status(),
                    ]);

                    return $this->result(
                        $booking,
                        $payment,
                        'pending',
                        'Your payment was authorized and is awaiting capture confirmation.'
                    );
                }

                $gatewayData = $captureResponse->json();
            }

            if (($gatewayData['status'] ?? null) !== 'captured') {
                if (($gatewayData['status'] ?? null) === 'failed') {
                    $this->releaseFailedReservation($booking, $payment);
                }

                return $this->result(
                    $booking,
                    $payment,
                    'failed',
                    'The payment was not captured. Please try again or contact support.'
                );
            }

            if (
                ($gatewayData['id'] ?? null) !== $validated['razorpay_payment_id'] ||
                ($gatewayData['order_id'] ?? null) !== $payment->gateway_order_id ||
                (int) ($gatewayData['amount'] ?? 0) !== $expectedAmount ||
                strtoupper((string) ($gatewayData['currency'] ?? '')) !== $payment->currency
            ) {
                return $this->result($booking, $payment, 'failed', 'Captured payment details did not match this booking.');
            }

            $duplicatePaymentMismatch = false;
            $reservationConflict = false;
            $bookingWasCancelled = false;
            DB::transaction(function () use (
                $payment,
                $booking,
                $validated,
                $gatewayData,
                &$duplicatePaymentMismatch,
                &$reservationConflict,
                &$bookingWasCancelled
            ): void {
                $lockedPayment = Payment::query()->lockForUpdate()->findOrFail($payment->id);
                $lockedBooking = Booking::query()->lockForUpdate()->findOrFail($booking->id);

                if ($lockedPayment->payment_status === 'paid') {
                    $duplicatePaymentMismatch = $lockedPayment->gateway_payment_id !== $validated['razorpay_payment_id'];
                    return;
                }

                if ($lockedBooking->booking_status === 'cancelled') {
                    $bookingWasCancelled = true;
                    $lockedPayment->update([
                        'gateway_payment_id' => $validated['razorpay_payment_id'],
                        'gateway_signature' => $validated['razorpay_signature'],
                        'transaction_id' => $validated['razorpay_payment_id'],
                        'payment_method' => $gatewayData['method'] ?? 'razorpay',
                        'payment_status' => 'paid',
                        'payment_date' => now(),
                        'meta' => [
                            'gateway_status' => $gatewayData['status'],
                            'gateway_order_id' => $gatewayData['order_id'],
                            'booking_cancelled_before_verification' => true,
                        ],
                    ]);
                    $lockedBooking->update([
                        'payment_status' => 'paid',
                        'hold_expires_at' => null,
                    ]);
                    return;
                }

                $bookingRooms = BookingRoom::query()
                    ->where('booking_id', $lockedBooking->id)
                    ->get(['room_id', 'quantity']);
                if (
                    $bookingRooms->isNotEmpty() &&
                    $lockedBooking->hold_expires_at?->isPast()
                ) {
                    $lockedRooms = Room::query()
                        ->whereIn('id', $bookingRooms->pluck('room_id'))
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->get();
                    $availability = app(RoomAvailability::class)->forRooms(
                        $lockedRooms,
                        $lockedBooking->check_in,
                        $lockedBooking->check_out,
                        $lockedBooking->id
                    );

                    foreach ($bookingRooms as $allocation) {
                        if ($allocation->quantity > ($availability[$allocation->room_id]['available'] ?? 0)) {
                            $reservationConflict = true;
                            break;
                        }
                    }
                }

                $lockedPayment->update([
                    'gateway_payment_id' => $validated['razorpay_payment_id'],
                    'gateway_signature' => $validated['razorpay_signature'],
                    'transaction_id' => $validated['razorpay_payment_id'],
                    'payment_method' => $gatewayData['method'] ?? 'razorpay',
                    'payment_status' => 'paid',
                    'payment_date' => now(),
                    'meta' => [
                        'gateway_status' => $gatewayData['status'],
                        'gateway_order_id' => $gatewayData['order_id'],
                        'inventory_conflict' => $reservationConflict,
                    ],
                ]);

                $lockedBooking->update([
                    'payment_status' => 'paid',
                    'booking_status' => $reservationConflict ? 'cancelled' : 'confirmed',
                    'hold_expires_at' => null,
                ]);
            });

            if ($duplicatePaymentMismatch) {
                return $this->result($booking->fresh(), $payment->fresh(), 'failed', 'A different payment has already been recorded for this booking.');
            }
            if ($reservationConflict) {
                Log::error('Captured hotel payment could not be confirmed because the inventory hold expired.', [
                    'booking_id' => $booking->id,
                    'payment_id' => $payment->id,
                ]);

                return $this->result(
                    $booking->fresh(),
                    $payment->fresh(),
                    'failed',
                    'Your payment was received, but the room reservation expired and availability changed. Please contact support to arrange a refund or alternative.'
                );
            }
            if ($bookingWasCancelled) {
                Log::error('Captured payment was received for a booking already cancelled by an administrator.', [
                    'booking_id' => $booking->id,
                    'payment_id' => $payment->id,
                ]);

                return $this->result(
                    $booking->fresh(),
                    $payment->fresh(),
                    'failed',
                    'Your payment was received after this booking was cancelled. Contact support to arrange a refund.'
                );
            }
        }

        return $this->result($booking->fresh(), $payment->fresh(), 'success', 'Your booking is confirmed and payment was received.');
    }

    public function paymentFailed(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'razorpay_payment_id' => ['required', 'string', 'max:100'],
            'razorpay_order_id' => ['required', 'string', 'max:100'],
        ]);
        abort_unless(
            $payment->gateway_name === 'razorpay' &&
            $payment->gateway_order_id &&
            hash_equals($payment->gateway_order_id, $validated['razorpay_order_id']) &&
            $payment->booking,
            404
        );

        try {
            $response = $this->razorpayRequest()
                ->get('/payments/'.$validated['razorpay_payment_id']);
        } catch (ConnectionException $exception) {
            Log::warning('Razorpay failed payment could not be checked.', [
                'payment_id' => $payment->id,
            ]);

            return response()->json([
                'message' => 'Payment failure could not be confirmed. Your room hold will expire automatically if payment is not completed.',
            ], 503);
        }

        $gatewayData = $response->json();
        if (
            ! $response->successful() ||
            ! is_array($gatewayData) ||
            ($gatewayData['id'] ?? null) !== $validated['razorpay_payment_id'] ||
            ($gatewayData['order_id'] ?? null) !== $payment->gateway_order_id ||
            (int) ($gatewayData['amount'] ?? 0) !== (int) round((float) $payment->amount * 100) ||
            strtoupper((string) ($gatewayData['currency'] ?? '')) !== $payment->currency ||
            ($gatewayData['status'] ?? null) !== 'failed'
        ) {
            Log::warning('Razorpay payment failure callback did not match a failed gateway payment.', [
                'payment_id' => $payment->id,
                'http_status' => $response->status(),
            ]);

            return response()->json([
                'message' => 'The payment service has not confirmed a failed payment. The room hold remains active temporarily.',
            ], 422);
        }

        $this->releaseFailedReservation($payment->booking, $payment);

        return response()->json([
            'message' => 'Payment failed and the temporary room reservation was released. Start a new booking to retry.',
        ]);
    }

    public function invoice(Payment $payment)
    {
        abort_unless($payment->payment_status === 'paid' && $payment->booking, 404);

        $payment->load('booking.user', 'booking.item', 'booking.rooms.room.roomType');

        return view('frontend.payment.invoice', [
            'payment' => $payment,
            'booking' => $payment->booking,
            'itemName' => $this->bookingItemName($payment->booking),
        ]);
    }

    private function gatewayConfigurationError()
    {
        if (config('services.razorpay.key') && config('services.razorpay.secret')) {
            return null;
        }

        return back()
            ->withInput()
            ->withErrors(['payment' => 'Online payments are not configured yet. Please contact the site administrator.']);
    }

    private function createCheckout(
        Request $request,
        string $itemType,
        int $itemId,
        string $itemName,
        array $pricing,
        int $guestCount,
        int $roomCount,
        array $contact,
        string $checkoutToken,
        array $dates,
        array $bookingRooms = [],
        array $inventoryAllocations = []
    ) {
        $amountPaise = $pricing['total_paise'];
        if ($amountPaise < 100) {
            return back()->withInput()->withErrors(['payment' => 'The booking amount must be at least ₹1.']);
        }

        $amount = $amountPaise / 100;
        $baseAmount = $pricing['base_paise'] / 100;
        $gstAmount = $pricing['gst_paise'] / 100;
        $bookingNumber = 'BK-'.now()->format('Ymd').'-'.Str::upper(Str::random(8));

        try {
            [$booking, $payment] = DB::transaction(function () use (
                $request,
                $itemType,
                $itemId,
                $guestCount,
                $roomCount,
                $contact,
                $dates,
                $amount,
                $baseAmount,
                $gstAmount,
                $pricing,
                $bookingNumber,
                $checkoutToken,
                $bookingRooms,
                $inventoryAllocations
            ): array {
                if ($inventoryAllocations !== []) {
                    $lockedRooms = \App\Models\Room::query()
                        ->whereIn('id', array_keys($inventoryAllocations))
                        ->where('hotel_id', $itemId)
                        ->where('status', true)
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->with('roomType')
                        ->get();

                    if ($lockedRooms->count() !== count($inventoryAllocations)) {
                        throw ValidationException::withMessages([
                            'room_allocations' => 'Sorry, this room is no longer available for your selected dates. Please select another room or date.',
                        ]);
                    }

                    $lockedAvailability = app(RoomAvailability::class)->forRooms(
                        $lockedRooms,
                        $dates['check_in'],
                        $dates['check_out']
                    );
                    $lockedCapacity = 0;
                    $lockedNights = (int) Carbon::parse($dates['check_in'])->diffInDays(
                        Carbon::parse($dates['check_out'])
                    );
                    $gstCalculator = app(GstCalculator::class);
                    foreach ($inventoryAllocations as $roomId => $quantity) {
                        $lockedRoom = $lockedRooms->firstWhere('id', (int) $roomId);
                        if ($quantity > ($lockedAvailability[$roomId]['available'] ?? 0)) {
                            throw ValidationException::withMessages([
                                'room_allocations' => 'Sorry, this room is no longer available for your selected dates. Please select another room or date.',
                            ]);
                        }
                        $requestedRoom = collect($bookingRooms)->firstWhere('room_id', (int) $roomId);
                        if (
                            ! $requestedRoom ||
                            (int) round((float) $requestedRoom['price'] * 100) !==
                                (int) round((float) $lockedRoom->price_per_night * 100)
                        ) {
                            throw ValidationException::withMessages([
                                'room_allocations' => 'A selected room rate changed. Please reload the hotel details and try again.',
                            ]);
                        }

                        $currentGstLine = $gstCalculator->hotelLine(
                            (int) round((float) $lockedRoom->price_per_night * 100),
                            $quantity,
                            $lockedNights,
                            $lockedRoom->room_name,
                            $lockedRoom->id,
                            $lockedRoom->roomType?->name
                        );
                        $checkoutGstLine = collect($pricing['lines'])->firstWhere('room_id', (int) $roomId);
                        if (
                            ! $checkoutGstLine ||
                            (float) $checkoutGstLine['gst_percentage'] !== (float) $currentGstLine['gst_percentage'] ||
                            (int) round((float) $checkoutGstLine['base_amount'] * 100) !== $currentGstLine['base_paise'] ||
                            (int) round((float) $checkoutGstLine['gst_amount'] * 100) !== $currentGstLine['gst_paise']
                        ) {
                            throw ValidationException::withMessages([
                                'room_allocations' => 'Room pricing or GST changed. Please reload the hotel details and try again.',
                            ]);
                        }
                        $lockedCapacity += (int) $quantity * (int) $lockedRoom->capacity;
                    }

                    if ($guestCount > $lockedCapacity) {
                        throw ValidationException::withMessages([
                            'guests' => 'The selected rooms can accommodate up to '.$lockedCapacity.' guests.',
                        ]);
                    }
                }

                $booking = Booking::create([
                    'user_id' => $request->user()?->id,
                    'booking_no' => $bookingNumber,
                    'checkout_token' => $checkoutToken,
                    'hold_expires_at' => $inventoryAllocations !== [] ? now()->addMinutes(15) : null,
                    'item_type' => $itemType,
                    'item_id' => $itemId,
                    'check_in' => $dates['check_in'],
                    'check_out' => $dates['check_out'],
                    'travel_date' => $dates['travel_date'],
                    'guest' => $guestCount,
                    'room_count' => $roomCount,
                    'contact_name' => $contact['name'],
                    'contact_phone' => $contact['phone'],
                    'contact_email' => $contact['email'],
                    'total_amount' => $baseAmount,
                    'taxable_amount' => $baseAmount,
                    'gst_percentage' => $pricing['gst_percentage'],
                    'gst_amount' => $gstAmount,
                    'gst_category' => $pricing['gst_category'],
                    'gst_breakdown' => $pricing['lines'],
                    'grand_total' => $amount,
                    'currency' => 'INR',
                    'payment_status' => 'pending',
                    'booking_status' => 'pending',
                ]);

                foreach ($bookingRooms as $room) {
                    BookingRoom::create([
                        'booking_id' => $booking->id,
                        'room_id' => $room['room_id'],
                        'quantity' => $room['quantity'],
                        'price' => $room['price'],
                    ]);
                }

                if ($itemType === 'package') {
                    BookingPackage::create([
                        'booking_id' => $booking->id,
                        'package_id' => $itemId,
                        'price' => $baseAmount,
                    ]);
                }

                $payment = Payment::create([
                    'booking_id' => $booking->id,
                    'user_id' => $request->user()?->id,
                    'user_email' => $contact['email'],
                    'gateway_name' => 'razorpay',
                    'currency' => 'INR',
                    'base_amount' => $baseAmount,
                    'gst_percentage' => $pricing['gst_percentage'],
                    'gst_amount' => $gstAmount,
                    'gst_category' => $pricing['gst_category'],
                    'amount' => $amount,
                    'payment_status' => 'created',
                ]);

                return [$booking, $payment];
            });
        } catch (UniqueConstraintViolationException $exception) {
            $existing = Booking::query()->where('checkout_token', $checkoutToken)->first();
            if ($existing) {
                return $this->resumeCheckout($existing, $itemType, $itemId);
            }

            throw $exception;
        }

        try {
            $response = $this->razorpayRequest()->post('/orders', [
                'amount' => $amountPaise,
                'currency' => 'INR',
                'receipt' => $bookingNumber,
                'notes' => [
                    'booking_id' => (string) $booking->id,
                    'booking_type' => $itemType,
                ],
            ]);
        } catch (ConnectionException $exception) {
            $this->markOrderCreationFailed($booking, $payment);
            Log::error('Razorpay order creation could not connect.', ['booking_id' => $booking->id]);

            return back()->withInput()->withErrors([
                'payment' => 'Could not connect to the payment service. Please try again.',
            ])->with('new_checkout_token', (string) Str::uuid());
        }

        $orderId = $response->json('id');
        if (
            ! $response->successful() ||
            ! is_string($orderId) ||
            $orderId === '' ||
            (int) $response->json('amount') !== $amountPaise ||
            $response->json('currency') !== 'INR'
        ) {
            $this->markOrderCreationFailed($booking, $payment);
            Log::error('Razorpay order creation failed.', [
                'booking_id' => $booking->id,
                'http_status' => $response->status(),
            ]);

            return back()->withInput()->withErrors([
                'payment' => 'The payment service could not start checkout. Please try again.',
            ])->with('new_checkout_token', (string) Str::uuid());
        }

        $payment->update(['gateway_order_id' => $orderId]);
        $booking->load('rooms.room.roomType');

        return view('frontend.payment.checkout', [
            'booking' => $booking,
            'payment' => $payment,
            'itemName' => $itemName,
            'key' => config('services.razorpay.key'),
            'amountPaise' => $amountPaise,
            'contact' => $contact,
        ]);
    }

    private function existingCheckout(string $checkoutToken, string $itemType, int $itemId)
    {
        $booking = Booking::query()
            ->where('checkout_token', $checkoutToken)
            ->with('payment')
            ->first();

        if ($booking) {
            abort_unless((int) $booking->user_id === (int) auth()->id(), 404);
        }

        return $booking ? $this->resumeCheckout($booking, $itemType, $itemId) : null;
    }

    private function forgetPendingBooking(Request $request, string $type, int $itemId): void
    {
        $pending = $request->session()->get('booking.pending');
        if (($pending['type'] ?? null) === $type && (int) ($pending['id'] ?? 0) === $itemId) {
            $request->session()->forget('booking.pending');
            $request->session()->forget('url.intended');
        }
    }

    private function resumeCheckout(Booking $booking, string $itemType, int $itemId)
    {
        abort_unless($booking->item_type === $itemType && (int) $booking->item_id === $itemId, 404);

        $payment = $booking->payment;
        if ($payment?->payment_status === 'paid') {
            return $this->result($booking, $payment, 'success', 'Your booking is confirmed and payment was received.');
        }

        if ($payment?->gateway_order_id && $payment->payment_status === 'created') {
            if ($booking->hold_expires_at && $booking->hold_expires_at->isPast()) {
                return back()->withInput()->withErrors([
                    'payment' => 'This room reservation expired. Please start a new booking to check availability again.',
                ])->with('new_checkout_token', (string) Str::uuid());
            }

            return view('frontend.payment.checkout', [
                'booking' => $booking,
                'payment' => $payment,
                'itemName' => $this->bookingItemName($booking),
                'key' => config('services.razorpay.key'),
                'amountPaise' => (int) round((float) $payment->amount * 100),
                'contact' => [
                    'name' => $booking->contact_name,
                    'email' => $booking->contact_email,
                    'phone' => $booking->contact_phone,
                ],
            ]);
        }

        return back()->withInput()->withErrors([
            'payment' => 'This checkout could not be resumed. Please start a new booking.',
        ])->with('new_checkout_token', (string) Str::uuid());
    }

    private function markOrderCreationFailed(Booking $booking, Payment $payment): void
    {
        DB::transaction(function () use ($booking, $payment): void {
            $payment->update(['payment_status' => 'failed']);
            $booking->update([
                'payment_status' => 'failed',
                'booking_status' => 'cancelled',
                'hold_expires_at' => null,
            ]);
        });
    }

    private function releaseFailedReservation(Booking $booking, Payment $payment): void
    {
        DB::transaction(function () use ($booking, $payment): void {
            $lockedPayment = Payment::query()->lockForUpdate()->findOrFail($payment->id);
            $lockedBooking = Booking::query()->lockForUpdate()->findOrFail($booking->id);

            if ($lockedPayment->payment_status === 'paid') {
                return;
            }

            $lockedPayment->update(['payment_status' => 'failed']);
            $lockedBooking->update([
                'payment_status' => 'failed',
                'booking_status' => 'cancelled',
                'hold_expires_at' => null,
            ]);
        });
    }

    private function razorpayRequest(): PendingRequest
    {
        return Http::baseUrl('https://api.razorpay.com/v1')
            ->withBasicAuth(
                (string) config('services.razorpay.key'),
                (string) config('services.razorpay.secret')
            )
            ->acceptJson()
            ->asJson()
            ->timeout(20);
    }

    private function result(Booking $booking, Payment $payment, string $status, string $message)
    {
        $booking->loadMissing('item');

        return response()->view('frontend.payment.result', [
            'booking' => $booking,
            'payment' => $payment,
            'itemName' => $this->bookingItemName($booking),
            'status' => $status,
            'message' => $message,
            'invoiceUrl' => $status === 'success'
                ? URL::temporarySignedRoute('payments.invoice', now()->addDays(30), ['payment' => $payment])
                : null,
        ], $status === 'pending' ? 202 : 200);
    }

    private function bookingItemName(Booking $booking): string
    {
        return match ($booking->item_type) {
            'hotel' => $booking->item?->hotel_name,
            'package' => $booking->item?->package_name,
            default => null,
        } ?? 'Your trip';
    }
}
