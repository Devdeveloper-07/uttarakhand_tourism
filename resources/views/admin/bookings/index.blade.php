@extends('layouts.admin')

@section('title', 'Bookings')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Bookings</h1>
            <p class="page-subtitle">Review guest reservations and their current status.</p>
        </div>
        <span class="badge-count">{{ $bookings->total() }} total</span>
    </div>

    <div class="content-card mb-4">
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-3 align-items-end p-3">
            <div class="col-lg-6">
                <label for="booking-search" class="form-label">Search bookings</label>
                <input id="booking-search" type="search" name="search" class="form-control"
                    value="{{ request('search') }}" placeholder="Reference, guest or email">
            </div>
            <div class="col-lg-4">
                <label for="booking-status" class="form-label">Booking status</label>
                <select id="booking-status" name="status" class="form-select">
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
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary" aria-label="Clear filters">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="content-card">
        <div class="card-header-custom">
            <h2 class="h5 mb-0">Reservation records</h2>
            <span class="text-muted small">{{ $bookings->count() }} shown</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Guest</th>
                        <th>Stay / trip</th>
                        <th>Dates</th>
                        <th>Total</th>
                        <th>Booking status</th>
                        <th>Payment</th>
                        <th><span class="visually-hidden">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
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
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="fw-semibold">
                                    {{ $booking->booking_no ?: 'Booking #'.$booking->id }}
                                </a>
                                <div class="small text-muted">Created {{ $booking->created_at?->format('d M Y') }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $booking->contact_name ?: ($booking->user?->name ?? 'Guest') }}</div>
                                <div class="small text-muted">{{ $booking->contact_email ?: $booking->user?->email }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">
                                    {{ data_get($booking->item, 'hotel_name') ?? data_get($booking->item, 'package_name') ?? str()->headline(class_basename($booking->item_type ?? 'trip')) }}
                                </div>
                                <div class="small text-muted">{{ str()->headline(class_basename($booking->item_type ?? 'booking')) }}</div>
                            </td>
                            <td>
                                @if ($booking->check_in || $booking->check_out)
                                    {{ $booking->check_in?->format('d M Y') ?? '—' }}<br>
                                    <span class="small text-muted">to {{ $booking->check_out?->format('d M Y') ?? '—' }}</span>
                                @else
                                    {{ $booking->travel_date?->format('d M Y') ?? '—' }}
                                @endif
                            </td>
                            <td class="text-nowrap">
                                {{ $booking->currency ?: 'INR' }}
                                {{ number_format((float) ($booking->grand_total ?? $booking->total_amount ?? 0), 2) }}
                                <div class="small text-muted">Base {{ number_format((float) ($booking->taxable_amount ?? $booking->total_amount ?? 0), 2) }} · GST {{ number_format((float) ($booking->gst_amount ?? 0), 2) }}</div>
                            </td>
                            <td><span class="badge rounded-pill {{ $bookingStatusClass }}">{{ str()->headline($booking->booking_status ?: 'Unknown') }}</span></td>
                            <td><span class="badge rounded-pill {{ $paymentStatusClass }}">{{ str()->headline($booking->payment_status ?: 'Unknown') }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary" aria-label="View booking">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state my-3">
                                    <i class="fa-regular fa-calendar-xmark fa-2x"></i>
                                    <h3 class="h5">No bookings found</h3>
                                    <p>Try changing your filters. New reservations will appear here when available.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($bookings->hasPages())
            <div class="p-3">{{ $bookings->links() }}</div>
        @endif
    </div>
@endsection
