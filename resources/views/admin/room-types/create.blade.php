@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>Add Room Type</h2>

        <a href="{{ route('admin.room-types.index') }}"
            class="btn btn-secondary">

            <i class="fa fa-arrow-left"></i>

            Back

        </a>

    </div>

    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <div class="card shadow">

        <div class="card-body">

            <form
                action="{{ route('admin.room-types.store') }}"
                method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label">

                        Room Type Name

                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name') }}"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Description

                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        class="form-control">{{ old('description') }}</textarea>

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Status

                    </label>

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

                <button class="btn btn-success">

                    <i class="fa fa-save"></i>

                    Save Room Type

                </button>

            </form>

        </div>

    </div>

</div>

@endsection
