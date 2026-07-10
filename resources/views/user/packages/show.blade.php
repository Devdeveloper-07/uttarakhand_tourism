@extends('layouts.user')

@section('title','Package Details')

@section('content')

<div class="dashboard-container">

    <div class="page-header">

        <div>

            <h2 class="page-title">

                Package Details

            </h2>

            <p class="page-subtitle">

                View your selected package details.

            </p>

        </div>

        <div class="page-actions">

            <a href="{{ route('user.packages') }}"
               class="btn btn-outline-secondary">

                <i class="fa-solid fa-arrow-left"></i>

                Back

            </a>

        </div>

    </div>

    <div class="content-card">

        <div class="card-header-custom">

            <h5>

                <i class="fa-solid fa-suitcase"></i>

                {{ $package->package_name }}

            </h5>

        </div>

        <div class="card-body-custom">

            <div class="row">

                <div class="col-lg-5">

                    @if($package->featured_image)

                        <img src="{{ asset('uploads/packages/'.$package->featured_image) }}"
                             class="img-fluid rounded shadow"
                             style="width:100%;height:320px;object-fit:cover;">

                    @else

                        <img src="https://via.placeholder.com/600x400?text=Package"
                             class="img-fluid rounded shadow">

                    @endif

                </div>

                <div class="col-lg-7">

                    <table class="table table-bordered">

                        <tr>

                            <th width="220">

                                Package Name

                            </th>

                            <td>

                                {{ $package->package_name }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Duration

                            </th>

                            <td>

                                {{ $package->duration ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Price

                            </th>

                            <td>

                                ₹{{ number_format($package->price) }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Destination

                            </th>

                            <td>

                                {{ $package->destination ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Start Date

                            </th>

                            <td>

                                {{ $package->start_date ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                End Date

                            </th>

                            <td>

                                {{ $package->end_date ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Available Seats

                            </th>

                            <td>

                                {{ $package->available_seats ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Status

                            </th>

                            <td>

                                @if($package->status)

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

                                {{ $package->created_at->format('d M Y h:i A') }}

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

            <hr>

            <h5>

                Package Description

            </h5>

            <div class="border rounded p-4 bg-light">

                {!! nl2br(e($package->description ?? 'No Description Available')) !!}

            </div>

            @if(!empty($package->includes))

                <hr>

                <h5>

                    Package Includes

                </h5>

                <div class="border rounded p-4 bg-light">

                    {!! nl2br(e($package->includes)) !!}

                </div>

            @endif

            @if(!empty($package->excludes))

                <hr>

                <h5>

                    Package Excludes

                </h5>

                <div class="border rounded p-4 bg-light">

                    {!! nl2br(e($package->excludes)) !!}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
