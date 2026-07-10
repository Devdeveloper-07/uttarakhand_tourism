@extends('layouts.admin')

@section('title', 'User Details')

@section('content')

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">User Details</h2>
            <p class="page-subtitle">View registered user information</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                <i class="fa-solid fa-pen"></i> Edit
            </a>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="content-card">
        <div class="card-header-custom">
            <div class="d-flex align-items-center gap-2">
                <h5><i class="fa-solid fa-user"></i> User Information</h5>
                @if(optional($user->detail)->status)
                    <span class="status-badge active">
                        <span class="status-dot"></span> Active
                    </span>
                @else
                    <span class="status-badge inactive">
                        <span class="status-dot"></span> Inactive
                    </span>
                @endif
            </div>
        </div>

        <div class="card-body-custom">
            <!-- User Image & Basic Info Row -->
            <div class="row align-items-center mb-4">
                <div class="col-md-3 text-center">
                    <div class="user-detail-avatar">
                        @if(optional($user->detail)->image)
                            <img src="{{ asset('uploads/users/'.optional($user->detail)->image) }}" alt="{{ $user->name }}">
                        @else
                            <div class="avatar-placeholder-xl">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        @endif
                    </div>
                    <div class="user-role-display mt-3">
                        <span class="role-badge">{{ ucfirst($user->role) }}</span>
                    </div>
                </div>

                <div class="col-md-9">
                    <!-- Row 1: Name & Email -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon" style="background: var(--primary-light); color: var(--primary);">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Name</span>
                                    <span class="info-value">{{ $user->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon" style="background: var(--info-light); color: var(--info);">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Email</span>
                                    <span class="info-value">{{ $user->email }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Mobile & Gender -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon" style="background: var(--success-light); color: var(--success);">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Mobile</span>
                                    <span class="info-value">{{ optional($user->detail)->mobile ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon" style="background: var(--warning-light); color: var(--warning);">
                                    <i class="fa-solid fa-venus-mars"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Gender</span>
                                    <span class="info-value">
                                        @if(optional($user->detail)->gender)
                                            <span class="gender-inline {{ strtolower(optional($user->detail)->gender) }}">
                                                <i class="fa-solid {{ optional($user->detail)->gender == 'Male' ? 'fa-mars' : (optional($user->detail)->gender == 'Female' ? 'fa-venus' : 'fa-genderless') }}"></i>
                                                {{ optional($user->detail)->gender }}
                                            </span>
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 3: Date of Birth & Status -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon" style="background: var(--purple-light, rgba(111, 66, 193, 0.1)); color: var(--purple, #6f42c1);">
                                    <i class="fa-solid fa-cake-candles"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Date of Birth</span>
                                    <span class="info-value">{{ optional($user->detail)->dob ? \Carbon\Carbon::parse($user->detail->dob)->format('d M Y') : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon" style="background: {{ optional($user->detail)->status ? 'var(--success-light)' : 'var(--danger-light)' }}; color: {{ optional($user->detail)->status ? 'var(--success)' : 'var(--danger)' }};">
                                    <i class="fa-solid {{ optional($user->detail)->status ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Status</span>
                                    <span class="info-value" style="color: {{ optional($user->detail)->status ? 'var(--success)' : 'var(--danger)' }};">
                                        {{ optional($user->detail)->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Section -->
            <div class="description-section mt-4">
                <h6>
                    <i class="fa-solid fa-location-dot"></i> Address Information
                </h6>
                <div class="address-grid">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="address-item">
                                <span class="address-label"><i class="fa-solid fa-map-pin text-primary"></i> Address</span>
                                <span class="address-value">{{ optional($user->detail)->address ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="address-item">
                                <span class="address-label"><i class="fa-solid fa-city text-primary"></i> City</span>
                                <span class="address-value">{{ optional($user->detail)->city ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="address-item">
                                <span class="address-label"><i class="fa-solid fa-flag text-primary"></i> State</span>
                                <span class="address-value">{{ optional($user->detail)->state ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="address-item">
                                <span class="address-label"><i class="fa-solid fa-globe text-primary"></i> Country</span>
                                <span class="address-value">{{ optional($user->detail)->country ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="address-item">
                                <span class="address-label"><i class="fa-solid fa-hashtag text-primary"></i> Pincode</span>
                                <span class="address-value">{{ optional($user->detail)->pincode ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meta Info -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="meta-item">
                        <i class="fa-solid fa-calendar-plus"></i>
                        <div>
                            <span class="meta-label">Registered On</span>
                            <span class="meta-value">{{ $user->created_at->format('d M Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="meta-item">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <div>
                            <span class="meta-label">Last Updated</span>
                            <span class="meta-value">{{ $user->updated_at->format('d M Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
