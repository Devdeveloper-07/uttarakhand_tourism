@extends('layouts.user')

@section('title','My Hotels')

@section('content')

<div class="dashboard-container">

    <div class="page-header">

        <div>

            <h2 class="page-title">

                My Hotels

            </h2>

            <p class="page-subtitle">

                View all hotels available for your bookings.

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

                <i class="fa-solid fa-hotel"></i>

                Hotel List

            </h5>

        </div>

        <div class="card-body-custom">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>#</th>

                            <th>Image</th>

                            <th>Hotel</th>

                            <th>Location</th>

                            <th>Rooms</th>

                            <th>Status</th>

                            <th width="130">

                                Action

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($hotels as $hotel)

                        <tr>

                            <td>

                                {{ $loop->iteration }}

                            </td>

                            <td width="90">

                                @if($hotel->featured_image)

                                    <img src="{{ asset('uploads/hotels/'.$hotel->featured_image) }}"
                                         width="70"
                                         height="70"
                                         class="rounded shadow"
                                         style="object-fit:cover;">

                                @else

                                    <img src="https://via.placeholder.com/70"
                                         class="rounded shadow">

                                @endif

                            </td>

                            <td>

                                {{ $hotel->hotel_name }}

                            </td>

                            <td>

                                {{ $hotel->location }}

                            </td>

                            <td>

                                {{ $hotel->rooms_count ?? '-' }}

                            </td>

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

                            <td>

                                <a href="{{ route('user.hotels.show',$hotel->id) }}"
                                   class="btn btn-info btn-sm">

                                    <i class="fa-solid fa-eye"></i>

                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="7">

                                <div class="text-center py-5">

                                    <i class="fa-solid fa-hotel fa-3x text-secondary"></i>

                                    <h5 class="mt-3">

                                        No Hotels Found

                                    </h5>

                                </div>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-4">

                {{ $hotels->links() }}

            </div>

        </div>

    </div>

</div>

@endsection
