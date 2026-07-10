@extends('layouts.admin')

@section('content')

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Room Types</h2>
            <p class="page-subtitle">Manage your hotel room categories</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.room-types.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Add Room Type
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
        <div class="col-md-4 col-6">
            <div class="mini-stat">
                <div class="mini-stat-icon bg-primary-soft">
                    <i class="fa-solid fa-layer-group text-primary"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $roomTypes->total() }}</h4>
                    <span>Total Types</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-6">
            <div class="mini-stat">
                <div class="mini-stat-icon bg-success-soft">
                    <i class="fa-solid fa-check-circle text-success"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $roomTypes->where('status', 1)->count() }}</h4>
                    <span>Active</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-6">
            <div class="mini-stat">
                <div class="mini-stat-icon bg-danger-soft">
                    <i class="fa-solid fa-circle-xmark text-danger"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $roomTypes->where('status', 0)->count() }}</h4>
                    <span>Inactive</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Types Table Card -->
    <div class="content-card">
        <div class="card-header-custom">
            <div class="d-flex align-items-center gap-2">
                <h5><i class="fa-solid fa-list"></i> All Room Types</h5>
                <span class="badge-count">{{ $roomTypes->total() }}</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table modern-table" id="roomTypesTable">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Room Type</th>
                        <th>Description</th>
                        <th width="100">Status</th>
                        <th width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($roomTypes as $roomType)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration + ($roomTypes->currentPage() - 1) * $roomTypes->perPage() }}</td>

                        <td>
                            <div class="room-type-info">
                                <h6 class="room-type-name">{{ $roomType->name }}</h6>
                            </div>
                        </td>

                        <td>
                            <span class="description-text">{{ Str::limit($roomType->description, 60) }}</span>
                        </td>

                        <td>
                            @if($roomType->status)
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
                                <a href="{{ route('admin.room-types.show', $roomType->id) }}"
                                   class="btn-action view"
                                   data-bs-toggle="tooltip"
                                   title="View Details">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.room-types.edit', $roomType->id) }}"
                                   class="btn-action edit"
                                   data-bs-toggle="tooltip"
                                   title="Edit Room Type">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('admin.room-types.destroy', $roomType->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this room type?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn-action delete"
                                            data-bs-toggle="tooltip"
                                            title="Delete Room Type">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fa-solid fa-layer-group"></i>
                                </div>
                                <h5>No Room Types Found</h5>
                                <p>Start by adding your first room type to the system.</p>
                                <a href="{{ route('admin.room-types.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-plus"></i> Add Room Type
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($roomTypes->hasPages())
            <div class="card-footer-custom">
                <div class="pagination-info">
                    Showing {{ $roomTypes->firstItem() }} to {{ $roomTypes->lastItem() }} of {{ $roomTypes->total() }} room types
                </div>
                <div class="pagination-wrapper">
                    {{ $roomTypes->links() }}
                </div>
            </div>
        @endif
    </div>

</div>

@endsection

@section('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endsection
