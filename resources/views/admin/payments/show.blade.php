@extends('layouts.admin')

@section('title', 'Payment Details')

@section('content')
    @php
        $statusClass = match (strtolower((string) $payment->payment_status)) {
            'paid', 'completed' => 'text-bg-success',
            'pending' => 'text-bg-warning',
            'failed', 'refunded' => 'text-bg-danger',
            default => 'text-bg-secondary',
        };
    @endphp

    <div class="page-header">
        <div>
            <p class="page-subtitle mb-1">Transaction details</p>
            <h1 class="page-title">{{ $payment->transaction_id ?: 'Payment #'.$payment->id }}</h1>
        </div>
        <div class="page-actions">
            <span class="badge rounded-pill {{ $statusClass }}">{{ str()->headline($payment->payment_status ?: 'Unknown') }}</span>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to payments
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <section class="content-card h-100">
                <div class="card-header-custom"><h2 class="h5 mb-0">Transaction information</h2></div>
                <div class="card-body-custom">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 text-muted">Payment ID</dt>
                        <dd class="col-sm-7">#{{ $payment->id }}</dd>
                        <dt class="col-sm-5 text-muted">Transaction ID</dt>
                        <dd class="col-sm-7 text-break">{{ $payment->transaction_id ?: '—' }}</dd>
                        <dt class="col-sm-5 text-muted">Gateway order ID</dt>
                        <dd class="col-sm-7 text-break">{{ $payment->gateway_order_id ?: '—' }}</dd>
                        <dt class="col-sm-5 text-muted">Gateway payment ID</dt>
                        <dd class="col-sm-7 text-break">{{ $payment->gateway_payment_id ?: '—' }}</dd>
                        <dt class="col-sm-5 text-muted">Gateway</dt>
                        <dd class="col-sm-7">{{ $payment->gateway_name ?: '—' }}</dd>
                        <dt class="col-sm-5 text-muted">Method</dt>
                        <dd class="col-sm-7">{{ $payment->payment_method ?: '—' }}</dd>
                        <dt class="col-sm-5 text-muted">Customer email</dt>
                        <dd class="col-sm-7 text-break">{{ $payment->user_email ?: ($payment->booking?->contact_email ?? $payment->booking?->user?->email ?? '—') }}</dd>
                        <dt class="col-sm-5 text-muted">Payment date</dt>
                        <dd class="col-sm-7">{{ $payment->payment_date?->format('d M Y, h:i A') ?? $payment->created_at?->format('d M Y, h:i A') ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted">GST category</dt>
                        <dd class="col-sm-7">{{ $payment->gst_category ? str()->headline($payment->gst_category) : 'Not recorded' }}</dd>
                    </dl>
                </div>
            </section>
        </div>
        <div class="col-lg-5">
            <section class="content-card h-100">
                <div class="card-header-custom"><h2 class="h5 mb-0">Amount and booking</h2></div>
                <div class="card-body-custom">
                    <dl class="row mb-3">
                        <dt class="col-7 text-muted">Base amount</dt>
                        <dd class="col-5 text-end">{{ $payment->currency ?: 'INR' }} {{ number_format((float) ($payment->base_amount ?? 0), 2) }}</dd>
                        <dt class="col-7 text-muted">GST ({{ $payment->gst_percentage ?? 0 }}%)</dt>
                        <dd class="col-5 text-end">{{ $payment->currency ?: 'INR' }} {{ number_format((float) ($payment->gst_amount ?? 0), 2) }}</dd>
                        <dt class="col-7 fw-bold">Paid amount</dt>
                        <dd class="col-5 text-end fw-bold">{{ $payment->currency ?: 'INR' }} {{ number_format((float) $payment->amount, 2) }}</dd>
                    </dl>
                    @if ($payment->booking)
                        <a href="{{ route('admin.bookings.show', $payment->booking) }}" class="btn btn-outline-primary w-100">
                            View {{ $payment->booking->booking_no ?: 'booking #'.$payment->booking->id }}
                        </a>
                        @if ($payment->payment_status === 'paid')
                            <a href="{{ route('admin.payments.invoice', $payment) }}" class="btn btn-outline-secondary w-100 mt-2">
                                <i class="fa-solid fa-file-invoice me-1"></i> View invoice
                            </a>
                        @endif
                    @else
                        <p class="small text-muted mb-0">No booking is linked to this transaction.</p>
                    @endif
                </div>
            </section>
        </div>
    </div>
@endsection
