@extends('layouts.admin')

@section('title', 'Edit Testimonial')

@section('content')

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Edit Testimonial</h2>
            <p class="page-subtitle">Update customer testimonial information</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Testimonials
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
            <h5><i class="fa-solid fa-comments"></i> Testimonial Information</h5>
            <span class="text-muted small">All fields marked with <span class="text-danger">*</span> are required</span>
        </div>

        <div class="card-body-custom">
            <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data" class="modern-form">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Customer Name -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-user text-primary"></i> Customer Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control modern-input" value="{{ old('name', $testimonial->name) }}" placeholder="Enter customer name" required>
                            <small class="form-hint">e.g. John Doe</small>
                        </div>
                    </div>

                    <!-- Designation -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-briefcase text-primary"></i> Designation
                            </label>
                            <input type="text" name="designation" class="form-control modern-input" value="{{ old('designation', $testimonial->designation) }}" placeholder="e.g. Tourist, Traveler, Blogger">
                            <small class="form-hint">Customer's role or profession</small>
                        </div>
                    </div>

                    <!-- Change Image -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-image text-primary"></i> Change Image
                            </label>
                            <div class="image-upload-wrapper">
                                <input type="file" name="image" class="form-control modern-input" accept="image/*" id="testimonialImage">
                                <small class="form-hint">Upload new image to replace current one (300x300px recommended)</small>
                                <div class="image-preview mt-2" id="newImagePreview" style="display: none;">
                                    <img src="" alt="New Preview" class="preview-img rounded-circle">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Image -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-eye text-primary"></i> Current Image
                            </label>
                            <div class="current-image-display">
                                @if($testimonial->image)
                                    <img src="{{ asset('uploads/testimonials/'.$testimonial->image) }}" alt="{{ $testimonial->name }}" class="current-preview-img rounded-circle">
                                    <small class="form-hint d-block mt-1">Current uploaded image</small>
                                @else
                                    <div class="avatar-placeholder-lg">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <small class="form-hint d-block mt-1">No image uploaded</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Rating -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-star text-primary"></i> Rating <span class="text-danger">*</span>
                            </label>
                            <div class="rating-select-group">
                                <div class="form-check form-check-inline rating-option">
                                    <input class="form-check-input" type="radio" name="rating" id="rating5" value="5" {{ old('rating', $testimonial->rating) == 5 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating5">
                                        <i class="fa-solid fa-star text-warning"></i> 5
                                    </label>
                                </div>
                                <div class="form-check form-check-inline rating-option">
                                    <input class="form-check-input" type="radio" name="rating" id="rating4" value="4" {{ old('rating', $testimonial->rating) == 4 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating4">
                                        <i class="fa-solid fa-star text-warning"></i> 4
                                    </label>
                                </div>
                                <div class="form-check form-check-inline rating-option">
                                    <input class="form-check-input" type="radio" name="rating" id="rating3" value="3" {{ old('rating', $testimonial->rating) == 3 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating3">
                                        <i class="fa-solid fa-star text-warning"></i> 3
                                    </label>
                                </div>
                                <div class="form-check form-check-inline rating-option">
                                    <input class="form-check-input" type="radio" name="rating" id="rating2" value="2" {{ old('rating', $testimonial->rating) == 2 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating2">
                                        <i class="fa-solid fa-star text-warning"></i> 2
                                    </label>
                                </div>
                                <div class="form-check form-check-inline rating-option">
                                    <input class="form-check-input" type="radio" name="rating" id="rating1" value="1" {{ old('rating', $testimonial->rating) == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating1">
                                        <i class="fa-solid fa-star text-warning"></i> 1
                                    </label>
                                </div>
                            </div>
                            <small class="form-hint">Select customer rating from 1 to 5 stars</small>
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
                                    <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" {{ old('status', $testimonial->status) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusActive">
                                        <span class="status-indicator active"></span>
                                        Active
                                    </label>
                                </div>
                                <div class="form-check form-check-inline status-option">
                                    <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0" {{ !old('status', $testimonial->status) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusInactive">
                                        <span class="status-indicator inactive"></span>
                                        Inactive
                                    </label>
                                </div>
                            </div>
                            <small class="form-hint">Active testimonials will be displayed on the website</small>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-align-left text-primary"></i> Message <span class="text-danger">*</span>
                            </label>
                            <textarea name="message" rows="5" class="form-control modern-input" placeholder="Write the customer testimonial message here..." required>{{ old('message', $testimonial->message) }}</textarea>
                            <small class="form-hint">Customer's feedback or review</small>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions mt-4 pt-4 border-top">
                    <button type="reset" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </button>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-xmark"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-check"></i> Update Testimonial
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
    // New image preview functionality
    document.getElementById('testimonialImage').addEventListener('change', function(e) {
        const preview = document.getElementById('newImagePreview');
        const img = preview.querySelector('img');

        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(this.files[0]);
        } else {
            preview.style.display = 'none';
        }
    });
</script>
@endsection
