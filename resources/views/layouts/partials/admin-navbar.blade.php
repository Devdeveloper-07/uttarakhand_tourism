<nav class="navbar navbar-expand-lg admin-navbar">
    <div class="container-fluid">

        <!-- Sidebar Toggle -->
        <button class="btn sidebar-toggle-btn" id="menu-toggle">
            <i class="fa-solid fa-bars"></i>
        </button>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="d-none d-md-flex ms-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-home"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>

        <!-- Right Side -->
        <div class="ms-auto d-flex align-items-center gap-3">

            <!-- Notifications -->
            <div class="dropdown">
                <button class="btn notification-btn position-relative" type="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-bell"></i>
                    <span class="badge">3</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
                    <li class="dropdown-header">Notifications</li>
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-calendar-check text-success"></i> New booking received</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user-plus text-primary"></i> New user registered</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-star text-warning"></i> New testimonial</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-center text-primary" href="#">View All</a></li>
                </ul>
            </div>

            <!-- User Profile -->
            <div class="dropdown user-dropdown">
                <a class="dropdown-toggle d-flex align-items-center gap-2 text-decoration-none" href="#" role="button" data-bs-toggle="dropdown">
                    <div class="user-avatar">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="d-none d-md-block">
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <span class="user-role d-block">Administrator</span>
                    </div>
                    <i class="fa-solid fa-chevron-down ms-1 small"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-header">Account</li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fa-solid fa-user-gear"></i> Profile Settings
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fa-solid fa-shield-halved"></i> Security
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger">
                                <i class="fa-solid fa-right-from-bracket"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</nav>
