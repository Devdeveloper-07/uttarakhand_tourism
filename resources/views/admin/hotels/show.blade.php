@extends('layouts.admin')

@section('title', 'Hotel Details')

@section('content')

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Hotel Details</h2>
            <p class="page-subtitle">View complete information about {{ $hotel->hotel_name }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Hotels
            </a>
        </div>
    </div>

    <!-- Hotel Detail Card -->
    <div class="content-card">
        <div class="card-header-custom">
            <div class="d-flex align-items-center gap-2">
                <h5><i class="fa-solid fa-hotel"></i> {{ $hotel->hotel_name }}</h5>
                <span class="status-badge {{ $hotel->status ? 'active' : 'inactive' }}">
                    <span class="status-dot"></span> {{ $hotel->status ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.hotels.edit', $hotel->id) }}" class="btn btn-warning btn-sm">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </a>
                <form action="{{ route('admin.hotels.destroy', $hotel->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this hotel?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa-solid fa-trash-can"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="card-body-custom">
            <div class="row g-4">

                <!-- Image Section -->
                <div class="col-lg-5">
                    <div class="hotel-image-section">
                        <div class="hotel-main-image">
                            @if($hotel->thumbnail)
                                <img src="{{ asset('uploads/hotels/'.$hotel->thumbnail) }}" alt="{{ $hotel->hotel_name }}">
                            @else
                                <div class="no-image-large">
                                    <i class="fa-solid fa-hotel"></i>
                                    <span>No Image Available</span>
                                </div>
                            @endif
                        </div>
                        <div class="hotel-quick-stats">
                            <div class="quick-stat">
                                <i class="fa-solid fa-star text-warning"></i>
                                <div>
                                    <span class="value">{{ $hotel->rating ?? 'N/A' }}</span>
                                    <span class="label">Rating</span>
                                </div>
                            </div>
                            <div class="quick-stat">
                                <i class="fa-solid fa-indian-rupee-sign text-success"></i>
                                <div>
                                    <span class="value">₹{{ number_format($hotel->starting_price) }}</span>
                                    <span class="label">Starting</span>
                                </div>
                            </div>
                            <div class="quick-stat">
                                <i class="fa-solid fa-calendar-check text-primary"></i>
                                <div>
                                    <span class="value">{{ $hotel->created_at->format('M Y') }}</span>
                                    <span class="label">Added</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Details Section -->
                <div class="col-lg-7">
                    <div class="hotel-details-section">

                        <!-- Info Grid -->
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-icon bg-primary-soft">
                                    <i class="fa-solid fa-signature text-primary"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Hotel Name</span>
                                    <span class="info-value">{{ $hotel->hotel_name }}</span>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-success-soft">
                                    <i class="fa-solid fa-city text-success"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">City</span>
                                    <span class="info-value">{{ $hotel->city }}</span>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-info-soft">
                                    <i class="fa-solid fa-location-dot text-info"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Location</span>
                                    <span class="info-value">{{ $hotel->location }}</span>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-warning-soft">
                                    <i class="fa-solid fa-map text-warning"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">State</span>
                                    <span class="info-value">{{ $hotel->state ?? 'Uttarakhand' }}</span>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-danger-soft">
                                    <i class="fa-solid fa-indian-rupee-sign text-danger"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Starting Price</span>
                                    <span class="info-value price">₹{{ number_format($hotel->starting_price, 2) }} <small>/ night</small></span>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-primary-soft">
                                    <i class="fa-solid fa-star text-primary"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Rating</span>
                                    <span class="info-value">
                                        @if($hotel->rating)
                                            {{ $hotel->rating }} <i class="fa-solid fa-star text-warning" style="font-size:12px;"></i>
                                        @else
                                            Not rated
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="description-section mt-4">
                            <h6><i class="fa-solid fa-align-left text-primary"></i> Description</h6>
                            <div class="description-box">
                                {{ $hotel->description }}
                            </div>
                        </div>

                        <!-- Meta Info -->
                        <div class="meta-info mt-4 pt-4 border-top">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="meta-item">
                                        <i class="fa-solid fa-calendar-plus text-muted"></i>
                                        <div>
                                            <span class="meta-label">Created</span>
                                            <span class="meta-value">{{ $hotel->created_at->format('M d, Y h:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="meta-item">
                                        <i class="fa-solid fa-calendar-check text-muted"></i>
                                        <div>
                                            <span class="meta-label">Last Updated</span>
                                            <span class="meta-value">{{ $hotel->updated_at->format('M d, Y h:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
