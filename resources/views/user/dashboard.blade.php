@extends('layouts.user')

@section('title', 'User Dashboard')

@section('content')

    <div class="dashboard-container">

        <!-- ================= Header ================= -->
        <div class="page-header">
            <div>
                <h2 class="page-title">
                    Welcome, {{ auth()->user()->name }} 👋
                </h2>
                <p class="page-subtitle">
                    Manage your bookings, profile and explore Uttarakhand Travels.
                </p>
            </div>
            <div class="page-actions">
                <a href="{{ route('hotels') }}" class="btn btn-primary">
                    <i class="fa-solid fa-hotel"></i>
                    Explore Hotels
                </a>
            </div>
        </div>

        <!-- ================= Stats ================= -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-primary-soft">
                        <i class="fa-solid fa-calendar-check text-primary"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">
                            {{ $totalBookings }}
                        </h3>
                        <p class="stat-label">
                            Total Bookings
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-success-soft">
                        <i class="fa-solid fa-suitcase text-success"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">
                            {{ $totalPackages }}
                        </h3>
                        <p class="stat-label">
                            Packages
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-warning-soft">
                        <i class="fa-solid fa-star text-warning"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">
                            {{ $totalReviews }}
                        </h3>
                        <p class="stat-label">
                            Reviews
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon bg-info-soft">
                        <i class="fa-solid fa-wallet text-info"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number">
                            ₹{{ number_format($totalPayments) }}
                        </h3>
                        <p class="stat-label">
                            Payments
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= Welcome Banner ================= -->
        <div class="welcome-card mb-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h3>
                        Welcome Back, {{ auth()->user()->name }} 🌄
                    </h3>
                    <p>
                        Explore the beauty of Uttarakhand. Book hotels, discover tour packages and manage all your trips
                        from one place.
                    </p>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="{{ route('packages') }}" class="btn btn-light">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Explore Now
                    </a>
                </div>
            </div>
        </div>

        <!-- ================= Content Row ================= -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Recent Packages -->
                <div class="content-card">
                    <div class="card-header-custom">
                        <h5>
                            <i class="fa-solid fa-suitcase"></i>
                            Recent Packages
                        </h5>
                        <a href="{{ route('user.packages') }}" class="view-all">
                            View All
                        </a>
                    </div>
                    <div class="card-body-custom">
                        @forelse($recentPackages as $package)
                            <div class="booking-item">
                                <div class="booking-image bg-primary-soft">
                                    <i class="fa-solid fa-suitcase text-primary"></i>
                                </div>
                                <div class="booking-details">
                                    <h6>
                                        {{ $package->package?->package_name ?? 'Tour Package' }}
                                    </h6>
                                    <p>
                                        Booking ID : {{ $package->booking?->booking_no ?? '#'.$package->booking_id }}
                                    </p>
                                    <small class="text-muted">
                                        {{ ($package->booking?->created_at ?? $package->created_at)->format('d M Y') }}
                                    </small>
                                </div>
                                <div class="booking-price">
                                    @if (isset($package->price))
                                        <span class="price">
                                            ₹{{ number_format($package->price) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <hr>
                        @empty
                            <div class="empty-state">
                                <i class="fa-solid fa-box-open fa-3x text-secondary"></i>
                                <h5 class="mt-3">
                                    No Packages Found
                                </h5>
                                <p class="text-muted">
                                    Start exploring Uttarakhand tour packages.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4">

                <div class="content-card mt-4">
                    <div class="card-body-custom text-center profile-card">
                        @if (auth()->user()->detail && auth()->user()->detail->image)
                            <img src="{{ asset('uploads/users/' . auth()->user()->detail->image) }}"
                                class="rounded-circle shadow profile-image" width="90" height="90">
                        @else
                            <div class="profile-avatar">
                                <i class="fa-solid fa-user fa-2x"></i>
                            </div>
                        @endif
                        <h5 class="mt-3">
                            {{ auth()->user()->name }}
                        </h5>
                        <p class="email">
                            <i class="fa-solid fa-envelope"></i>
                            {{ auth()->user()->email }}
                        </p>
                        <a href="{{ route('user.profile') }}" class="btn btn-primary w-100">
                            <i class="fa-solid fa-user-pen"></i>
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
