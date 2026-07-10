@extends('layouts.admin')

@section('title', 'Room Details')

@section('content')

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Room Details</h2>
            <p class="page-subtitle">View complete room information</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-warning btn-sm">
                <i class="fa-solid fa-pen"></i> Edit
            </a>
        </div>
    </div>

    <!-- Room Info Card -->
    <div class="content-card">
        <div class="card-header-custom">
            <div class="d-flex align-items-center gap-2">
                <h5><i class="fa-solid fa-bed"></i> Room Information</h5>
                @if($room->status)
                    <span class="status-badge active">
                        <span class="status-dot"></span> Available
                    </span>
                @else
                    <span class="status-badge inactive">
                        <span class="status-dot"></span> Unavailable
                    </span>
                @endif
            </div>
        </div>

        <div class="card-body-custom">
            <!-- Row 1: Hotel & Room Type -->
            <div class="row">
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-icon" style="background: var(--primary-light); color: var(--primary);">
                            <i class="fa-solid fa-hotel"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Hotel</span>
                            <span class="info-value">{{ $room->hotel->hotel_name }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-icon" style="background: var(--primary-light); color: var(--primary);">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Room Type</span>
                            <span class="info-value">{{ $room->roomType->name }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 2: Room Name & Room Number -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-icon" style="background: var(--primary-light); color: var(--primary);">
                            <i class="fa-solid fa-signature"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Room Name</span>
                            <span class="info-value">{{ $room->room_name }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-icon" style="background: var(--primary-light); color: var(--primary);">
                            <i class="fa-solid fa-hashtag"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Room Number</span>
                            <span class="info-value">{{ $room->room_number }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 3: Capacity, Total, Available -->
            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="info-item">
                        <div class="info-icon" style="background: var(--info-light); color: var(--info);">
                            <i class="fa-solid fa-user-group"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Capacity</span>
                            <span class="info-value">{{ $room->capacity }} <small>Guests</small></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-item">
                        <div class="info-icon" style="background: var(--warning-light); color: var(--warning);">
                            <i class="fa-solid fa-door-closed"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Total Rooms</span>
                            <span class="info-value">{{ $room->total_rooms }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-item">
                        <div class="info-icon" style="background: var(--success-light); color: var(--success);">
                            <i class="fa-solid fa-door-open"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Available</span>
                            <span class="info-value">{{ $room->available_rooms }} <small>left</small></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 4: Price & Status -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-icon" style="background: var(--success-light); color: var(--success);">
                            <i class="fa-solid fa-indian-rupee-sign"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Price Per Night</span>
                            <span class="info-value price">₹ {{ number_format($room->price_per_night, 2) }} <small>/night</small></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-icon" style="background: {{ $room->status ? 'var(--success-light)' : 'var(--danger-light)' }}; color: {{ $room->status ? 'var(--success)' : 'var(--danger)' }};">
                            <i class="fa-solid {{ $room->status ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Status</span>
                            <span class="info-value" style="color: {{ $room->status ? 'var(--success)' : 'var(--danger)' }};">
                                {{ $room->status ? 'Available' : 'Unavailable' }}
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
                    {{ $room->description ?? 'No description available' }}
                </div>
            </div>

            <!-- Meta Info -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="meta-item">
                        <i class="fa-solid fa-calendar-plus"></i>
                        <div>
                            <span class="meta-label">Created At</span>
                            <span class="meta-value">{{ $room->created_at->format('d M Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="meta-item">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <div>
                            <span class="meta-label">Last Updated</span>
                            <span class="meta-value">{{ $room->updated_at->format('d M Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
