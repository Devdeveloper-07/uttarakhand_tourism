@extends('layouts.admin')

@section('title', 'Users')

@section('content')

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Users</h2>
            <p class="page-subtitle">Manage Registered Users</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-user-plus me-1"></i> Add User
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
                    <i class="fa-solid fa-users text-primary"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $users->total() }}</h4>
                    <span>Total Users</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="mini-stat">
                <div class="mini-stat-icon bg-success-soft">
                    <i class="fa-solid fa-circle-check text-success"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $users->filter(function($u) { return optional($u->detail)->status == 1; })->count() }}</h4>
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
                    <h4>{{ $users->filter(function($u) { return optional($u->detail)->status == 0; })->count() }}</h4>
                    <span>Inactive</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="mini-stat">
                <div class="mini-stat-icon bg-info-soft">
                    <i class="fa-solid fa-calendar text-info"></i>
                </div>
                <div class="mini-stat-info">
                    <h4>{{ $users->where('created_at', '>=', now()->subDays(7))->count() }}</h4>
                    <span>New (7 Days)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="content-card">
        <div class="card-header-custom">
            <div class="d-flex align-items-center gap-2">
                <h5><i class="fa-solid fa-list"></i> User List</h5>
                <span class="badge-count">{{ $users->total() }}</span>
            </div>
            <div class="header-actions">
                <div class="search-box">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" placeholder="Search users..." id="userSearch">
                </div>
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table modern-table" id="usersTable">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th width="80">Photo</th>
                        <th>User Info</th>
                        <th>Contact</th>
                        <th width="90">Gender</th>
                        <th width="100">Status</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>

                        <td>
                            <div class="user-avatar">
                                @if(optional($user->detail)->image)
                                    <img src="{{ asset('uploads/users/'.optional($user->detail)->image) }}" alt="{{ $user->name }}">
                                @else
                                    <div class="avatar-placeholder">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td>
                            <div class="user-info">
                                <h6 class="user-name">{{ $user->name }}</h6>
                                <span class="user-email"><i class="fa-solid fa-envelope text-muted"></i> {{ $user->email }}</span>
                            </div>
                        </td>

                        <td>
                            <div class="contact-info">
                                @if(optional($user->detail)->mobile)
                                    <span><i class="fa-solid fa-phone text-primary"></i> {{ $user->detail->mobile }}</span>
                                @else
                                    <span class="text-muted"><i class="fa-solid fa-phone"></i> N/A</span>
                                @endif
                            </div>
                        </td>

                        <td>
                            @if(optional($user->detail)->gender)
                                <span class="gender-badge {{ strtolower(optional($user->detail)->gender) }}">
                                    <i class="fa-solid {{ optional($user->detail)->gender == 'Male' ? 'fa-mars' : (optional($user->detail)->gender == 'Female' ? 'fa-venus' : 'fa-genderless') }}"></i>
                                    {{ optional($user->detail)->gender }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <td>
                            @if(optional($user->detail)->status)
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
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                   class="btn-action view"
                                   data-bs-toggle="tooltip"
                                   title="View Details">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="btn-action edit"
                                   data-bs-toggle="tooltip"
                                   title="Edit User">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('admin.users.destroy', $user->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn-action delete"
                                            data-bs-toggle="tooltip"
                                            title="Delete User">
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
                                    <i class="fa-solid fa-users"></i>
                                </div>
                                <h5>No Users Found</h5>
                                <p>No registered users in the system yet.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="card-footer-custom">
                <div class="pagination-info">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                </div>
                <div class="pagination-wrapper">
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>

</div>

@endsection

@section('scripts')
<script>
    // Search functionality
    document.getElementById('userSearch').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#usersTable tbody tr');

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
