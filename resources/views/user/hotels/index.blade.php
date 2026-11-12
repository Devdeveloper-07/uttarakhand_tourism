@extends('layouts.user')

@section('title', 'My Hotel Bookings')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">My Hotel Bookings</h1>
            <p class="page-subtitle">Hotel reservations made from your account.</p>
        </div>
    </div>

    <section class="content-card">
        <div class="card-header-custom">
            <h2 class="h5 mb-0"><i class="fa-solid fa-hotel me-2"></i>Hotel reservations</h2>
            <span class="text-muted small">{{ $bookings->total() }} total</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Hotel</th>
                        <th>Location</th>
                        <th>Stay</th>
                        <th>Guests / rooms</th>
                        <th>Booking status</th>
                        <th><span class="visually-hidden">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        @php
                            $hotel = $booking->item;
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
                                    @if ($hotel?->thumbnail)
                                        <img src="{{ asset('uploads/hotels/'.$hotel->thumbnail) }}" class="table-item-image" alt="{{ $hotel->hotel_name }}">
                                    @else
                                        <span class="table-item-placeholder"><i class="fa-solid fa-hotel"></i></span>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $hotel?->hotel_name ?? 'Hotel listing unavailable' }}</div>
                                        <div class="small text-muted">{{ $booking->booking_no ?: 'Booking #'.$booking->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $hotel?->city ?: ($hotel?->location ?? '—') }}</td>
                            <td>
                                {{ $booking->check_in?->format('d M Y') ?? '—' }}<br>
                                <span class="small text-muted">to {{ $booking->check_out?->format('d M Y') ?? '—' }}</span>
                            </td>
                            <td>{{ $booking->guest ?? '—' }} / {{ $booking->room_count ?? '—' }}</td>
                            <td><span class="badge rounded-pill {{ $statusClass }}">{{ str()->headline($booking->booking_status ?: 'Unknown') }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('user.hotels.show', $booking->id) }}" class="btn btn-sm btn-outline-primary" aria-label="View hotel booking">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state my-3">
                                    <i class="fa-solid fa-hotel fa-2x"></i>
                                    <h2 class="h5">No hotel bookings yet</h2>
                                    <p>Your hotel reservations will appear here after you book a stay.</p>
                                    <a href="{{ route('hotels') }}" class="btn btn-primary mt-2">Explore hotels</a>
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
