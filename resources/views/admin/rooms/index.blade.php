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
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                    <h4>{{ $inventory['total'] }}</h4>
                    <span>Total Inventory</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="mini-stat">
                <div class="mini-stat-icon bg-success-soft">
                    <i class="fa-solid fa-check-circle text-success"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $inventory['available'] }}</h4>
                    <span>Available for Dates</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="mini-stat">
                <div class="mini-stat-icon bg-danger-soft">
                    <i class="fa-solid fa-circle-xmark text-danger"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $inventory['occupied'] }}</h4>
                    <span>Booked</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="mini-stat">
                <div class="mini-stat-icon bg-info-soft">
                    <i class="fa-solid fa-hotel text-info"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $inventory['reserved'] }}</h4>
                    <span>Temporary Holds</span>
                </div>
            </div>
        </div>
    </div>

    <div class="content-card mb-4">
        <div class="card-body-custom">
            <form method="GET" action="{{ route('admin.rooms.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Hotel</label>
                    <select name="hotel_id" class="form-select">
                        <option value="">All hotels</option>
                        @foreach ($hotels as $hotel)
                            <option value="{{ $hotel->id }}" @selected((string) $hotelFilter === (string) $hotel->id)>
                                {{ $hotel->hotel_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Check-in</label>
                    <input type="date" name="check_in" class="form-control" value="{{ $inventory['check_in'] }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Check-out</label>
                    <input type="date" name="check_out" class="form-control" value="{{ $inventory['check_out'] }}" required>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit"><i class="fa-solid fa-filter me-1"></i> Apply</button>
                </div>
            </form>
            <p class="text-muted small mt-3 mb-0">
                Hotels: {{ $inventory['hotels'] }} · Room types: {{ $inventory['room_types'] }} ·
                Occupancy: {{ $inventory['occupancy'] }}% · Availability is the minimum inventory across
                {{ $inventory['check_in'] }} to (but not including) {{ $inventory['check_out'] }}.
            </p>
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
                        <th>Total</th>
                        <th>Occupied</th>
                        <th>Available</th>
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

                        @php($roomCounts = $inventory['availability'][$room->id] ?? ['total' => $room->total_rooms, 'booked' => 0, 'reserved' => 0, 'available' => $room->total_rooms, 'daily' => []])
                        <td>{{ $roomCounts['total'] }}</td>
                        <td>
                            {{ $roomCounts['booked'] }}
                            @if ($roomCounts['reserved'] > 0)
                                <small class="d-block text-warning">{{ $roomCounts['reserved'] }} reserved</small>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $roomCounts['available'] }}</strong>
                            <details class="small">
                                <summary>By night</summary>
                                @foreach ($roomCounts['daily'] as $night)
                                    <div>{{ \Illuminate\Support\Carbon::parse($night['date'])->format('d M') }}: {{ $night['available'] }}</div>
                                @endforeach
                            </details>
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
                        <td colspan="11">
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
