@extends('layouts.user')

@section('title','My Bookings')

@section('content')

<div class="dashboard-container">

    <div class="page-header">

        <div>

            <h2 class="page-title">

                My Bookings

            </h2>

            <p class="page-subtitle">

                View and manage your hotel and package bookings.

            </p>

        </div>

    </div>

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <div class="content-card">

        <div class="card-header-custom">

            <h5>

                <i class="fa-solid fa-calendar-check"></i>

                Booking List

            </h5>

        </div>

        <div class="card-body-custom">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>#</th>

                            <th>Booking ID</th>

                            <th>Trip</th>

                            <th>Check In</th>

                            <th>Check Out</th>

                            <th>Guests</th>

                            <th>Status</th>

                            <th width="130">

                                Action

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($bookings as $booking)

                        <tr>

                            <td>

                                {{ $loop->iteration }}

                            </td>

                            <td>

                                {{ $booking->booking_no ?? '#BK-'.$booking->id }}

                            </td>

                            <td>

                                {{ $booking->item?->hotel_name ?? $booking->item?->package_name ?? 'N/A' }}

                            </td>

                            <td>

                                {{ $booking->check_in?->format('d M Y') ?? $booking->travel_date?->format('d M Y') ?? '-' }}

                            </td>

                            <td>

                                {{ $booking->check_out?->format('d M Y') ?? '-' }}

                            </td>

                            <td>

                                {{ $booking->guest ?? '-' }}

                            </td>

                            <td>

                                @php

                                    $status = strtolower($booking->booking_status ?? 'pending');

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

                            <td>

                                <a href="{{ route('user.bookings.show',$booking->id) }}"
                                   class="btn btn-info btn-sm">

                                    <i class="fa-solid fa-eye"></i>

                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="8">

                                <div class="text-center py-5">

                                    <i class="fa-solid fa-calendar-xmark fa-3x text-secondary"></i>

                                    <h5 class="mt-3">

                                        No Bookings Found

                                    </h5>

                                </div>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-4">

                {{ $bookings->links() }}

            </div>

        </div>

    </div>

</div>

@endsection
