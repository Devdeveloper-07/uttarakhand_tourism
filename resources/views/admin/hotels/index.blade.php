@extends('layouts.admin')

@section('title', 'Hotel Management')

@section('content')

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Hotel Management</h2>
            <p class="page-subtitle">Manage all hotels in Uttarakhand Travels</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Add New Hotel
            </a>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-modern alert-success alert-dismissible fade show" role="alert">
            <div class="alert-icon"><i class="fa-solid fa-circle-check"></i></div>
            <div class="alert-content">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Stats Bar -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="mini-stat">
                <div class="mini-stat-icon bg-primary-soft">
                    <i class="fa-solid fa-hotel text-primary"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $hotels->total() }}</h4>
                    <span>Total Hotels</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="mini-stat">
                <div class="mini-stat-icon bg-success-soft">
                    <i class="fa-solid fa-check-circle text-success"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $hotels->where('status', 1)->count() }}</h4>
                    <span>Active</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="mini-stat">
                <div class="mini-stat-icon bg-danger-soft">
                    <i class="fa-solid fa-circle-xmark text-danger"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $hotels->where('status', 0)->count() }}</h4>
                    <span>Inactive</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="mini-stat">
                <div class="mini-stat-icon bg-warning-soft">
                    <i class="fa-solid fa-indian-rupee-sign text-warning"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>₹{{ number_format($hotels->avg('starting_price') ?? 0) }}</h4>
                    <span>Avg Price</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Hotels Table Card -->
    <div class="content-card">
        <div class="card-header-custom">
            <div class="d-flex align-items-center gap-2">
                <h5><i class="fa-solid fa-list"></i> All Hotels</h5>
                <span class="badge-count">{{ $hotels->total() }}</span>
            </div>
            <div class="header-actions">
                <div class="search-box">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" placeholder="Search hotels..." id="hotelSearch">
                </div>
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table modern-table" id="hotelsTable">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th width="100">Image</th>
                        <th>Hotel Details</th>
                        <th>Location</th>
                        <th width="120">Price/Night</th>
                        <th width="100">Status</th>
                        <th width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($hotels as $hotel)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration + ($hotels->currentPage() - 1) * $hotels->perPage() }}</td>

                        <td>
                            <div class="hotel-thumb">
                                @if($hotel->thumbnail)
                                    <img src="{{ asset('uploads/hotels/'.$hotel->thumbnail) }}" alt="{{ $hotel->hotel_name }}">
                                @else
                                    <div class="thumb-placeholder">
                                        <i class="fa-solid fa-hotel"></i>
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td>
                            <div class="hotel-info">
                                <h6 class="hotel-name">{{ $hotel->hotel_name }}</h6>
                                <span class="hotel-city"><i class="fa-solid fa-location-dot"></i> {{ $hotel->city }}</span>
                            </div>
                        </td>

                        <td>
                            <span class="location-text">{{ Str::limit($hotel->location, 40) }}</span>
                        </td>

                        <td>
                            <div class="price-tag">
                                <span class="currency">₹</span>
                                <span class="amount">{{ number_format($hotel->starting_price) }}</span>
                            </div>
                        </td>

                        <td>
                            @if($hotel->status)
                                <span class="status-badge active">
                                    <span class="status-dot"></span> Active
                                </span>
                            @else
                                <span class="status-badge inactive">
                                    <span class="status-dot"></span> Inactive
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="action-btns">
                                <a href="{{ route('admin.hotels.edit', $hotel->id) }}"
                                   class="btn-action edit"
                                   data-bs-toggle="tooltip"
                                   title="Edit Hotel">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <a href="{{ route('admin.hotels.show', $hotel->id) }}"
                                   class="btn-action view"
                                   data-bs-toggle="tooltip"
                                   title="View Details">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <form action="{{ route('admin.hotels.destroy', $hotel->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this hotel?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn-action delete"
                                            data-bs-toggle="tooltip"
                                            title="Delete Hotel">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fa-solid fa-hotel"></i>
                                </div>
                                <h5>No Hotels Found</h5>
                                <p>Start by adding your first hotel to the system.</p>
                                <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-plus"></i> Add Hotel
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($hotels->hasPages())
            <div class="card-footer-custom">
                <div class="pagination-info">
                    Showing {{ $hotels->firstItem() }} to {{ $hotels->lastItem() }} of {{ $hotels->total() }} hotels
                </div>
                <div class="pagination-wrapper">
                    {{ $hotels->links() }}
                </div>
            </div>
        @endif
    </div>

</div>

@endsection

@section('scripts')
<script>
    // Search functionality
    document.getElementById('hotelSearch').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#hotelsTable tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endsection
