@extends('layouts.admin')

@section('title', 'Room Management')

@section('content')

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Room Management</h2>
            <p class="page-subtitle">Manage all rooms across Uttarakhand Travels hotels</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Add New Room
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
                    <i class="fa-solid fa-bed text-primary"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $rooms->total() }}</h4>
                    <span>Total Rooms</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="mini-stat">
                <div class="mini-stat-icon bg-success-soft">
                    <i class="fa-solid fa-check-circle text-success"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $rooms->where('status', 1)->count() }}</h4>
                    <span>Available</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="mini-stat">
                <div class="mini-stat-icon bg-danger-soft">
                    <i class="fa-solid fa-circle-xmark text-danger"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $rooms->where('status', 0)->count() }}</h4>
                    <span>Unavailable</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="mini-stat">
                <div class="mini-stat-icon bg-info-soft">
                    <i class="fa-solid fa-hotel text-info"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $rooms->groupBy('hotel_id')->count() }}</h4>
                    <span>Hotels</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Rooms Table Card -->
    <div class="content-card">
        <div class="card-header-custom">
            <div class="d-flex align-items-center gap-2">
                <h5><i class="fa-solid fa-list"></i> All Rooms</h5>
                <span class="badge-count">{{ $rooms->total() }}</span>
            </div>
            <div class="header-actions">
                <div class="search-box">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" placeholder="Search rooms..." id="roomSearch">
                </div>
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table modern-table" id="roomsTable">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Hotel</th>
                        <th>Room Details</th>
                        <th>Room Type</th>
                        <th width="90">Capacity</th>
                        <th width="120">Price/Night</th>
                        <th width="100">Status</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($rooms as $room)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration + ($rooms->currentPage() - 1) * $rooms->perPage() }}</td>

                        <td>
                            <div class="hotel-info">
                                <h6 class="hotel-name">{{ $room->hotel->hotel_name }}</h6>
                                <span class="hotel-city"><i class="fa-solid fa-location-dot"></i> {{ $room->hotel->city }}</span>
                            </div>
                        </td>

                        <td>
                            <div class="room-info">
                                <h6 class="room-name">{{ $room->room_name }}</h6>
                                <span class="room-number">Room No. {{ $room->room_number ?? 'N/A' }}</span>
                            </div>
                        </td>

                        <td>
                            <span class="room-type-badge">{{ $room->roomType->name }}</span>
                        </td>

                        <td>
                            <div class="capacity-info">
                                <i class="fa-solid fa-user-group text-primary"></i>
                                <span>{{ $room->capacity }} <small>Guests</small></span>
                            </div>
                        </td>

                        <td>
                            <div class="price-tag">
                                <span class="currency">₹</span>
                                <span class="amount">{{ number_format($room->price_per_night) }}</span>
                            </div>
                        </td>

                        <td>
                            @if($room->status)
                                <span class="status-badge active">
                                    <span class="status-dot"></span> Available
                                </span>
                            @else
                                <span class="status-badge inactive">
                                    <span class="status-dot"></span> Unavailable
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="action-btns">
                                <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                   class="btn-action edit"
                                   data-bs-toggle="tooltip"
                                   title="Edit Room">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <a href="{{ route('admin.rooms.show', $room->id) }}"
                                   class="btn-action view"
                                   data-bs-toggle="tooltip"
                                   title="View Details">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <form action="{{ route('admin.rooms.destroy', $room->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this room?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn-action delete"
                                            data-bs-toggle="tooltip"
                                            title="Delete Room">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fa-solid fa-bed"></i>
                                </div>
                                <h5>No Rooms Found</h5>
                                <p>Start by adding your first room to the system.</p>
                                <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-plus"></i> Add Room
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($rooms->hasPages())
            <div class="card-footer-custom">
                <div class="pagination-info">
                    Showing {{ $rooms->firstItem() }} to {{ $rooms->lastItem() }} of {{ $rooms->total() }} rooms
                </div>
                <div class="pagination-wrapper">
                    {{ $rooms->links() }}
                </div>
            </div>
        @endif
    </div>

</div>

@endsection

@section('scripts')
<script>
    // Search functionality
    document.getElementById('roomSearch').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#roomsTable tbody tr');

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
