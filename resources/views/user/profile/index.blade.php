@extends('layouts.user')

@section('title','My Profile')

@section('content')

<div class="dashboard-container">

    <div class="page-header">
        <div>
            <h2 class="page-title">My Profile</h2>
            <p class="page-subtitle">
                Manage your account information
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <div class="content-card">

        <div class="card-header-custom">

            <h5>

                <i class="fa-solid fa-user"></i>

                Update Profile

            </h5>

        </div>

        <div class="card-body-custom">

            <form action="{{ route('user.profile.update') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="row">

                    <div class="col-lg-4 text-center mb-4">

                        @if($detail->image)

                            <img src="{{ asset('uploads/users/'.$detail->image) }}"
                                 class="rounded-circle shadow mb-3"
                                 width="180"
                                 height="180"
                                 style="object-fit:cover;">

                        @else

                            <img src="https://via.placeholder.com/180"
                                 class="rounded-circle shadow mb-3">

                        @endif

                        <input type="file"
                               name="image"
                               class="form-control">

                    </div>

                    <div class="col-lg-8">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Full Name

                                </label>

                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       value="{{ old('name',$user->name) }}">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Email

                                </label>

                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       value="{{ old('email',$user->email) }}">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Mobile

                                </label>

                                <input type="text"
                                       name="mobile"
                                       class="form-control"
                                       value="{{ old('mobile',$detail->mobile) }}">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Gender

                                </label>

                                <select name="gender"
                                        class="form-select">

                                    <option value="">Select</option>

                                    <option value="Male"
                                        {{ $detail->gender=='Male' ? 'selected' : '' }}>

                                        Male

                                    </option>

                                    <option value="Female"
                                        {{ $detail->gender=='Female' ? 'selected' : '' }}>

                                        Female

                                    </option>

                                    <option value="Other"
                                        {{ $detail->gender=='Other' ? 'selected' : '' }}>

                                        Other

                                    </option>

                                </select>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Date of Birth

                                </label>

                                <input type="date"
                                       name="dob"
                                       class="form-control"
                                       value="{{ old('dob',$detail->dob) }}">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Pincode

                                </label>

                                <input type="text"
                                       name="pincode"
                                       class="form-control"
                                       value="{{ old('pincode',$detail->pincode) }}">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    City

                                </label>

                                <input type="text"
                                       name="city"
                                       class="form-control"
                                       value="{{ old('city',$detail->city) }}">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    State

                                </label>

                                <input type="text"
                                       name="state"
                                       class="form-control"
                                       value="{{ old('state',$detail->state) }}">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Country

                                </label>

                                <input type="text"
                                       name="country"
                                       class="form-control"
                                       value="{{ old('country',$detail->country) }}">

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">

                                    Address

                                </label>

                                <textarea name="address"
                                          rows="4"
                                          class="form-control">{{ old('address',$detail->address) }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="text-end">

                    <button class="btn btn-primary">

                        <i class="fa-solid fa-floppy-disk"></i>

                        Update Profile

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
