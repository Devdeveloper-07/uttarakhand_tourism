@extends('layouts.admin')

@section('title', 'Testimonial Details')

@section('content')

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Testimonial Details</h2>
            <p class="page-subtitle">View complete customer testimonial information</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="btn btn-warning btn-sm">
                <i class="fa-solid fa-pen"></i> Edit
            </a>
        </div>
    </div>

    <!-- Testimonial Info Card -->
    <div class="content-card">
        <div class="card-header-custom">
            <div class="d-flex align-items-center gap-2">
                <h5><i class="fa-solid fa-comments"></i> Customer Information</h5>
                @if($testimonial->status)
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
            <!-- Customer Image & Basic Info Row -->
            <div class="row align-items-center mb-4">
                <div class="col-md-3 text-center">
                    <div class="testimonial-detail-avatar">
                        @if($testimonial->image)
                            <img src="{{ asset('uploads/testimonials/'.$testimonial->image) }}" alt="{{ $testimonial->name }}">
                        @else
                            <div class="avatar-placeholder-xl">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        @endif
                    </div>
                    <div class="rating-display-lg mt-3">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $testimonial->rating)
                                <i class="fa-solid fa-star text-warning"></i>
                            @else
                                <i class="fa-regular fa-star text-secondary"></i>
                            @endif
                        @endfor
                        <div class="rating-text">{{ $testimonial->rating }} out of 5</div>
                    </div>
                </div>

                <div class="col-md-9">
                    <!-- Row 1: Name & Designation -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon" style="background: var(--primary-light); color: var(--primary);">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Customer Name</span>
                                    <span class="info-value">{{ $testimonial->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon" style="background: var(--info-light); color: var(--info);">
                                    <i class="fa-solid fa-briefcase"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Designation</span>
                                    <span class="info-value">{{ $testimonial->designation ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Rating & Status -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon" style="background: var(--warning-light); color: var(--warning);">
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Rating</span>
                                    <span class="info-value">
                                        {{ $testimonial->rating }} / 5
                                        <span class="rating-stars-inline">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $testimonial->rating)
                                                    <i class="fa-solid fa-star text-warning"></i>
                                                @else
                                                    <i class="fa-regular fa-star text-secondary"></i>
                                                @endif
                                            @endfor
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon" style="background: {{ $testimonial->status ? 'var(--success-light)' : 'var(--danger-light)' }}; color: {{ $testimonial->status ? 'var(--success)' : 'var(--danger)' }};">
                                    <i class="fa-solid {{ $testimonial->status ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Status</span>
                                    <span class="info-value" style="color: {{ $testimonial->status ? 'var(--success)' : 'var(--danger)' }};">
                                        {{ $testimonial->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Section -->
            <div class="description-section mt-4">
                <h6>
                    <i class="fa-solid fa-align-left"></i> Customer Review
                </h6>
                <div class="description-box testimonial-message">
                    {!! nl2br(e($testimonial->message)) !!}
                </div>
            </div>

            <!-- Meta Info -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="meta-item">
                        <i class="fa-solid fa-calendar-plus"></i>
                        <div>
                            <span class="meta-label">Created At</span>
                            <span class="meta-value">{{ $testimonial->created_at->format('d M Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="meta-item">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <div>
                            <span class="meta-label">Last Updated</span>
                            <span class="meta-value">{{ $testimonial->updated_at->format('d M Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
