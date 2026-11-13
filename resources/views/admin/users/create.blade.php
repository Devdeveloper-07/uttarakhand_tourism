@extends('layouts.admin')

@section('title', 'Add User')

@section('content')
<div class="dashboard-container">
    <div class="page-header">
        <div>
            <h2 class="page-title">Add User</h2>
            <p class="page-subtitle">Create a customer account</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Users
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-modern alert-danger" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="content-card">
        <div class="card-header-custom"><h5><i class="fa-solid fa-user-plus"></i> User Information</h5></div>
        <div class="card-body-custom">
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="modern-form">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label" for="userName">Full name <span class="text-danger">*</span></label>
                        <input id="userName" class="form-control modern-input" name="name" value="{{ old('name') }}" maxlength="255" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="userEmail">Email address <span class="text-danger">*</span></label>
                        <input id="userEmail" type="email" class="form-control modern-input" name="email" value="{{ old('email') }}" maxlength="255" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="userMobile">Mobile number</label>
                        <input id="userMobile" class="form-control modern-input" name="mobile" value="{{ old('mobile') }}" inputmode="numeric" maxlength="10">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="userGender">Gender</label>
                        <select id="userGender" class="form-select modern-input" name="gender">
                            <option value="">Prefer not to say</option>
                            @foreach (['Male', 'Female', 'Other'] as $gender)
                                <option value="{{ $gender }}" @selected(old('gender') === $gender)>{{ $gender }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="userPassword">Password <span class="text-danger">*</span></label>
                        <input id="userPassword" type="password" class="form-control modern-input" name="password" required autocomplete="new-password">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="userPasswordConfirmation">Confirm password <span class="text-danger">*</span></label>
                        <input id="userPasswordConfirmation" type="password" class="form-control modern-input" name="password_confirmation" required autocomplete="new-password">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="userImage">Profile image</label>
                        <input id="userImage" type="file" class="form-control modern-input" name="image" accept="image/jpeg,image/png,image/webp">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="userStatus">Account status <span class="text-danger">*</span></label>
                        <select id="userStatus" class="form-select modern-input" name="status" required>
                            <option value="1" @selected(old('status', '1') === '1')>Active</option>
                            <option value="0" @selected(old('status') === '0')>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="form-actions mt-4 pt-4 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check me-1"></i> Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
