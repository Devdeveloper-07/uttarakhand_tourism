@extends('layouts.admin')

@section('title', 'Edit Package')

@section('content')

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Edit Package</h2>
            <p class="page-subtitle">Update Tour Package Details</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Packages
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
            <h5><i class="fa-solid fa-pen-to-square"></i> Package Information</h5>
            <span class="text-muted small">All fields marked with <span class="text-danger">*</span> are required</span>
        </div>

        <div class="card-body-custom">
            <form action="{{ route('admin.packages.update', $package->id) }}" method="POST" enctype="multipart/form-data" class="modern-form">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Package Name -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-signature text-primary"></i> Package Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="package_name" class="form-control modern-input" value="{{ old('package_name', $package->package_name) }}" placeholder="Enter package name" required>
                            <small class="form-hint">e.g. Golden Triangle Tour</small>
                        </div>
                    </div>

                    <!-- Destination -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-location-dot text-primary"></i> Destination <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="destination" class="form-control modern-input" value="{{ old('destination', $package->destination) }}" placeholder="Enter destination" required>
                            <small class="form-hint">e.g. Uttarakhand, Goa, Kerala</small>
                        </div>
                    </div>

                    <!-- Duration -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-clock text-primary"></i> Duration (Days) <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="duration" class="form-control modern-input" value="{{ old('duration', $package->duration) }}" placeholder="No. of days" min="1" required>
                            <small class="form-hint">Total tour duration in days</small>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-indian-rupee-sign text-primary"></i> Price <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text modern-input-group">₹</span>
                                <input type="number" step="0.01" name="price" class="form-control modern-input" value="{{ old('price', $package->price) }}" placeholder="Enter price" min="0" required>
                            </div>
                            <small class="form-hint">Original price per person</small>
                        </div>
                    </div>

                    <!-- Discount Price -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-tag text-primary"></i> Discount Price
                            </label>
                            <div class="input-group">
                                <span class="input-group-text modern-input-group">₹</span>
                                <input type="number" step="0.01" name="discount_price" class="form-control modern-input" value="{{ old('discount_price', $package->discount_price) }}" placeholder="Enter discount price" min="0">
                            </div>
                            <small class="form-hint">Leave empty if no discount</small>
                        </div>
                    </div>

                    <!-- Max Persons -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-user-group text-primary"></i> Maximum Persons <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="max_person" class="form-control modern-input" value="{{ old('max_person', $package->max_person) }}" placeholder="Max group size" min="1" required>
                            <small class="form-hint">Maximum persons allowed per booking</small>
                        </div>
                    </div>

                    <!-- Package Image -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-image text-primary"></i> Package Image
                            </label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="thumbnail" class="form-control modern-input file-input" id="packageThumbnail" accept="image/*">
                                <div class="file-upload-preview" id="thumbnailPreview">
                                    @if($package->thumbnail)
                                        <img src="{{ asset('uploads/packages/'.$package->thumbnail) }}" style="max-width: 100%; max-height: 200px; border-radius: 8px;">
                                    @else
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                        <span>Click to upload or drag & drop</span>
                                        <small>PNG, JPG up to 5MB</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-align-left text-primary"></i> Description <span class="text-danger">*</span>
                            </label>
                            <textarea name="description" rows="4" class="form-control modern-input" placeholder="Describe the package, highlights, and what travelers can expect..." required>{{ old('description', $package->description) }}</textarea>
                            <small class="form-hint">Detailed description of the tour package</small>
                        </div>
                    </div>

                    <!-- Includes -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-check-circle text-success"></i> Includes
                            </label>
                            <textarea name="includes" rows="5" class="form-control modern-input" placeholder="List what's included in the package...&#10;- Hotel accommodation&#10;- Breakfast & dinner&#10;- Sightseeing">{{ old('includes', $package->includes) }}</textarea>
                            <small class="form-hint">What's included in the package</small>
                        </div>
                    </div>

                    <!-- Excludes -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-times-circle text-danger"></i> Excludes
                            </label>
                            <textarea name="excludes" rows="5" class="form-control modern-input" placeholder="List what's not included...&#10;- Airfare&#10;- Personal expenses&#10;- Travel insurance">{{ old('excludes', $package->excludes) }}</textarea>
                            <small class="form-hint">What's not included in the package</small>
                        </div>
                    </div>

                    <!-- Itinerary -->
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-route text-primary"></i> Itinerary
                            </label>
                            <textarea name="itinerary" rows="6" class="form-control modern-input" placeholder="Day-wise itinerary...&#10;Day 1: Arrival and check-in&#10;Day 2: Local sightseeing&#10;Day 3: Departure">{{ old('itinerary', $package->itinerary) }}</textarea>
                            <small class="form-hint">Day-wise tour itinerary</small>
                        </div>
                    </div>

                    <!-- Featured -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-star text-primary"></i> Featured <span class="text-danger">*</span>
                            </label>
                            <div class="status-toggle-group">
                                <div class="form-check form-check-inline status-option">
                                    <input class="form-check-input" type="radio" name="featured" id="featuredYes" value="1" {{ $package->featured ? 'checked' : '' }}>
                                    <label class="form-check-label" for="featuredYes">
                                        <span class="status-indicator active"></span>
                                        Yes
                                    </label>
                                </div>
                                <div class="form-check form-check-inline status-option">
                                    <input class="form-check-input" type="radio" name="featured" id="featuredNo" value="0" {{ !$package->featured ? 'checked' : '' }}>
                                    <label class="form-check-label" for="featuredNo">
                                        <span class="status-indicator inactive"></span>
                                        No
                                    </label>
                                </div>
                            </div>
                            <small class="form-hint">Featured packages appear on homepage</small>
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
                                    <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" {{ $package->status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusActive">
                                        <span class="status-indicator active"></span>
                                        Active
                                    </label>
                                </div>
                                <div class="form-check form-check-inline status-option">
                                    <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0" {{ !$package->status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusInactive">
                                        <span class="status-indicator inactive"></span>
                                        Inactive
                                    </label>
                                </div>
                            </div>
                            <small class="form-hint">Active packages are visible to users</small>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions mt-4 pt-4 border-top">
                    <button type="reset" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </button>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-xmark"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-check"></i> Update Package
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
    document.getElementById('packageThumbnail').addEventListener('change', function(e) {
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
            document.getElementById('packageThumbnail').files = e.dataTransfer.files;
            const reader = new FileReader();
            reader.onload = function(e) {
                dropZone.innerHTML = '<img src="' + e.target.result + '" style="max-width: 100%; max-height: 200px; border-radius: 8px;">';
                dropZone.classList.add('has-preview');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
