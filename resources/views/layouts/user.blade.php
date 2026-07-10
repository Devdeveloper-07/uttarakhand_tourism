<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | Uttarakhand Tourism</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('user/css/user.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/dashboard.css') }}">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <div class="wrapper">

        <!-- Sidebar -->
        <div class="sidebar user-sidebar">

            <!-- Brand -->
            <div class="sidebar-brand">
                <div class="welcome-icon">
                    <img src="{{ asset('assets/logo/logo.png') }}" alt="Uttarakhand Tourism Logo" class="welcome-logo">
                </div>
                <div class="brand-text">
                    <h4>Uttarakhand</h4>
                    <span>Tourism</span>
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
                        <li class="active">
                            <a href="{{ route('user.dashboard') }}">
                                <span class="nav-icon"><i class="fa-solid fa-gauge-high"></i></span>
                                <span class="nav-text">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.bookings') }}">
                                <span class="nav-icon"><i class="fa-solid fa-calendar-check"></i></span>
                                <span class="nav-text">My Bookings</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.hotels') }}">
                                <span class="nav-icon"><i class="fa-solid fa-hotel"></i></span>
                                <span class="nav-text">My Hotels</span>
                            </a>
                        </li>
                        <li>
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
                        <li>
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

                    <button class="btn sidebar-toggle-btn" id="menu-toggle">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="d-none d-md-flex ms-3">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i
                                        class="fa-solid fa-home"></i></a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </nav>

                    <!-- Right Side -->
                    <div class="ms-auto d-flex align-items-center gap-3">

                        <!-- Notifications -->
                        <div class="dropdown">
                            <button class="btn notification-btn position-relative" type="button"
                                data-bs-toggle="dropdown">
                                <i class="fa-solid fa-bell"></i>
                                <span class="badge">2</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
                                <li class="dropdown-header">Notifications</li>
                                <li><a class="dropdown-item" href="#"><i
                                            class="fa-solid fa-check-circle text-success"></i> Booking confirmed</a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="fa-solid fa-tag text-primary"></i>
                                        Special offer available</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-center text-primary" href="#">View All</a></li>
                            </ul>
                        </div>

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
