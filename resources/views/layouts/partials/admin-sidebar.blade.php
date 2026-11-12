<div class="sidebar" id="adminSidebar">

    <!-- Logo -->
    <div class="sidebar-brand">
       <div class="welcome-icon">
                    <img src="{{ asset('assets/logo/logo.png') }}" alt="Uttarakhand Travels logo" class="welcome-logo">
</div>
        <div class="brand-text">
            <h4>Uttarakhand</h4>
            <span>Travels</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <div class="nav-section">
            <span class="nav-label">Main</span>
            <ul>
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
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
                <li class="{{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.hotels.index') }}">
                        <span class="nav-icon"><i class="fa-solid fa-hotel"></i></span>
                        <span class="nav-text">Hotels</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.rooms.index') }}">
                        <span class="nav-icon"><i class="fa-solid fa-bed"></i></span>
                        <span class="nav-text">Rooms</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('admin.room-types.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.room-types.index') }}">
                        <span class="nav-icon"><i class="fa-solid fa-tags"></i></span>
                        <span class="nav-text">Room Types</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.packages.index') }}">
                        <span class="nav-icon"><i class="fa-solid fa-suitcase-rolling"></i></span>
                        <span class="nav-text">Packages</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.bookings.index') }}">
                        <span class="nav-icon"><i class="fa-solid fa-calendar-check"></i></span>
                        <span class="nav-text">Bookings</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.payments.index') }}">
                        <span class="nav-icon"><i class="fa-solid fa-credit-card"></i></span>
                        <span class="nav-text">Payments</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.gst-settings.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.gst-settings.index') }}">
                        <span class="nav-icon"><i class="fa-solid fa-percent"></i></span>
                        <span class="nav-text">GST Settings</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section">
            <span class="nav-label">Content</span>
            <ul>
                <li class="{{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.galleries.index') }}">
                        <span class="nav-icon"><i class="fa-solid fa-images"></i></span>
                        <span class="nav-text">Gallery</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.testimonials.index') }}">
                        <span class="nav-icon"><i class="fa-solid fa-star"></i></span>
                        <span class="nav-text">Testimonials</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}">
                        <span class="nav-icon"><i class="fa-solid fa-users"></i></span>
                        <span class="nav-text">Users</span>
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
