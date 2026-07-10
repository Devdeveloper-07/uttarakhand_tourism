<div class="sidebar">

    <!-- Logo -->
    <div class="sidebar-brand">
       <div class="welcome-icon">
    <img src="{{ asset('assets/logo/logo.png') }}" alt="Uttarakhand Tourism Logo" class="welcome-logo">
</div>
        <div class="brand-text">
            <h4>Uttarakhand</h4>
            <span>Tourism</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <div class="nav-section">
            <span class="nav-label">Main</span>
            <ul>
                <li class="active">
                    <a href="{{ route('admin.dashboard') }}">
                        <span class="nav-icon"><i class="fa-solid fa-gauge-high"></i></span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section">
            <span class="nav-label">Management</span>
            <ul>
                <li>
                    <a href="{{ route('admin.hotels.index') }}">
                        <span class="nav-icon"><i class="fa-solid fa-hotel"></i></span>
                        <span class="nav-text">Hotels</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.rooms.index') }}">
                        <span class="nav-icon"><i class="fa-solid fa-bed"></i></span>
                        <span class="nav-text">Rooms</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.room-types.index') }}">
                        <span class="nav-icon"><i class="fa-solid fa-tags"></i></span>
                        <span class="nav-text">Room Types</span>
                    </a>
                </li>                <li>
                    <a href="{{ route('admin.packages.index') }}">
                        <span class="nav-icon"><i class="fa-solid fa-suitcase-rolling"></i></span>
                        <span class="nav-text">Packages</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="nav-icon"><i class="fa-solid fa-calendar-check"></i></span>
                        <span class="nav-text">Bookings</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section">
            <span class="nav-label">Content</span>
            <ul>
                <li>
                    <a href="{{ route('admin.galleries.index') }}">
                        <span class="nav-icon"><i class="fa-solid fa-images"></i></span>
                        <span class="nav-text">Gallery</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.testimonials.index') }}">
                        <span class="nav-icon"><i class="fa-solid fa-star"></i></span>
                        <span class="nav-text">Testimonials</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}">
                        <span class="nav-icon"><i class="fa-solid fa-users"></i></span>
                        <span class="nav-text">Users</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section">
            <span class="nav-label">Analytics</span>
            <ul>
                <li>
                    <a href="#">
                        <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span>
                        <span class="nav-text">Reports</span>
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
