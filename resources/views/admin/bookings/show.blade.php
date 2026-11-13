@extends('layouts.admin')

@section('title', 'Booking Details')

@section('content')
    @php
        $bookingStatusClass = match (strtolower((string) $booking->booking_status)) {
            'confirmed', 'completed' => 'text-bg-success',
            'pending' => 'text-bg-warning',
            'cancelled', 'failed' => 'text-bg-danger',
            default => 'text-bg-secondary',
        };
        $paymentStatusClass = match (strtolower((string) $booking->payment_status)) {
            'paid', 'completed' => 'text-bg-success',
            'pending' => 'text-bg-warning',
            'failed', 'refunded' => 'text-bg-danger',
            default => 'text-bg-secondary',
        };
        $itemName = data_get($booking->item, 'hotel_name')
            ?? data_get($booking->item, 'package_name')
            ?? str()->headline(class_basename($booking->item_type ?? 'trip'));
    @endphp

    <div class="page-header">
        <div>
            <p class="page-subtitle mb-1">Reservation details</p>
            <h1 class="page-title">{{ $booking->booking_no ?: 'Booking #'.$booking->id }}</h1>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to bookings
            </a>
            @if (in_array($booking->booking_status, ['pending', 'confirmed'], true))
                <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}"
                    onsubmit="return confirm('Cancel this booking and release its room inventory?')">
                    @csrf
                    <button class="btn btn-outline-danger" type="submit">
                        <i class="fa-solid fa-ban me-1"></i> Cancel booking
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="content-card mb-4">
        <div class="card-body-custom">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                <div>
                    <span class="text-muted small">Booked experience</span>
                    <h2 class="h4 mt-1 mb-1">{{ $itemName }}</h2>
                    <span class="text-muted">{{ str()->headline(class_basename($booking->item_type ?? 'booking')) }}</span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge rounded-pill {{ $bookingStatusClass }}">{{ str()->headline($booking->booking_status ?: 'Unknown') }}</span>
                    <span class="badge rounded-pill {{ $paymentStatusClass }}">Payment: {{ str()->headline($booking->payment_status ?: 'Unknown') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <section class="content-card h-100">
                <div class="card-header-custom"><h2 class="h5 mb-0">Guest and trip</h2></div>
                <div class="card-body-custom">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 text-muted">Contact name</dt>
                        <dd class="col-sm-7">{{ $booking->contact_name ?: ($booking->user?->name ?? '—') }}</dd>
                        <dt class="col-sm-5 text-muted">Email</dt>
                        <dd class="col-sm-7 text-break">{{ $booking->contact_email ?: ($booking->user?->email ?? '—') }}</dd>
                        <dt class="col-sm-5 text-muted">Phone</dt>
                        <dd class="col-sm-7">{{ $booking->contact_phone ?: '—' }}</dd>
                        <dt class="col-sm-5 text-muted">Guests</dt>
                        <dd class="col-sm-7">{{ $booking->guest ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted">Rooms</dt>
                        <dd class="col-sm-7">{{ $booking->room_count ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted">Check-in</dt>
                        <dd class="col-sm-7">{{ $booking->check_in?->format('d M Y') ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted">Check-out</dt>
                        <dd class="col-sm-7">{{ $booking->check_out?->format('d M Y') ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted">Travel date</dt>
                        <dd class="col-sm-7">{{ $booking->travel_date?->format('d M Y') ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted">Created</dt>
                        <dd class="col-sm-7">{{ $booking->created_at?->format('d M Y, h:i A') ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted">GST category</dt>
                        <dd class="col-sm-7">{{ $booking->gst_category ? str()->headline($booking->gst_category) : 'Historical booking / not recorded' }}</dd>
                    </dl>
                </div>
            </section>
        </div>
        <div class="col-lg-5">
            <section class="content-card h-100">
                <div class="card-header-custom"><h2 class="h5 mb-0">Payment summary</h2></div>
                <div class="card-body-custom">
                    <dl class="row mb-3">
                        <dt class="col-7 text-muted">Subtotal</dt>
                        <dd class="col-5 text-end">{{ $booking->currency ?: 'INR' }} {{ number_format((float) ($booking->taxable_amount ?? $booking->total_amount ?? 0), 2) }}</dd>
                        <dt class="col-7 text-muted">GST ({{ $booking->gst_percentage ?? 0 }}%)</dt>
                        <dd class="col-5 text-end">{{ $booking->currency ?: 'INR' }} {{ number_format((float) ($booking->gst_amount ?? 0), 2) }}</dd>
                        <dt class="col-7 fw-bold">Total</dt>
                        <dd class="col-5 text-end fw-bold">{{ $booking->currency ?: 'INR' }} {{ number_format((float) ($booking->grand_total ?? $booking->total_amount ?? 0), 2) }}</dd>
                    </dl>
                    @if (is_array($booking->gst_breakdown) && count($booking->gst_breakdown))
                        <h3 class="h6">GST calculation details</h3>
                        @foreach ($booking->gst_breakdown as $line)
                            <div class="border-top py-2 small">
                                <div class="d-flex justify-content-between gap-3">
                                    <strong>{{ $line['description'] }}</strong>
                                    <span>{{ number_format((float) $line['gst_percentage'], 2) }}%</span>
                                </div>
                                <div class="d-flex justify-content-between gap-3 text-muted">
                                    <span>Base {{ $booking->currency ?: 'INR' }} {{ number_format((float) $line['base_amount'], 2) }}</span>
                                    <span>GST {{ $booking->currency ?: 'INR' }} {{ number_format((float) $line['gst_amount'], 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    @if ($booking->payment)
                        <a href="{{ route('admin.payments.show', $booking->payment) }}" class="btn btn-outline-primary w-100">
                            View payment record
                        </a>
                        @if ($booking->payment->payment_status === 'paid')
                            <a href="{{ route('admin.payments.invoice', $booking->payment) }}" class="btn btn-outline-secondary w-100 mt-2">
                                <i class="fa-solid fa-file-invoice me-1"></i> View invoice
                            </a>
                        @endif
                    @else
                        <p class="small text-muted mb-0">No payment record is linked to this booking.</p>
                    @endif
                </div>
            </section>
        </div>
    </div>
@endsection
