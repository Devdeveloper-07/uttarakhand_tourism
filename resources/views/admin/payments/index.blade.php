@extends('layouts.admin')

@section('title', 'Payments')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Payments</h1>
            <p class="page-subtitle">Review payment transactions linked to guest bookings.</p>
        </div>
        <span class="badge-count">{{ $payments->total() }} total</span>
    </div>

    <div class="content-card mb-4">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="row g-3 align-items-end p-3">
            <div class="col-lg-6">
                <label for="payment-search" class="form-label">Search transactions</label>
                <input id="payment-search" type="search" name="search" class="form-control"
                    value="{{ request('search') }}" placeholder="Transaction, booking, email or guest">
            </div>
            <div class="col-lg-4">
                <label for="payment-status" class="form-label">Payment status</label>
                <select id="payment-status" name="status" class="form-select">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>
                            {{ str()->headline($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Search
                </button>
                @if (request()->filled('search') || request()->filled('status'))
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary" aria-label="Clear filters">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="content-card">
        <div class="card-header-custom">
            <h2 class="h5 mb-0">Transaction records</h2>
            <span class="text-muted small">{{ $payments->count() }} shown</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Transaction</th>
                        <th>Booking</th>
                        <th>Customer</th>
                        <th>Gateway</th>
                        <th>Base / GST / Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th><span class="visually-hidden">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        @php
                            $statusClass = match (strtolower((string) $payment->payment_status)) {
                                'paid', 'completed' => 'text-bg-success',
                                'pending' => 'text-bg-warning',
                                'failed', 'refunded' => 'text-bg-danger',
                                default => 'text-bg-secondary',
                            };
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('admin.payments.show', $payment) }}" class="fw-semibold">
                                    {{ $payment->transaction_id ?: 'Payment #'.$payment->id }}
                                </a>
                                <div class="small text-muted">{{ $payment->payment_method ?: 'Payment method not recorded' }}</div>
                            </td>
                            <td>
                                @if ($payment->booking)
                                    <a href="{{ route('admin.bookings.show', $payment->booking) }}">
                                        {{ $payment->booking->booking_no ?: 'Booking #'.$payment->booking->id }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-break">{{ $payment->user_email ?: ($payment->booking?->contact_email ?? $payment->booking?->user?->email ?? '—') }}</td>
                            <td>{{ $payment->gateway_name ?: '—' }}</td>
                            <td class="text-nowrap">
                                <strong>{{ $payment->currency ?: 'INR' }} {{ number_format((float) $payment->amount, 2) }}</strong>
                                <div class="small text-muted">Base {{ number_format((float) ($payment->base_amount ?? 0), 2) }} · GST {{ number_format((float) ($payment->gst_amount ?? 0), 2) }}</div>
                            </td>
                            <td><span class="badge rounded-pill {{ $statusClass }}">{{ str()->headline($payment->payment_status ?: 'Unknown') }}</span></td>
                            <td>{{ $payment->payment_date?->format('d M Y, h:i A') ?? $payment->created_at?->format('d M Y, h:i A') ?? '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-outline-primary" aria-label="View payment">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state my-3">
                                    <i class="fa-solid fa-receipt fa-2x"></i>
                                    <h3 class="h5">No payments found</h3>
                                    <p>Try changing your filters. Recorded transactions will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($payments->hasPages())
            <div class="p-3">{{ $payments->links() }}</div>
        @endif
    </div>
@endsection
