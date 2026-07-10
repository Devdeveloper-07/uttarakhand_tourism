@extends('layouts.admin')

@section('title', 'Testimonials')

@section('content')

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Testimonials</h2>
            <p class="page-subtitle">Manage Customer Testimonials</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Add Testimonial
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
                    <i class="fa-solid fa-comments text-primary"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $testimonials->total() }}</h4>
                    <span>Total Testimonials</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="mini-stat">
                <div class="mini-stat-icon bg-success-soft">
                    <i class="fa-solid fa-check-circle text-success"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $testimonials->where('status', 1)->count() }}</h4>
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
                    <h4>{{ $testimonials->where('status', 0)->count() }}</h4>
                    <span>Inactive</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="mini-stat">
                <div class="mini-stat-icon bg-warning-soft">
                    <i class="fa-solid fa-star text-warning"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $testimonials->avg('rating') ? number_format($testimonials->avg('rating'), 1) : '0.0' }}</h4>
                    <span>Avg Rating</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Table Card -->
    <div class="content-card">
        <div class="card-header-custom">
            <div class="d-flex align-items-center gap-2">
                <h5><i class="fa-solid fa-list"></i> All Testimonials</h5>
                <span class="badge-count">{{ $testimonials->total() }}</span>
            </div>
            <div class="header-actions">
                <div class="search-box">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" placeholder="Search testimonials..." id="testimonialSearch">
                </div>
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table modern-table" id="testimonialsTable">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th width="90">Image</th>
                        <th>Name & Designation</th>
                        <th>Rating</th>
                        <th width="100">Status</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($testimonials as $testimonial)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration + ($testimonials->currentPage() - 1) * $testimonials->perPage() }}</td>

                        <td>
                            <div class="testimonial-avatar">
                                @if($testimonial->image)
                                    <img src="{{ asset('uploads/testimonials/'.$testimonial->image) }}" alt="{{ $testimonial->name }}">
                                @else
                                    <div class="avatar-placeholder">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td>
                            <div class="testimonial-info">
                                <h6 class="testimonial-name">{{ $testimonial->name }}</h6>
                                <span class="testimonial-designation"><i class="fa-solid fa-briefcase text-muted"></i> {{ $testimonial->designation }}</span>
                            </div>
                        </td>

                        <td>
                            <div class="rating-display">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $testimonial->rating)
                                        <i class="fa-solid fa-star text-warning"></i>
                                    @else
                                        <i class="fa-regular fa-star text-secondary"></i>
                                    @endif
                                @endfor
                                <span class="rating-value">{{ $testimonial->rating }}/5</span>
                            </div>
                        </td>

                        <td>
                            @if($testimonial->status)
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
                                <a href="{{ route('admin.testimonials.show', $testimonial->id) }}"
                                   class="btn-action view"
                                   data-bs-toggle="tooltip"
                                   title="View Details">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}"
                                   class="btn-action edit"
                                   data-bs-toggle="tooltip"
                                   title="Edit Testimonial">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this testimonial?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn-action delete"
                                            data-bs-toggle="tooltip"
                                            title="Delete Testimonial">
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
                                    <i class="fa-solid fa-comments"></i>
                                </div>
                                <h5>No Testimonials Found</h5>
                                <p>Start by adding your first customer testimonial.</p>
                                <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-plus"></i> Add Testimonial
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($testimonials->hasPages())
            <div class="card-footer-custom">
                <div class="pagination-info">
                    Showing {{ $testimonials->firstItem() }} to {{ $testimonials->lastItem() }} of {{ $testimonials->total() }} testimonials
                </div>
                <div class="pagination-wrapper">
                    {{ $testimonials->links() }}
                </div>
            </div>
        @endif
    </div>

</div>

@endsection

@section('scripts')
<script>
    // Search functionality
    document.getElementById('testimonialSearch').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#testimonialsTable tbody tr');

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
