<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Payment::query()->with(['booking.user', 'booking.item']);

        if ($request->filled('status')) {
            $query->where('payment_status', $request->string('status')->toString());
        }

        if ($request->filled('search')) {
            $search = trim($request->string('search')->toString());

            $query->where(function (Builder $query) use ($search) {
                $query->where('transaction_id', 'like', "%{$search}%")
                    ->orWhere('gateway_order_id', 'like', "%{$search}%")
                    ->orWhere('user_email', 'like', "%{$search}%")
                    ->orWhereHas('booking', function (Builder $bookingQuery) use ($search) {
                        $bookingQuery->where('booking_no', 'like', "%{$search}%")
                            ->orWhere('contact_name', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $query->latest()->paginate(15)->withQueryString();
        $statuses = Payment::query()
            ->whereNotNull('payment_status')
            ->distinct()
            ->orderBy('payment_status')
            ->pluck('payment_status');

        return view('admin.payments.index', compact('payments', 'statuses'));
    }

    public function show(Payment $payment): View
    {
        $payment->load(['booking.user', 'booking.item']);

        return view('admin.payments.show', compact('payment'));
    }
}
