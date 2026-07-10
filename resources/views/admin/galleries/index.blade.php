@extends('layouts.admin')

@section('title', 'Gallery')

@section('content')

    <div class="dashboard-container">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="page-title">Gallery</h2>
                <p class="page-subtitle">Manage Gallery Images</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus"></i> Add Image
                </a>
            </div>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
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
                        <i class="fa-solid fa-image text-primary"></i>
                    </div>
                    <div class="mini-stat-info">
                        <h4>{{ $galleries->total() }}</h4>
                        <span>Total Images</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="mini-stat">
                    <div class="mini-stat-icon bg-success-soft">
                        <i class="fa-solid fa-check-circle text-success"></i>
                    </div>
                    <div class="mini-stat-info">
                        <h4>{{ $galleries->where('status', 1)->count() }}</h4>
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
                        <h4>{{ $galleries->where('status', 0)->count() }}</h4>
                        <span>Inactive</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery Table Card -->
        <div class="content-card">
            <div class="card-header-custom">
                <div class="d-flex align-items-center gap-2">
                    <h5><i class="fa-solid fa-list"></i> Gallery List</h5>
                    <span class="badge-count">{{ $galleries->total() }}</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table modern-table" id="galleriesTable">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th width="110">Image</th>
                            <th>Title</th>
                            <th width="120">Type</th>
                            <th width="100">Status</th>
                            <th width="140">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($galleries as $gallery)
                            <tr>
                                <td class="text-muted">
                                    {{ $loop->iteration + ($galleries->currentPage() - 1) * $galleries->perPage() }}</td>

                                <td>
                                    @php $galleryImage = $gallery->first_image; @endphp
                                    <div class="hotel-thumb" style="width: 90px; height: 70px;">
                                        @if ($galleryImage)
                                            <img src="{{ asset('uploads/gallery/' . $galleryImage) }}"
                                                alt="{{ $gallery->title }}">
                                        @else
                                            <div class="thumb-placeholder">
                                                <i class="fa-solid fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    <div class="hotel-info">
                                        <h6 class="hotel-name">{{ $gallery->title }}</h6>
                                    </div>
                                </td>

                                <td>
                                    <span class="room-type-badge">
                                        <i class="fa-solid fa-layer-group"></i>
                                        {{ ucfirst($gallery->type) }}
                                    </span>
                                </td>

                                <td>
                                    @if ($gallery->status)
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
                                        <a href="{{ route('admin.galleries.show', $gallery->id) }}" class="btn-action view"
                                            data-bs-toggle="tooltip" title="View Details">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.galleries.edit', $gallery->id) }}" class="btn-action edit"
                                            data-bs-toggle="tooltip" title="Edit Image">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <form action="{{ route('admin.galleries.destroy', $gallery->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Delete this image?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action delete" data-bs-toggle="tooltip"
                                                title="Delete Image">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                        <h5>No Gallery Images Found</h5>
                                        <p>Start by adding your first image to the gallery.</p>
                                        <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fa-solid fa-plus"></i> Add Image
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($galleries->hasPages())
                <div class="card-footer-custom">
                    <div class="pagination-info">
                        Showing {{ $galleries->firstItem() }} to {{ $galleries->lastItem() }} of
                        {{ $galleries->total() }} images
                    </div>
                    <div class="pagination-wrapper">
                        {{ $galleries->links() }}
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
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endsection
