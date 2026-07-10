@extends('layouts.admin')

@section('title', 'Add Hotel')

@section('content')

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Add New Hotel</h2>
            <p class="page-subtitle">Create a new hotel listing for Uttarakhand Tourism</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Hotels
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
            <h5><i class="fa-solid fa-hotel"></i> Hotel Information</h5>
            <span class="text-muted small">All fields marked with <span class="text-danger">*</span> are required</span>
        </div>

        <div class="card-body-custom">
            <form action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data" class="modern-form">
                @csrf

                <div class="row g-4">
                    <!-- Hotel Name -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-signature text-primary"></i> Hotel Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="hotel_name" class="form-control modern-input" value="{{ old('hotel_name') }}" placeholder="Enter hotel name" required>
                            <small class="form-hint">e.g. Hotel Grand Himalaya</small>
                        </div>
                    </div>

                    <!-- City -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-city text-primary"></i> City <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="city" class="form-control modern-input" value="{{ old('city') }}" placeholder="Enter city name" required>
                            <small class="form-hint">e.g. Nainital, Mussoorie</small>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-location-dot text-primary"></i> Location <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="location" class="form-control modern-input" value="{{ old('location') }}" placeholder="Enter detailed location" required>
                            <small class="form-hint">Full address or landmark</small>
                        </div>
                    </div>

                    <!-- Starting Price -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-indian-rupee-sign text-primary"></i> Starting Price <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text modern-input-group">₹</span>
                                <input type="number" name="starting_price" class="form-control modern-input" value="{{ old('starting_price') }}" placeholder="Enter price per night" required>
                            </div>
                            <small class="form-hint">Price per night in INR</small>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-align-left text-primary"></i> Description <span class="text-danger">*</span>
                            </label>
                            <textarea name="description" rows="5" class="form-control modern-input" placeholder="Describe the hotel, amenities, and unique features..." required>{{ old('description') }}</textarea>
                            <small class="form-hint">Minimum 50 characters recommended</small>
                        </div>
                    </div>

                    <!-- Thumbnail -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-image text-primary"></i> Hotel Image
                            </label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="thumbnail" class="form-control modern-input file-input" id="hotelThumbnail" accept="image/*">
                                <div class="file-upload-preview" id="thumbnailPreview">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <span>Click to upload or drag & drop</span>
                                    <small>PNG, JPG up to 5MB</small>
                                </div>
                            </div>
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
                                    <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" checked>
                                    <label class="form-check-label" for="statusActive">
                                        <span class="status-indicator active"></span>
                                        Active
                                    </label>
                                </div>
                                <div class="form-check form-check-inline status-option">
                                    <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0">
                                    <label class="form-check-label" for="statusInactive">
                                        <span class="status-indicator inactive"></span>
                                        Inactive
                                    </label>
                                </div>
                            </div>
                            <small class="form-hint">Active hotels will be visible to users</small>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions mt-4 pt-4 border-top">
                    <button type="reset" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </button>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-xmark"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-check"></i> Save Hotel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    // File upload preview
    document.getElementById('hotelThumbnail').addEventListener('change', function(e) {
        const preview = document.getElementById('thumbnailPreview');
        const file = e.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = '<img src="' + e.target.result + '" style="max-width: 100%; max-height: 200px; border-radius: 8px;">';
                preview.classList.add('has-preview');
            }
            reader.readAsDataURL(file);
        }
    });

    // Drag and drop
    const dropZone = document.getElementById('thumbnailPreview');

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');

        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            document.getElementById('hotelThumbnail').files = e.dataTransfer.files;
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = '<img src="' + e.target.result + '" style="max-width: 100%; max-height: 200px; border-radius: 8px;">';
                preview.classList.add('has-preview');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
