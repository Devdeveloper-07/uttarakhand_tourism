@extends('layouts.admin')

@section('title', 'Room Type Details')

@section('content')

<div class="dashboard-container">

    <!-- Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Room Type Details</h2>
            <p class="page-subtitle">View complete room type information</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.room-types.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('admin.room-types.edit', $roomType->id) }}" class="btn btn-warning btn-sm">
                <i class="fa-solid fa-pen"></i> Edit
            </a>
        </div>
    </div>

    <!-- Room Type Info Card -->
    <div class="content-card">
        <div class="card-header-custom">
            <div class="d-flex align-items-center gap-2">
                <h5><i class="fa-solid fa-layer-group"></i> Room Type Information</h5>
                @if($roomType->status)
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
            <div class="row">
                <!-- Room Type Name -->
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-icon" style="background: var(--primary-light); color: var(--primary);">
                            <i class="fa-solid fa-signature"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Room Type Name</span>
                            <span class="info-value">{{ $roomType->name }}</span>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-icon" style="background: {{ $roomType->status ? 'var(--success-light)' : 'var(--danger-light)' }}; color: {{ $roomType->status ? 'var(--success)' : 'var(--danger)' }};">
                            <i class="fa-solid {{ $roomType->status ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Status</span>
                            <span class="info-value" style="color: {{ $roomType->status ? 'var(--success)' : 'var(--danger)' }};">
                                {{ $roomType->status ? 'Active' : 'Inactive' }}
                            </span>
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
                    {{ $roomType->description ?? 'No Description Available' }}
                </div>
            </div>

            <!-- Meta Info -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="meta-item">
                        <i class="fa-solid fa-calendar-plus"></i>
                        <div>
                            <span class="meta-label">Created At</span>
                            <span class="meta-value">{{ $roomType->created_at->format('d M Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="meta-item">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <div>
                            <span class="meta-label">Last Updated</span>
                            <span class="meta-value">{{ $roomType->updated_at->format('d M Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
