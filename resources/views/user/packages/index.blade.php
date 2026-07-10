@extends('layouts.user')

@section('title','My Packages')

@section('content')

<div class="dashboard-container">

    <div class="page-header">

        <div>

            <h2 class="page-title">

                My Packages

            </h2>

            <p class="page-subtitle">

                View your booked tour packages.

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

                <i class="fa-solid fa-suitcase"></i>

                Package List

            </h5>

        </div>

        <div class="card-body-custom">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>#</th>

                            <th>Image</th>

                            <th>Package</th>

                            <th>Duration</th>

                            <th>Price</th>

                            <th>Status</th>

                            <th width="120">

                                Action

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($packages as $package)

                        <tr>

                            <td>

                                {{ $loop->iteration }}

                            </td>

                            <td width="90">

                                @if($package->featured_image)

                                    <img src="{{ asset('uploads/packages/'.$package->featured_image) }}"
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

                                {{ $package->package_name }}

                            </td>

                            <td>

                                {{ $package->duration ?? '-' }}

                            </td>

                            <td>

                                ₹{{ number_format($package->price) }}

                            </td>

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

                            <td>

                                <a href="{{ route('user.packages.show',$package->id) }}"
                                   class="btn btn-info btn-sm">

                                    <i class="fa-solid fa-eye"></i>

                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="7">

                                <div class="text-center py-5">

                                    <i class="fa-solid fa-box-open fa-3x text-secondary"></i>

                                    <h5 class="mt-3">

                                        No Packages Found

                                    </h5>

                                </div>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-4">

                {{ $packages->links() }}

            </div>

        </div>

    </div>

</div>

@endsection
