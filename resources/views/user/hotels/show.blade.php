@extends('layouts.user')

@section('title','Hotel Details')

@section('content')

<div class="dashboard-container">

    <div class="page-header">

        <div>

            <h2 class="page-title">

                Hotel Details

            </h2>

            <p class="page-subtitle">

                View Hotel Information

            </p>

        </div>

        <div class="page-actions">

            <a href="{{ route('user.hotels') }}"
               class="btn btn-outline-secondary">

                <i class="fa-solid fa-arrow-left"></i>

                Back

            </a>

        </div>

    </div>

    <div class="content-card">

        <div class="card-header-custom">

            <h5>

                <i class="fa-solid fa-hotel"></i>

                {{ $hotel->hotel_name }}

            </h5>

        </div>

        <div class="card-body-custom">

            <div class="row">

                <div class="col-lg-5">

                    @if($hotel->featured_image)

                        <img src="{{ asset('uploads/hotels/'.$hotel->featured_image) }}"
                             class="img-fluid rounded shadow"
                             style="width:100%;height:320px;object-fit:cover;">

                    @else

                        <img src="https://via.placeholder.com/600x400"
                             class="img-fluid rounded shadow">

                    @endif

                </div>

                <div class="col-lg-7">

                    <table class="table table-bordered">

                        <tr>

                            <th width="220">

                                Hotel Name

                            </th>

                            <td>

                                {{ $hotel->hotel_name }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Location

                            </th>

                            <td>

                                {{ $hotel->location }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                City

                            </th>

                            <td>

                                {{ $hotel->city ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                State

                            </th>

                            <td>

                                {{ $hotel->state ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Country

                            </th>

                            <td>

                                {{ $hotel->country ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Pincode

                            </th>

                            <td>

                                {{ $hotel->pincode ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Contact Number

                            </th>

                            <td>

                                {{ $hotel->mobile ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Email

                            </th>

                            <td>

                                {{ $hotel->email ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Price Per Night

                            </th>

                            <td>

                                ₹{{ number_format($hotel->price_per_night ?? 0) }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Status

                            </th>

                            <td>

                                @if($hotel->status)

                                    <span class="badge bg-success">

                                        Active

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        Inactive

                                    </span>

                                @endif

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Created At

                            </th>

                            <td>

                                {{ $hotel->created_at->format('d M Y h:i A') }}

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

            <hr>

            <h5>

                Description

            </h5>

            <div class="border rounded p-4 bg-light">

                {!! nl2br(e($hotel->description ?? 'No Description Available')) !!}

            </div>

        </div>

    </div>

</div>

@endsection
