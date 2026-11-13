@extends('layouts.admin')

@section('title', 'Edit Room')

@section('content')

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Edit Room</h2>
            <p class="page-subtitle">Update room information</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Rooms
            </a>
        </div>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-modern alert-danger alert-dismissible fade show" role="alert">
            <div class="alert-icon"><i class="fa-solid fa-circle-exclamation"></i></div>
            <div class="alert-content">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Form Card -->
    <div class="content-card">
        <div class="card-header-custom">
            <h5><i class="fa-solid fa-bed"></i> Room Information</h5>
            <span class="text-muted small">All fields marked with <span class="text-danger">*</span> are required</span>
        </div>

        <div class="card-body-custom">
            <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data" class="modern-form">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Hotel -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-hotel text-primary"></i> Hotel <span class="text-danger">*</span>
                            </label>
                            <select name="hotel_id" class="form-select modern-input" required>
                                <option value="" disabled>Select a hotel</option>
                                @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->id }}" {{ $room->hotel_id == $hotel->id ? 'selected' : '' }}>
                                        {{ $hotel->hotel_name }} - {{ $hotel->city }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-hint">Choose the hotel where this room belongs</small>
                        </div>
                    </div>

                    <!-- Room Type -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-layer-group text-primary"></i> Room Type <span class="text-danger">*</span>
                            </label>
                            <select name="room_type_id" class="form-select modern-input" required>
                                <option value="" disabled>Select room type</option>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}" {{ $room->room_type_id == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-hint">e.g. Deluxe, Suite, Standard</small>
                        </div>
                    </div>

                    <!-- Room Name -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-signature text-primary"></i> Room Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="room_name" class="form-control modern-input" value="{{ old('room_name', $room->room_name) }}" placeholder="Enter room name" required>
                            <small class="form-hint">e.g. Ocean View Suite</small>
                        </div>
                    </div>

                    <!-- Room Number -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-hashtag text-primary"></i> Room Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="room_number" class="form-control modern-input" value="{{ old('room_number', $room->room_number) }}" placeholder="e.g. 101, A-202" required>
                            <small class="form-hint">Unique room identifier</small>
                        </div>
                    </div>

                    <!-- Capacity -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-user-group text-primary"></i> Capacity <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="capacity" class="form-control modern-input" value="{{ old('capacity', $room->capacity) }}" placeholder="No. of guests" min="1" required>
                            <small class="form-hint">Maximum guests allowed</small>
                        </div>
                    </div>

                    <!-- Total Rooms -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-door-closed text-primary"></i> Total Rooms <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="total_rooms" class="form-control modern-input" value="{{ old('total_rooms', $room->total_rooms) }}" placeholder="Total count" min="1" required>
                            <small class="form-hint">Total rooms of this type</small>
                        </div>
                    </div>

                    <!-- Price Per Night -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-indian-rupee-sign text-primary"></i> Price Per Night <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text modern-input-group">₹</span>
                                <input type="number" name="price_per_night" class="form-control modern-input" value="{{ old('price_per_night', $room->price_per_night) }}" placeholder="Enter price per night" min="0" required>
                            </div>
                            <small class="form-hint">Price in INR per night</small>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-toggle-on text-primary"></i> Status <span class="text-danger">*</span>
                            </label>
                            <div class="status-toggle-group">
                                <div class="form-check form-check-inline status-option">
                                    <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" {{ $room->status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusActive">
                                        <span class="status-indicator active"></span>
                                        Available
                                    </label>
                                </div>
                                <div class="form-check form-check-inline status-option">
                                    <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0" {{ !$room->status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusInactive">
                                        <span class="status-indicator inactive"></span>
                                        Unavailable
                                    </label>
                                </div>
                            </div>
                            <small class="form-hint">Available rooms can be booked by users</small>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-align-left text-primary"></i> Description
                            </label>
                            <textarea name="description" rows="4" class="form-control modern-input" placeholder="Describe the room features, amenities, and facilities...">{{ old('description', $room->description) }}</textarea>
                            <small class="form-hint">Optional - Add room details and amenities</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fa-solid fa-list-check text-primary"></i> Amenities</label>
                            <input type="text" name="amenities_text" class="form-control modern-input"
                                value="{{ old('amenities_text', implode(', ', $room->amenities ?? [])) }}"
                                placeholder="Wi-Fi, Air conditioning, Breakfast">
                            <small class="form-hint">Separate amenities with commas</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fa-solid fa-image text-primary"></i> Room Image</label>
                            @if ($room->image_path)
                                <img src="{{ asset('storage/'.$room->image_path) }}" alt="{{ $room->room_name }}"
                                    class="d-block rounded mb-2" style="max-width:180px;max-height:120px;object-fit:cover">
                            @endif
                            <input type="file" name="image" class="form-control modern-input" accept="image/*">
                            <small class="form-hint">Optional image, up to 5 MB</small>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions mt-4 pt-4 border-top">
                    <button type="reset" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </button>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-xmark"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-check"></i> Update Room
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
