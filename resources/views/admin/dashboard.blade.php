@extends('layouts.admin')

@section('content')
    <div class="dashboard-container">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Dashboard</h2>
                <p class="page-subtitle">Welcome back! Here's what's happening today.</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-6 col-md-4 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon bg-primary-soft">
                        <i class="fa-solid fa-hotel text-primary"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">{{ $totalHotels }}</h3>
                        <p class="stat-label">Total Hotels</p>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon bg-success-soft">
                        <i class="fa-solid fa-bed text-success"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">{{ $totalRooms }}</h3>
                        <p class="stat-label">Total Rooms</p>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon bg-warning-soft">
                        <i class="fa-solid fa-suitcase-rolling text-warning"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">{{ $totalPackages }}</h3>
                        <p class="stat-label">Total Packages</p>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon bg-info-soft">
                        <i class="fa-solid fa-calendar-check text-info"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">{{ $totalBookings }}</h3>
                        <p class="stat-label">Total Bookings</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon bg-primary-soft"><i class="fa-solid fa-users text-primary"></i></div>
                    <div class="stat-info"><h3 class="stat-number">{{ $totalUsers }}</h3><p class="stat-label">Total Users</p></div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon bg-info-soft"><i class="fa-solid fa-credit-card text-info"></i></div>
                    <div class="stat-info"><h3 class="stat-number">{{ $totalPayments }}</h3><p class="stat-label">Payments</p></div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon bg-success-soft"><i class="fa-solid fa-indian-rupee-sign text-success"></i></div>
                    <div class="stat-info"><h3 class="stat-number">₹{{ number_format((float) $totalRevenue, 0) }}</h3><p class="stat-label">Paid Revenue</p></div>
                </div>
            </div>
        </div>

        <!-- Welcome Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="welcome-card">
                    <div class="welcome-content">
                        <div class="welcome-icon">
                            <i class="fa-solid fa-mountain-sun welcome-logo" aria-hidden="true"></i>
                        </div>
                        <div class="welcome-text">
                            <h3>Welcome back, {{ auth()->user()->name }}! 👋</h3>
                            <p>Uttarakhand Travels admin panel — Manage your hotels, bookings, and content efficiently.</p>
                        </div>
                    </div>
                    <div class="welcome-actions">
                        <a href="{{ route('admin.hotels.create') }}" class="btn btn-light btn-sm">
                            <i class="fa-solid fa-plus"></i> New Hotel
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm">
                            <i class="fa-solid fa-eye"></i> View Site
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings & Quick Stats -->
        <div class="row g-4 dashboard-overview-row">
            <div class="col-lg-8">
                <div class="content-card mb-4 recent-bookings-card">
                    <div class="card-header-custom">
                        <h5><i class="fa-solid fa-calendar-check"></i> Recent Bookings</h5>
                        <a href="{{ route('admin.bookings.index') }}" class="view-all">View all</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Guest</th>
                                    <th>Trip</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th><span class="visually-hidden">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentBookings as $booking)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.bookings.show', $booking) }}" class="fw-semibold">
                                                {{ $booking->booking_no ?: 'Booking #'.$booking->id }}
                                            </a>
                                        </td>
                                        <td>{{ $booking->contact_name ?: ($booking->user?->name ?? 'Guest') }}</td>
                                        <td>{{ data_get($booking->item, 'hotel_name') ?? data_get($booking->item, 'package_name') ?? str()->headline(class_basename($booking->item_type ?? 'trip')) }}</td>
                                        <td><span class="badge rounded-pill bg-primary-subtle text-primary-emphasis">{{ str()->headline($booking->booking_status ?: 'Unknown') }}</span></td>
                                        <td class="text-nowrap">{{ $booking->currency ?: 'INR' }} {{ number_format((float) ($booking->grand_total ?? $booking->total_amount ?? 0), 2) }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary" aria-label="View booking">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6"><div class="empty-state my-3"><i class="fa-regular fa-calendar-xmark fa-2x"></i><h3 class="h5">No bookings yet</h3><p>New reservations will appear here.</p></div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="col-lg-4 dashboard-side-panels">
                <div class="content-card mb-4">
                    <div class="card-header-custom">
                        <h5><i class="fa-solid fa-chart-column"></i> Booking Status</h5>
                    </div>
                    <div class="card-body-custom">
                        @forelse ($bookingStatusStats as $statusStat)
                            @php
                                $statusPercent = (int) round(($statusStat->total / $maxBookingStatusCount) * 100);
                            @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between gap-2 mb-1">
                                    <span>{{ str()->headline($statusStat->status) }}</span>
                                    <strong>{{ $statusStat->total }}</strong>
                                </div>
                                <div class="progress" role="progressbar"
                                    aria-label="{{ str()->headline($statusStat->status) }} bookings"
                                    aria-valuenow="{{ $statusPercent }}" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar" style="width: {{ $statusPercent }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">Booking statistics will appear when reservations are created.</p>
                        @endforelse
                    </div>
                </div>

                <div class="content-card">
                    <div class="card-header-custom">
                        <h5><i class="fa-solid fa-bolt"></i> Quick Actions</h5>
                    </div>

                    <div class="card-body-custom">

                        <div class="quick-actions">

                            <!-- Add Hotel -->
                            <a href="{{ route('admin.hotels.create') }}" class="quick-action-item">

                                <div class="quick-icon bg-primary-soft">
                                    <i class="fa-solid fa-hotel text-primary"></i>
                                </div>

                                <span>Add Hotel</span>

                            </a>

                            <!-- Add Room -->
                            <a href="{{ route('admin.rooms.create') }}" class="quick-action-item">

                                <div class="quick-icon bg-success-soft">
                                    <i class="fa-solid fa-bed text-success"></i>
                                </div>

                                <span>Add Room</span>

                            </a>

                            <!-- Add Room Type -->
                            <a href="{{ route('admin.room-types.create') }}" class="quick-action-item">

                                <div class="quick-icon bg-warning-soft">
                                    <i class="fa-solid fa-layer-group text-warning"></i>
                                </div>

                                <span>Add Room Type</span>

                            </a>

                            <!-- Add Package -->
                            <a href="{{ route('admin.packages.create') }}" class="quick-action-item">

                                <div class="quick-icon bg-info-soft">
                                    <i class="fa-solid fa-suitcase text-info"></i>
                                </div>

                                <span>Add Package</span>

                            </a>

                            <a href="{{ route('admin.bookings.index') }}" class="quick-action-item">
                                <div class="quick-icon bg-primary-soft">
                                    <i class="fa-solid fa-calendar-check text-primary"></i>
                                </div>
                                <span>View Bookings</span>
                            </a>

                            <a href="{{ route('admin.payments.index') }}" class="quick-action-item">
                                <div class="quick-icon bg-success-soft">
                                    <i class="fa-solid fa-credit-card text-success"></i>
                                </div>
                                <span>View Payments</span>
                            </a>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
