@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Edit User</h2>
            <p class="page-subtitle">Update user information</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Users
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
            <h5><i class="fa-solid fa-user-pen"></i> User Information</h5>
            <span class="text-muted small">All fields marked with <span class="text-danger">*</span> are required</span>
        </div>

        <div class="card-body-custom">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="modern-form">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Name -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-user text-primary"></i> Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control modern-input" value="{{ old('name', $user->name) }}" placeholder="Enter full name" required>
                            <small class="form-hint">User's full name</small>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-envelope text-primary"></i> Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="email" class="form-control modern-input" value="{{ old('email', $user->email) }}" placeholder="Enter email address" required>
                            <small class="form-hint">Valid email address</small>
                        </div>
                    </div>

                    <!-- Mobile -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-phone text-primary"></i> Mobile
                            </label>
                            <input type="text" name="mobile" class="form-control modern-input" value="{{ old('mobile', optional($user->detail)->mobile) }}" placeholder="10 digit mobile number" maxlength="10">
                            <small class="form-hint">10 digit mobile number</small>
                        </div>
                    </div>

                    <!-- Gender -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-venus-mars text-primary"></i> Gender
                            </label>
                            <div class="gender-select-group">
                                <div class="form-check form-check-inline gender-option">
                                    <input class="form-check-input" type="radio" name="gender" id="genderMale" value="Male" {{ optional($user->detail)->gender == 'Male' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="genderMale">
                                        <i class="fa-solid fa-mars text-primary"></i> Male
                                    </label>
                                </div>
                                <div class="form-check form-check-inline gender-option">
                                    <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="Female" {{ optional($user->detail)->gender == 'Female' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="genderFemale">
                                        <i class="fa-solid fa-venus text-danger"></i> Female
                                    </label>
                                </div>
                                <div class="form-check form-check-inline gender-option">
                                    <input class="form-check-input" type="radio" name="gender" id="genderOther" value="Other" {{ optional($user->detail)->gender == 'Other' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="genderOther">
                                        <i class="fa-solid fa-genderless text-secondary"></i> Other
                                    </label>
                                </div>
                            </div>
                            <small class="form-hint">Select gender</small>
                        </div>
                    </div>

                    <!-- Change Image -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-image text-primary"></i> Change Image
                            </label>
                            <div class="image-upload-wrapper">
                                <input type="file" name="image" class="form-control modern-input" accept="image/*" id="userImage">
                                <small class="form-hint">Upload new profile image (JPG, PNG)</small>
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
                                @if(optional($user->detail)->image)
                                    <img src="{{ asset('uploads/users/'.optional($user->detail)->image) }}" alt="{{ $user->name }}" class="current-preview-img rounded-circle">
                                    <small class="form-hint d-block mt-1">Current profile image</small>
                                @else
                                    <div class="avatar-placeholder-lg">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <small class="form-hint d-block mt-1">No image uploaded</small>
                                @endif
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
                                    <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" {{ optional($user->detail)->status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusActive">
                                        <span class="status-indicator active"></span>
                                        Active
                                    </label>
                                </div>
                                <div class="form-check form-check-inline status-option">
                                    <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0" {{ !optional($user->detail)->status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusInactive">
                                        <span class="status-indicator inactive"></span>
                                        Inactive
                                    </label>
                                </div>
                            </div>
                            <small class="form-hint">Active users can login to the system</small>
                        </div>
                    </div>

                    <!-- New Password -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-lock text-primary"></i> New Password
                            </label>
                            <input type="password" name="password" class="form-control modern-input" placeholder="Enter new password">
                            <small class="form-hint">Leave blank to keep current password</small>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-lock text-primary"></i> Confirm Password
                            </label>
                            <input type="password" name="password_confirmation" class="form-control modern-input" placeholder="Confirm new password">
                            <small class="form-hint">Re-enter new password</small>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions mt-4 pt-4 border-top">
                    <button type="reset" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </button>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-xmark"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-check"></i> Update User
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
    document.getElementById('userImage').addEventListener('change', function(e) {
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
