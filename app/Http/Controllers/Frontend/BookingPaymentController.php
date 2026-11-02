<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingPackage;
use App\Models\Hotel;
use App\Models\Package;
use App\Models\Payment;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BookingPaymentController extends Controller
{
    public function startHotel(Request $request, Hotel $hotel)
    {
        abort_unless($hotel->status, 404);

        $validated = $request->validate([
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['required', 'integer', 'min:1', 'max:20'],
            'rooms' => ['required', 'integer', 'min:1', 'max:4'],
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'phone' => ['required', 'string', 'max:24', 'regex:/^(?=.*[0-9])[0-9+\-\s()]+$/'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        if ($redirect = $this->gatewayConfigurationError()) {
            return $redirect;
        }

        $nights = (int) Carbon::parse($validated['check_in'])->diffInDays(
            Carbon::parse($validated['check_out'])
        );
        $amountPaise = (int) round((float) $hotel->starting_price * 100) * $nights * $validated['rooms'];

        return $this->createCheckout(
            $request,
            itemType: 'hotel',
            itemId: $hotel->id,
            itemName: $hotel->hotel_name,
            amountPaise: $amountPaise,
            guestCount: $validated['guests'],
            roomCount: $validated['rooms'],
            contact: $validated,
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

        $validated = $request->validate([
            'travel_date' => ['required', 'date', 'after_or_equal:today'],
            'travelers' => ['required', 'integer', 'min:1', 'max:20'],
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'phone' => ['required', 'string', 'max:24', 'regex:/^(?=.*[0-9])[0-9+\-\s()]+$/'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        if ($package->max_person && $validated['travelers'] > $package->max_person) {
            return back()
                ->withInput()
                ->withErrors(['travelers' => 'This package is limited to '.$package->max_person.' travelers.']);
        }

        if ($redirect = $this->gatewayConfigurationError()) {
            return $redirect;
        }

        $amountPaise = (int) round($package->booking_price * 100) * $validated['travelers'];

        return $this->createCheckout(
            $request,
            itemType: 'package',
            itemId: $package->id,
            itemName: $package->package_name,
            amountPaise: $amountPaise,
            guestCount: $validated['travelers'],
            roomCount: 1,
            contact: $validated,
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
                return $this->result(
                    $booking,
                    $payment,
                    'failed',
                    'The payment was not captured. Please try again or contact support.'
                );
            }

            DB::transaction(function () use ($payment, $booking, $validated, $gatewayData): void {
                $lockedPayment = Payment::query()->lockForUpdate()->findOrFail($payment->id);
                $lockedBooking = Booking::query()->lockForUpdate()->findOrFail($booking->id);

                if ($lockedPayment->payment_status === 'paid') {
                    return;
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
                    ],
                ]);

                $lockedBooking->update([
                    'payment_status' => 'paid',
                    'booking_status' => 'confirmed',
                ]);
            });
        }

        return $this->result($booking->fresh(), $payment->fresh(), 'success', 'Your booking is confirmed and payment was received.');
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
        int $amountPaise,
        int $guestCount,
        int $roomCount,
        array $contact,
        array $dates
    ) {
        if ($amountPaise < 100) {
            return back()->withInput()->withErrors(['payment' => 'The booking amount must be at least ₹1.']);
        }

        $amount = $amountPaise / 100;
        $bookingNumber = 'BK-'.now()->format('Ymd').'-'.Str::upper(Str::random(8));

        [$booking, $payment] = DB::transaction(function () use (
            $request,
            $itemType,
            $itemId,
            $guestCount,
            $roomCount,
            $contact,
            $dates,
            $amount,
            $bookingNumber
        ): array {
            $booking = Booking::create([
                'user_id' => $request->user()?->id,
                'booking_no' => $bookingNumber,
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
                'total_amount' => $amount,
                'taxable_amount' => $amount,
                'gst_percentage' => 0,
                'gst_amount' => 0,
                'grand_total' => $amount,
                'currency' => 'INR',
                'payment_status' => 'pending',
                'booking_status' => 'pending',
            ]);

            if ($itemType === 'package') {
                BookingPackage::create([
                    'booking_id' => $booking->id,
                    'package_id' => $itemId,
                    'price' => $amount,
                ]);
            }

            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $request->user()?->id,
                'user_email' => $contact['email'],
                'gateway_name' => 'razorpay',
                'currency' => 'INR',
                'base_amount' => $amount,
                'gst_percentage' => 0,
                'gst_amount' => 0,
                'amount' => $amount,
                'payment_status' => 'created',
            ]);

            return [$booking, $payment];
        });

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
            ]);
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
            ]);
        }

        $payment->update(['gateway_order_id' => $orderId]);

        return view('frontend.payment.checkout', [
            'booking' => $booking,
            'payment' => $payment,
            'itemName' => $itemName,
            'key' => config('services.razorpay.key'),
            'amountPaise' => $amountPaise,
            'contact' => $contact,
        ]);
    }

    private function markOrderCreationFailed(Booking $booking, Payment $payment): void
    {
        DB::transaction(function () use ($booking, $payment): void {
            $payment->update(['payment_status' => 'failed']);
            $booking->update([
                'payment_status' => 'failed',
                'booking_status' => 'cancelled',
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
        $itemName = match ($booking->item_type) {
            'hotel' => $booking->item?->hotel_name,
            'package' => $booking->item?->package_name,
            default => null,
        };

        return response()->view('frontend.payment.result', [
            'booking' => $booking,
            'payment' => $payment,
            'itemName' => $itemName ?? 'Your trip',
            'status' => $status,
            'message' => $message,
        ], $status === 'pending' ? 202 : 200);
    }
}
