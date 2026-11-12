@extends('layouts.user')

@section('title', 'Package Booking Details')

@section('content')
    @php
        $statusClass = match (strtolower((string) $booking->booking_status)) {
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
    @endphp

    <div class="page-header">
        <div>
            <p class="page-subtitle mb-1">Tour reservation</p>
            <h1 class="page-title">{{ $booking->booking_no ?: 'Booking #'.$booking->id }}</h1>
        </div>
        <div class="page-actions">
            <a href="{{ route('user.packages') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to my package bookings
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <section class="content-card h-100">
                @if ($package->thumbnail)
                    <img src="{{ asset('uploads/packages/'.$package->thumbnail) }}" class="booking-detail-image" alt="{{ $package->package_name }}">
                @else
                    <div class="booking-detail-placeholder"><i class="fa-solid fa-suitcase-rolling"></i></div>
                @endif
                <div class="card-body-custom">
                    <span class="text-muted small">Booked package</span>
                    <h2 class="h4 mt-1">{{ $package->package_name }}</h2>
                    <p class="text-muted mb-0">{{ $package->destination ?: 'Destination not listed' }} · {{ $package->duration ?: 'Duration not listed' }}</p>
                </div>
            </section>
        </div>
        <div class="col-lg-7">
            <section class="content-card h-100">
                <div class="card-header-custom">
                    <h2 class="h5 mb-0">Your trip</h2>
                    <span class="badge rounded-pill {{ $statusClass }}">{{ str()->headline($booking->booking_status ?: 'Unknown') }}</span>
                </div>
                <div class="card-body-custom">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 text-muted">Travel date</dt>
                        <dd class="col-sm-7">{{ $booking->travel_date?->format('d M Y') ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted">Travelers</dt>
                        <dd class="col-sm-7">{{ $booking->guest ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted">Payment status</dt>
                        <dd class="col-sm-7"><span class="badge rounded-pill {{ $paymentStatusClass }}">{{ str()->headline($booking->payment_status ?: 'Unknown') }}</span></dd>
                        <dt class="col-sm-5 text-muted">Total</dt>
                        <dd class="col-sm-7 fw-bold">{{ $booking->currency ?: 'INR' }} {{ number_format((float) ($booking->grand_total ?? $booking->total_amount ?? 0), 2) }}</dd>
                        <dt class="col-sm-5 text-muted">Booked on</dt>
                        <dd class="col-sm-7">{{ $booking->created_at?->format('d M Y, h:i A') ?? '—' }}</dd>
                    </dl>
                </div>
            </section>
        </div>
    </div>

    @if ($package->description)
        <section class="content-card mt-4">
            <div class="card-header-custom"><h2 class="h5 mb-0">About this package</h2></div>
            <div class="card-body-custom">{{ $package->description }}</div>
        </section>
    @endif
@endsection
