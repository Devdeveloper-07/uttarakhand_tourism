<header class="travel-header">
    <nav class="navbar navbar-expand-lg" aria-label="Main navigation">
        <div class="container">
            <a class="travel-brand" href="{{ route('home') }}" aria-label="Uttarakhand Travels home">
                <span class="travel-brand-emblem" aria-hidden="true"><i class="fa-solid fa-mountain-sun"></i></span>
                <span class="travel-brand-name"><strong>Uttarakhand</strong><small>Travels</small></span>
            </a>
            <button class="navbar-toggler travel-menu-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#travelNav" aria-controls="travelNav" aria-expanded="false" aria-label="Open navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="travelNav">
                <ul class="navbar-nav mx-auto travel-nav-links">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('hotels*','hotel.*') ? 'active' : '' }}" href="{{ route('hotels') }}">Hotels</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('packages*','package.*') ? 'active' : '' }}" href="{{ route('packages') }}">Tour packages</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
                </ul>
                <div class="travel-nav-actions">
                    @guest
                        <a href="{{ route('login') }}" class="travel-login">Log in</a>
                        <a href="{{ route('register') }}" class="btn travel-cta">Create account</a>
                    @endguest
                    @auth
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="btn travel-cta"><i class="bi bi-person-circle me-1"></i> My trips</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>
