@extends('layouts.admin')

@section('title', 'Edit Room Type')

@section('content')

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Edit Room Type</h2>
            <p class="page-subtitle">Update room type information</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.room-types.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Room Types
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
            <h5><i class="fa-solid fa-pen-to-square"></i> Room Type Information</h5>
            <span class="text-muted small">All fields marked with <span class="text-danger">*</span> are required</span>
        </div>

        <div class="card-body-custom">
            <form action="{{ route('admin.room-types.update', $roomType->id) }}" method="POST" class="modern-form">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Room Type Name -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-signature text-primary"></i> Room Type Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control modern-input" value="{{ old('name', $roomType->name) }}" placeholder="Enter room type name" required>
                            <small class="form-hint">e.g. Deluxe, Suite, Standard</small>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-align-left text-primary"></i> Description
                            </label>
                            <textarea name="description" rows="5" class="form-control modern-input" placeholder="Describe the room type features and amenities...">{{ old('description', $roomType->description) }}</textarea>
                            <small class="form-hint">Optional - Add details about this room category</small>
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
                                    <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" {{ $roomType->status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusActive">
                                        <span class="status-indicator active"></span>
                                        Active
                                    </label>
                                </div>
                                <div class="form-check form-check-inline status-option">
                                    <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0" {{ !$roomType->status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusInactive">
                                        <span class="status-indicator inactive"></span>
                                        Inactive
                                    </label>
                                </div>
                            </div>
                            <small class="form-hint">Active room types can be assigned to rooms</small>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions mt-4 pt-4 border-top">
                    <button type="reset" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </button>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.room-types.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-xmark"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-check"></i> Update Room Type
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
