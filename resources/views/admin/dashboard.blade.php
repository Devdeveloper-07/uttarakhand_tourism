@extends('layouts.admin')

@section('content')
    <div class="dashboard-container">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Dashboard</h2>
                <p class="page-subtitle">Welcome back! Here's what's happening today.</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-download"></i> Export Report
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-primary-soft">
                        <i class="fa-solid fa-hotel text-primary"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">{{ \App\Models\Hotel::count() }}</h3>
                        <p class="stat-label">Total Hotels</p>
                    </div>
                    <div class="stat-trend positive">
                        <i class="fa-solid fa-arrow-trend-up"></i> +12%
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-success-soft">
                        <i class="fa-solid fa-bed text-success"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">{{ \App\Models\Room::count() }}</h3>
                        <p class="stat-label">Total Rooms</p>
                    </div>
                    <div class="stat-trend positive">
                        <i class="fa-solid fa-arrow-trend-up"></i> +8%
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-warning-soft">
                        <i class="fa-solid fa-suitcase-rolling text-warning"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">{{ \App\Models\Package::count() }}</h3>
                        <p class="stat-label">Total Packages</p>
                    </div>
                    <div class="stat-trend positive">
                        <i class="fa-solid fa-arrow-trend-up"></i> +15%
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-info-soft">
                        <i class="fa-solid fa-calendar-check text-info"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">{{ \App\Models\Booking::count() }}</h3>
                        <p class="stat-label">Total Bookings</p>
                    </div>
                    <div class="stat-trend negative">
                        <i class="fa-solid fa-arrow-trend-down"></i> -3%
                    </div>
                </div>
            </div>
        </div>

        <!-- Welcome Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="welcome-card">
                    <div class="welcome-content">
                        <div class="welcome-icon">
                            <img src="{{ asset('assets/logo/logo.png') }}" alt="Uttarakhand Tourism Logo"
                                class="welcome-logo">
                        </div>
                        <div class="welcome-text">
                            <h3>Welcome back, {{ auth()->user()->name }}! 👋</h3>
                            <p>Uttarakhand Tourism Admin Panel — Manage your hotels, bookings, and content efficiently.</p>
                        </div>
                    </div>
                    <div class="welcome-actions">
                        <a href="#" class="btn btn-light btn-sm">
                            <i class="fa-solid fa-plus"></i> New Hotel
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm">
                            <i class="fa-solid fa-eye"></i> View Site
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Quick Stats -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="content-card">
                    <div class="card-header-custom">
                        <h5><i class="fa-solid fa-clock-rotate-left"></i> Recent Activity</h5>
                        <a href="#" class="view-all">View All</a>
                    </div>
                    <div class="card-body-custom">
                        <div class="activity-list">

                            @forelse($activities as $activity)
                                <div class="activity-item">

                                    <div class="activity-icon bg-{{ $activity['color'] }}">

                                        <i class="fa-solid {{ $activity['icon'] }}"></i>

                                    </div>

                                    <div class="activity-details">

                                        <p class="activity-text">

                                            {{ $activity['title'] }}

                                            <strong>

                                                {{ $activity['message'] }}

                                            </strong>

                                        </p>

                                        <span class="activity-time">

                                            {{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}

                                        </span>

                                    </div>

                                </div>

                            @empty

                                <div class="text-center py-5">

                                    <i class="fa-solid fa-clock fa-3x text-secondary"></i>

                                    <h5 class="mt-3">

                                        No Recent Activity

                                    </h5>

                                </div>
                            @endforelse

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
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

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
