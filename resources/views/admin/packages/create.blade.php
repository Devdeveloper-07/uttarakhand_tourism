@extends('layouts.admin')

@section('title','Add Package')

@section('content')

<div class="dashboard-container">

    <div class="page-header">

        <div>
            <h2 class="page-title">Add Package</h2>
            <p class="page-subtitle">Create New Tour Package</p>
        </div>

        <div class="page-actions">

            <a href="{{ route('admin.packages.index') }}"
                class="btn btn-outline-secondary">

                <i class="fa-solid fa-arrow-left"></i>

                Back

            </a>

        </div>

    </div>

    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <div class="content-card">

        <div class="card-header-custom">

            <h5>

                <i class="fa-solid fa-suitcase"></i>

                Package Information

            </h5>

        </div>

        <div class="card-body-custom">

            <form action="{{ route('admin.packages.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label>Package Name</label>

                        <input
                            type="text"
                            name="package_name"
                            class="form-control"
                            value="{{ old('package_name') }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Destination</label>

                        <input
                            type="text"
                            name="destination"
                            class="form-control"
                            value="{{ old('destination') }}"
                            required>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Duration (Days)</label>

                        <input
                            type="number"
                            name="duration"
                            class="form-control"
                            value="{{ old('duration') }}"
                            required>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Price</label>

                        <input
                            type="number"
                            step="0.01"
                            name="price"
                            class="form-control"
                            value="{{ old('price') }}"
                            required>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Discount Price</label>

                        <input
                            type="number"
                            step="0.01"
                            name="discount_price"
                            class="form-control"
                            value="{{ old('discount_price') }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Maximum Persons</label>

                        <input
                            type="number"
                            name="max_person"
                            class="form-control"
                            value="{{ old('max_person') }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Package Image</label>

                        <input
                            type="file"
                            name="thumbnail"
                            class="form-control">

                    </div>

                    <div class="col-md-12 mb-3">

                        <label>Description</label>

                        <textarea
                            name="description"
                            rows="4"
                            class="form-control"
                            required>{{ old('description') }}</textarea>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Includes</label>

                        <textarea
                            name="includes"
                            rows="5"
                            class="form-control">{{ old('includes') }}</textarea>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Excludes</label>

                        <textarea
                            name="excludes"
                            rows="5"
                            class="form-control">{{ old('excludes') }}</textarea>

                    </div>

                    <div class="col-md-12 mb-3">

                        <label>Itinerary</label>

                        <textarea
                            name="itinerary"
                            rows="6"
                            class="form-control">{{ old('itinerary') }}</textarea>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Featured Package</label>

                        <select
                            name="featured"
                            class="form-select">

                            <option value="1">

                                Yes

                            </option>

                            <option value="0" selected>

                                No

                            </option>

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Status</label>

                        <select
                            name="status"
                            class="form-select">

                            <option value="1">

                                Active

                            </option>

                            <option value="0">

                                Inactive

                            </option>

                        </select>

                    </div>

                </div>

                <div class="mt-4">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="fa-solid fa-floppy-disk"></i>

                        Save Package

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
