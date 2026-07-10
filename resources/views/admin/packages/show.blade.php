@extends('layouts.admin')

@section('title', 'Package Details')

@section('content')

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Package Details</h2>
            <p class="page-subtitle">View complete package information</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-warning btn-sm">
                <i class="fa-solid fa-pen"></i> Edit
            </a>
        </div>
    </div>

    <!-- Package Info Card -->
    <div class="content-card">
        <div class="card-header-custom">
            <div class="d-flex align-items-center gap-2">
                <h5><i class="fa-solid fa-suitcase"></i> Package Information</h5>
                @if($package->status)
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
            <!-- Image + Info Grid -->
            <div class="row">
                <!-- Package Image -->
                <div class="col-lg-4 mb-4">
                    <div class="hotel-main-image">
                        @if($package->thumbnail)
                            <img src="{{ asset('uploads/packages/'.$package->thumbnail) }}" alt="{{ $package->package_name }}">
                        @else
                            <div class="no-image-large">
                                <i class="fa-solid fa-suitcase"></i>
                                <span>No Image Available</span>
                            </div>
                        @endif
                    </div>

                    <!-- Quick Stats -->
                    <div class="hotel-quick-stats mt-3">
                        <div class="quick-stat">
                            <i class="fa-solid fa-clock text-primary"></i>
                            <span class="value">{{ $package->duration }}</span>
                            <span class="label">Days</span>
                        </div>
                        <div class="quick-stat">
                            <i class="fa-solid fa-user-group text-info"></i>
                            <span class="value">{{ $package->max_person }}</span>
                            <span class="label">Max Persons</span>
                        </div>
                        <div class="quick-stat">
                            <i class="fa-solid fa-star text-warning"></i>
                            <span class="value">{{ $package->featured ? 'Yes' : 'No' }}</span>
                            <span class="label">Featured</span>
                        </div>
                    </div>
                </div>

                <!-- Package Details -->
                <div class="col-lg-8">
                    <div class="info-grid">
                        <!-- Package Name -->
                        <div class="info-item">
                            <div class="info-icon" style="background: var(--primary-light); color: var(--primary);">
                                <i class="fa-solid fa-signature"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Package Name</span>
                                <span class="info-value">{{ $package->package_name }}</span>
                            </div>
                        </div>

                        <!-- Destination -->
                        <div class="info-item">
                            <div class="info-icon" style="background: var(--danger-light); color: var(--danger);">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Destination</span>
                                <span class="info-value">{{ $package->destination }}</span>
                            </div>
                        </div>

                        <!-- Duration -->
                        <div class="info-item">
                            <div class="info-icon" style="background: var(--info-light); color: var(--info);">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Duration</span>
                                <span class="info-value">{{ $package->duration }} <small>Days</small></span>
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="info-item">
                            <div class="info-icon" style="background: var(--success-light); color: var(--success);">
                                <i class="fa-solid fa-indian-rupee-sign"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Price</span>
                                <span class="info-value price">₹ {{ number_format($package->price, 2) }}</span>
                            </div>
                        </div>

                        <!-- Discount Price -->
                        <div class="info-item">
                            <div class="info-icon" style="background: var(--warning-light); color: var(--warning);">
                                <i class="fa-solid fa-tag"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Discount Price</span>
                                <span class="info-value price">₹ {{ number_format($package->discount_price ?? 0, 2) }}</span>
                            </div>
                        </div>

                        <!-- Max Persons -->
                        <div class="info-item">
                            <div class="info-icon" style="background: var(--info-light); color: var(--info);">
                                <i class="fa-solid fa-user-group"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Maximum Persons</span>
                                <span class="info-value">{{ $package->max_person }} <small>Persons</small></span>
                            </div>
                        </div>

                        <!-- Featured -->
                        <div class="info-item">
                            <div class="info-icon" style="background: {{ $package->featured ? 'var(--primary-light)' : 'var(--gray-100)' }}; color: {{ $package->featured ? 'var(--primary)' : 'var(--gray-500)' }};">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Featured</span>
                                <span class="info-value" style="color: {{ $package->featured ? 'var(--primary)' : 'var(--gray-500)' }};">
                                    {{ $package->featured ? 'Yes' : 'No' }}
                                </span>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="info-item">
                            <div class="info-icon" style="background: {{ $package->status ? 'var(--success-light)' : 'var(--danger-light)' }}; color: {{ $package->status ? 'var(--success)' : 'var(--danger)' }};">
                                <i class="fa-solid {{ $package->status ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Status</span>
                                <span class="info-value" style="color: {{ $package->status ? 'var(--success)' : 'var(--danger)' }};">
                                    {{ $package->status ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Section -->
            <div class="description-section mt-4">
                <h6>
                    <i class="fa-solid fa-align-left"></i> Description
                </h6>
                <div class="description-box">
                    {!! nl2br(e($package->description)) !!}
                </div>
            </div>

            <!-- Includes & Excludes -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="description-section">
                        <h6>
                            <i class="fa-solid fa-check-circle text-success"></i> Includes
                        </h6>
                        <div class="description-box" style="border-left: 4px solid var(--success);">
                            {!! nl2br(e($package->includes)) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="description-section">
                        <h6>
                            <i class="fa-solid fa-times-circle text-danger"></i> Excludes
                        </h6>
                        <div class="description-box" style="border-left: 4px solid var(--danger);">
                            {!! nl2br(e($package->excludes)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Itinerary -->
            <div class="description-section mt-4">
                <h6>
                    <i class="fa-solid fa-route text-primary"></i> Itinerary
                </h6>
                <div class="description-box" style="border-left: 4px solid var(--primary);">
                    {!! nl2br(e($package->itinerary)) !!}
                </div>
            </div>

            <!-- Meta Info -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="meta-item">
                        <i class="fa-solid fa-calendar-plus"></i>
                        <div>
                            <span class="meta-label">Created At</span>
                            <span class="meta-value">{{ $package->created_at->format('d M Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="meta-item">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <div>
                            <span class="meta-label">Last Updated</span>
                            <span class="meta-value">{{ $package->updated_at->format('d M Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
