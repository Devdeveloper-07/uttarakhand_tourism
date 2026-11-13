<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::query()->with(['user', 'payment', 'item']);

        if ($request->filled('status')) {
            $query->where('booking_status', $request->string('status')->toString());
        }

        if ($request->filled('search')) {
            $search = trim($request->string('search')->toString());

            $query->where(function (Builder $query) use ($search) {
                $query->where('booking_no', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
                    ->orWhere('contact_email', 'like', "%{$search}%")
                    ->orWhereHas('user', function (Builder $userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();
        $statuses = Booking::query()
            ->whereNotNull('booking_status')
            ->distinct()
            ->orderBy('booking_status')
            ->pluck('booking_status');

        return view('admin.bookings.index', compact('bookings', 'statuses'));
    }

    public function show(Booking $booking): View
    {
        $booking->load(['user', 'payment', 'item']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        DB::transaction(function () use ($booking): void {
            $lockedBooking = Booking::query()->lockForUpdate()->findOrFail($booking->id);
            if ($lockedBooking->booking_status === 'cancelled') {
                return;
            }

            if (! in_array($lockedBooking->booking_status, ['pending', 'confirmed'], true)) {
                abort(422, 'Only pending or confirmed bookings can be cancelled.');
            }

            $lockedBooking->update([
                'booking_status' => 'cancelled',
                'hold_expires_at' => null,
            ]);

            $payment = Payment::query()->where('booking_id', $lockedBooking->id)->lockForUpdate()->first();
            if ($payment && $payment->payment_status !== 'paid') {
                $payment->update(['payment_status' => 'failed']);
            }
        });

        $booking->refresh();
        $message = $booking->payment_status === 'paid'
            ? 'Booking cancelled and inventory released. Process any applicable refund according to your cancellation policy.'
            : 'Booking cancelled and room inventory released.';

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', $message);
    }
}
