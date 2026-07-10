@extends('layouts.admin')

@section('title', 'Edit Gallery Image')

@section('content')

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Edit Gallery Image</h2>
            <p class="page-subtitle">Update Gallery Information</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Gallery
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
            <h5><i class="fa-solid fa-pen-to-square"></i> Gallery Information</h5>
            <span class="text-muted small">All fields marked with <span class="text-danger">*</span> are required</span>
        </div>

        <div class="card-body-custom">
            <form action="{{ route('admin.galleries.update', $gallery->id) }}" method="POST"
                enctype="multipart/form-data" class="modern-form">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Title -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-signature text-primary"></i> Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" class="form-control modern-input"
                                value="{{ old('title', $gallery->title) }}" placeholder="Enter image title" required>
                            <small class="form-hint">e.g. Sunset at Nainital Lake</small>
                        </div>
                    </div>

                    <!-- Gallery Type -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-layer-group text-primary"></i> Gallery Type <span class="text-danger">*</span>
                            </label>
                            <select name="type" class="form-select modern-input" required>
                                <option value="hotel" {{ $gallery->type == 'hotel' ? 'selected' : '' }}>Hotel</option>
                                <option value="package" {{ $gallery->type == 'package' ? 'selected' : '' }}>Package</option>
                                <option value="tourism" {{ $gallery->type == 'tourism' ? 'selected' : '' }}>Tourism</option>
                            </select>
                            <small class="form-hint">Category for this image</small>
                        </div>
                    </div>

                    <!-- Change Images -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-image text-primary"></i> Change Images
                            </label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="image[]" class="form-control modern-input file-input"
                                    id="galleryImage" accept="image/*" multiple>
                                @php
                                    $galleryImages = $gallery->image;
                                @endphp
                                <div class="file-upload-preview" id="imagePreview">
                                    @if (!empty($galleryImages))
                                        <div class="existing-gallery-images d-flex flex-wrap gap-2">
                                            @foreach ($galleryImages as $img)
                                                <div class="gallery-thumb" style="width: 120px; height: 90px; overflow: hidden; border-radius: 8px;">
                                                    <img src="{{ asset('uploads/gallery/' . $img) }}"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                        <span>Click to upload or drag & drop</span>
                                        <small>PNG, JPG up to 5MB</small>
                                    @endif
                                </div>
                            </div>
                            <small class="form-hint">Leave empty to keep current images, or upload new images to replace them.</small>
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
                                    <input class="form-check-input" type="radio" name="status" id="statusActive"
                                        value="1" {{ $gallery->status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusActive">
                                        <span class="status-indicator active"></span>
                                        Active
                                    </label>
                                </div>
                                <div class="form-check form-check-inline status-option">
                                    <input class="form-check-input" type="radio" name="status" id="statusInactive"
                                        value="0" {{ !$gallery->status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusInactive">
                                        <span class="status-indicator inactive"></span>
                                        Inactive
                                    </label>
                                </div>
                            </div>
                            <small class="form-hint">Active images are visible in gallery</small>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-align-left text-primary"></i> Description
                            </label>
                            <textarea name="description" rows="5" class="form-control modern-input"
                                placeholder="Describe the image, location, or any relevant details...">{{ old('description', $gallery->description) }}</textarea>
                            <small class="form-hint">Optional - Add details about this image</small>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions mt-4 pt-4 border-top">
                    <button type="reset" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </button>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-xmark"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-check"></i> Update Gallery
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
        function renderImagePreviews(files, previewElement) {
            const fileArray = Array.from(files);
            if (!fileArray.length) {
                return;
            }

            previewElement.innerHTML = fileArray.map(file => {
                const url = URL.createObjectURL(file);
                return '<div class="preview-thumb" style="width: 120px; height: 90px; overflow: hidden; border-radius: 8px; margin-right: 10px; margin-bottom: 10px;"><img src="' + url + '" style="width: 100%; height: 100%; object-fit: cover;"></div>';
            }).join('');
            previewElement.classList.add('has-preview');
        }

        const galleryInput = document.getElementById('galleryImage');
        const imagePreview = document.getElementById('imagePreview');

        galleryInput.addEventListener('change', function(e) {
            renderImagePreviews(e.target.files, imagePreview);
        });

        imagePreview.addEventListener('dragover', (e) => {
            e.preventDefault();
            imagePreview.classList.add('dragover');
        });

        imagePreview.addEventListener('dragleave', () => {
            imagePreview.classList.remove('dragover');
        });

        imagePreview.addEventListener('drop', (e) => {
            e.preventDefault();
            imagePreview.classList.remove('dragover');

            const files = e.dataTransfer.files;
            if (files.length && files[0].type.startsWith('image/')) {
                galleryInput.files = files;
                renderImagePreviews(files, imagePreview);
            }
        });
    </script>
@endsection
