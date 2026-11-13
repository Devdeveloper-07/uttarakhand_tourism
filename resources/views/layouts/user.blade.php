<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo/logo333.png') }}">
    <title>@yield('title', 'My trips') | Uttarakhand Travels</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('user/css/main.css') }}?v={{ filemtime(public_path('user/css/main.css')) }}">
    @stack('styles')
</head>

<body class="user-dashboard">

    <div class="wrapper">

        <!-- Sidebar -->
        <div class="sidebar user-sidebar" id="userSidebar">

            <!-- Brand -->
            <div class="sidebar-brand">
                <div class="welcome-icon">
                    <i class="fa-solid fa-mountain-sun" aria-hidden="true"></i>
                </div>
                <div class="brand-text">
                    <h4>Uttarakhand</h4>
                    <span>Travels</span>
                </div>
            </div>

            <!-- User Info -->
            <div class="sidebar-user">
                <div class="user-avatar-large">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="user-info">
                    <h5>{{ auth()->user()->name }}</h5>
                    <span>Member</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <span class="nav-label">Menu</span>
                    <ul>
                        <li class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('user.dashboard') }}">
                                <span class="nav-icon"><i class="fa-solid fa-gauge-high"></i></span>
                                <span class="nav-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('user.bookings*') ? 'active' : '' }}">
                            <a href="{{ route('user.bookings') }}">
                                <span class="nav-icon"><i class="fa-solid fa-calendar-check"></i></span>
                                <span class="nav-text">My Bookings</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('user.hotels*') ? 'active' : '' }}">
                            <a href="{{ route('user.hotels') }}">
                                <span class="nav-icon"><i class="fa-solid fa-hotel"></i></span>
                                <span class="nav-text">My Hotels</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('user.packages*') ? 'active' : '' }}">
                            <a href="{{ route('user.packages') }}">
                                <span class="nav-icon"><i class="fa-solid fa-suitcase-rolling"></i></span>
                                <span class="nav-text">My Packages</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <span class="nav-label">Account</span>
                    <ul>
                        <li class="{{ request()->routeIs('user.profile*') ? 'active' : '' }}">
                            <a href="{{ route('user.profile') }}">
                                <span class="nav-icon"><i class="fa-solid fa-user-gear"></i></span>
                                <span class="nav-text">Profile</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout-btn">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main">

            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg user-navbar">
                <div class="container-fluid">

                    <button class="btn sidebar-toggle-btn" id="menu-toggle" type="button" aria-controls="userSidebar" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="d-none d-md-flex ms-3">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i
                                        class="fa-solid fa-home"></i></a></li>
                            <li class="breadcrumb-item active">{{ str()->headline(request()->segment(2) ?? 'Dashboard') }}</li>
                        </ol>
                    </nav>

                    <!-- Right Side -->
                    <div class="ms-auto d-flex align-items-center gap-3">

                        <!-- User Dropdown -->
                        <div class="dropdown user-dropdown">
                            <a class="dropdown-toggle d-flex align-items-center gap-2 text-decoration-none"
                                href="#" role="button" data-bs-toggle="dropdown">
                                <div class="user-avatar">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div class="d-none d-md-block">
                                    <span class="user-name">{{ auth()->user()->name }}</span>
                                </div>
                                <i class="fa-solid fa-chevron-down ms-1 small"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.profile') }}">
                                        <i class="fa-solid fa-user-gear"></i> Profile
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
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

            <!-- Content -->
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('user/js/user.js') }}"></script>

</body>

</html>
