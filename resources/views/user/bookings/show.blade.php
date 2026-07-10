@extends('layouts.user')

@section('title','Booking Details')

@section('content')

<div class="dashboard-container">

    <div class="page-header">

        <div>

            <h2 class="page-title">

                Booking Details

            </h2>

            <p class="page-subtitle">

                View your booking information

            </p>

        </div>

        <div class="page-actions">

            <a href="{{ route('user.bookings') }}"
               class="btn btn-outline-secondary">

                <i class="fa-solid fa-arrow-left"></i>

                Back

            </a>

        </div>

    </div>

    <div class="content-card">

        <div class="card-header-custom">

            <h5>

                <i class="fa-solid fa-calendar-check"></i>

                Booking Information

            </h5>

        </div>

        <div class="card-body-custom">

            <div class="row">

                <div class="col-md-4 text-center">

                    @if(isset($booking->hotel->featured_image))

                        <img src="{{ asset('uploads/hotels/'.$booking->hotel->featured_image) }}"
                             class="img-fluid rounded shadow"
                             style="height:220px;width:100%;object-fit:cover;">

                    @else

                        <img src="https://via.placeholder.com/350x220?text=Hotel"
                             class="img-fluid rounded shadow">

                    @endif

                </div>

                <div class="col-md-8">

                    <table class="table table-bordered">

                        <tr>

                            <th width="220">

                                Booking ID

                            </th>

                            <td>

                                #BK-{{ $booking->id }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Hotel Name

                            </th>

                            <td>

                                {{ $booking->hotel->hotel_name ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Check In

                            </th>

                            <td>

                                {{ $booking->check_in ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Check Out

                            </th>

                            <td>

                                {{ $booking->check_out ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Guests

                            </th>

                            <td>

                                {{ $booking->guests ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Total Amount

                            </th>

                            <td>

                                ₹{{ number_format($booking->total_amount ?? 0) }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Status

                            </th>

                            <td>

                                @php
                                    $status = strtolower($booking->status ?? 'pending');
                                @endphp

                                @if($status=='confirmed')

                                    <span class="badge bg-success">

                                        Confirmed

                                    </span>

                                @elseif($status=='cancelled')

                                    <span class="badge bg-danger">

                                        Cancelled

                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">

                                        Pending

                                    </span>

                                @endif

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Booking Date

                            </th>

                            <td>

                                {{ $booking->created_at->format('d M Y h:i A') }}

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

            @if(!empty($booking->special_request))

            <hr>

            <h5>

                Special Request

            </h5>

            <div class="alert alert-light border">

                {{ $booking->special_request }}

            </div>

            @endif

        </div>

    </div>

</div>

@endsection
