<header class="site-header">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('home') }}">
                <div class="brand-wrapper">
                    <img src="{{ asset('assets/logo/logo.png') }}"
                         alt="Uttarakhand Tourism Logo"
                         class="brand-logo">
                </div>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation -->
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fa-solid fa-house"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hotels') ? 'active' : '' }}" href="{{ route('hotels') }}">
                            <i class="fa-solid fa-hotel"></i> Hotels
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('packages') ? 'active' : '' }}" href="{{ route('packages') }}">
                            <i class="fa-solid fa-suitcase-rolling"></i> Packages
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}">
                            <i class="fa-solid fa-images"></i> Gallery
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                            <i class="fa-solid fa-circle-info"></i> About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                            <i class="fa-solid fa-envelope"></i> Contact
                        </a>
                    </li>
                </ul>

                <!-- Auth Buttons -->
                <div class="nav-auth">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-nav-outline">
                            <i class="fa-solid fa-user"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-nav-primary">
                            <i class="fa-solid fa-user-plus"></i> Register
                        </a>
                    @endguest

                    @auth
                        @if(auth()->user()->role=='admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-nav-primary">
                                <i class="fa-solid fa-gauge-high"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('user.dashboard') }}" class="btn btn-nav-primary">
                                <i class="fa-solid fa-gauge-high"></i> Dashboard
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>
