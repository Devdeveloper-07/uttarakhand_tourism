@extends('layouts.user')

@section('title', 'My Package Bookings')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">My Package Bookings</h1>
            <p class="page-subtitle">Your booked tours and their travel details.</p>
        </div>
    </div>

    <section class="content-card">
        <div class="card-header-custom">
            <h2 class="h5 mb-0"><i class="fa-solid fa-suitcase-rolling me-2"></i>Tour reservations</h2>
            <span class="text-muted small">{{ $bookings->total() }} total</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Package</th>
                        <th>Destination</th>
                        <th>Travel date</th>
                        <th>Travelers</th>
                        <th>Booking status</th>
                        <th>Total</th>
                        <th><span class="visually-hidden">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        @php
                            $package = $booking->item;
                            $statusClass = match (strtolower((string) $booking->booking_status)) {
                                'confirmed', 'completed' => 'text-bg-success',
                                'pending' => 'text-bg-warning',
                                'cancelled', 'failed' => 'text-bg-danger',
                                default => 'text-bg-secondary',
                            };
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if ($package?->thumbnail)
                                        <img src="{{ asset('uploads/packages/'.$package->thumbnail) }}" class="table-item-image" alt="{{ $package->package_name }}">
                                    @else
                                        <span class="table-item-placeholder"><i class="fa-solid fa-suitcase-rolling"></i></span>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $package?->package_name ?? 'Package listing unavailable' }}</div>
                                        <div class="small text-muted">{{ $booking->booking_no ?: 'Booking #'.$booking->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $package?->destination ?? '—' }}</td>
                            <td>{{ $booking->travel_date?->format('d M Y') ?? '—' }}</td>
                            <td>{{ $booking->guest ?? '—' }}</td>
                            <td><span class="badge rounded-pill {{ $statusClass }}">{{ str()->headline($booking->booking_status ?: 'Unknown') }}</span></td>
                            <td class="text-nowrap">{{ $booking->currency ?: 'INR' }} {{ number_format((float) ($booking->grand_total ?? $booking->total_amount ?? 0), 2) }}</td>
                            <td class="text-end">
                                <a href="{{ route('user.packages.show', $booking->id) }}" class="btn btn-sm btn-outline-primary" aria-label="View package booking">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state my-3">
                                    <i class="fa-solid fa-suitcase-rolling fa-2x"></i>
                                    <h2 class="h5">No package bookings yet</h2>
                                    <p>Your tour reservations will appear here after you book a package.</p>
                                    <a href="{{ route('packages') }}" class="btn btn-primary mt-2">Explore packages</a>
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
    </section>
@endsection
