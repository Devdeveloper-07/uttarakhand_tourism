@extends('layouts.admin')

@section('title', 'Packages')

@section('content')

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Packages</h2>
            <p class="page-subtitle">Manage Tour Packages</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.packages.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Add Package
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
                    <i class="fa-solid fa-suitcase text-primary"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $packages->total() }}</h4>
                    <span>Total Packages</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="mini-stat">
                <div class="mini-stat-icon bg-success-soft">
                    <i class="fa-solid fa-check-circle text-success"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $packages->where('status', 1)->count() }}</h4>
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
                    <h4>{{ $packages->where('status', 0)->count() }}</h4>
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
                    <h4>₹{{ number_format($packages->avg('price') ?? 0) }}</h4>
                    <span>Avg Price</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Packages Table Card -->
    <div class="content-card">
        <div class="card-header-custom">
            <div class="d-flex align-items-center gap-2">
                <h5><i class="fa-solid fa-list"></i> All Packages</h5>
                <span class="badge-count">{{ $packages->total() }}</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table modern-table" id="packagesTable">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th width="90">Image</th>
                        <th>Package</th>
                        <th>Destination</th>
                        <th width="100">Duration</th>
                        <th width="120">Price</th>
                        <th width="100">Status</th>
                        <th width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($packages as $package)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration + ($packages->currentPage() - 1) * $packages->perPage() }}</td>

                        <td>
                            <div class="hotel-thumb">
                                @if($package->thumbnail)
                                    <img src="{{ asset('uploads/packages/'.$package->thumbnail) }}" alt="{{ $package->package_name }}">
                                @else
                                    <div class="thumb-placeholder">
                                        <i class="fa-solid fa-suitcase"></i>
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td>
                            <div class="hotel-info">
                                <h6 class="hotel-name">{{ $package->package_name }}</h6>
                            </div>
                        </td>

                        <td>
                            <span class="location-text"><i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $package->destination }}</span>
                        </td>

                        <td>
                            <div class="capacity-info">
                                <i class="fa-solid fa-clock text-primary"></i>
                                <div>
                                    {{ $package->duration }} <small>Days</small>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="price-tag">
                                <span class="currency">₹</span>
                                <span class="amount">{{ number_format($package->price) }}</span>
                            </div>
                        </td>

                        <td>
                            @if($package->status)
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
                                <a href="{{ route('admin.packages.show', $package->id) }}"
                                   class="btn-action view"
                                   data-bs-toggle="tooltip"
                                   title="View Details">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.packages.edit', $package->id) }}"
                                   class="btn-action edit"
                                   data-bs-toggle="tooltip"
                                   title="Edit Package">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('admin.packages.destroy', $package->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this package?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn-action delete"
                                            data-bs-toggle="tooltip"
                                            title="Delete Package">
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
                                    <i class="fa-solid fa-suitcase"></i>
                                </div>
                                <h5>No Packages Found</h5>
                                <p>Start by adding your first tour package.</p>
                                <a href="{{ route('admin.packages.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-plus"></i> Add Package
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($packages->hasPages())
            <div class="card-footer-custom">
                <div class="pagination-info">
                    Showing {{ $packages->firstItem() }} to {{ $packages->lastItem() }} of {{ $packages->total() }} packages
                </div>
                <div class="pagination-wrapper">
                    {{ $packages->links() }}
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
