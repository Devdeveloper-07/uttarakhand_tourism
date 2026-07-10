<x-guest-layout>
    <div class="card login-card">
        <div class="row g-0 h-100">

            <!-- Left Side: Form -->
            <div class="col-lg-6 form-side">

                <!-- Heading -->
                <h2 class="welcome-heading">Welcome Back!</h2>
                <p class="welcome-subtitle">Log in to your account and continue reading</p>

                <!-- Session Status -->
                <x-auth-session-status class="mb-3" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label-custom">Email Address</label>
                        <div class="input-group-custom">
                            <div class="input-icon-box">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <input type="email" class="form-control-custom @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}" placeholder="your@email.com"
                                required autofocus autocomplete="username">
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label-custom">Password</label>
                        <div class="input-group-custom">
                            <div class="input-icon-box">
                                <i class="bi bi-lock"></i>
                            </div>
                            <input type="password" class="form-control-custom @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="••••••••" required
                                autocomplete="current-password">
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="remember-check">
                        <input type="checkbox" id="remember_me" name="remember">
                        <label for="remember_me">Remember me</label>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="link-forgot">
                                Forgot your password?
                            </a>
                        @endif
                    </div>

                    <!-- Full Width Login Button -->
                    <button type="submit" class="btn btn-full">
                        <i class="bi bi-box-arrow-in-right"></i>
                        LOG IN
                    </button>
                </form>

                <!-- Sign Up Link -->
                <div class="text-center mt-4">
                    <span style="font-size: 0.85rem; color: #6c757d;">Don't have an account? </span>
                    <a href="{{ route('register') }}" class="link-signup">Sign up</a>
                </div>
            </div>

            <!-- Right Side: Info Panel -->
            <div class="col-lg-6 info-side">
                <div class="position-relative" style="z-index: 1;">

                    <!-- Brand Name -->
                    <h1 class="brand-name">Hotel Admin Portal</h1>

                    <!-- Description -->
                    <p class="brand-desc">
                        Welcome back to the Hotel Admin Portal. Sign in to securely manage hotel bookings,
                        room availability, guest details, packages, testimonials, galleries, and website
                        content—all from one powerful and easy-to-use dashboard.
                    </p>

                    <!-- Features -->
                    <div class="feature-item">
                        <div class="feature-icon-box">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <span class="feature-text">Easy Account Setup</span>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon-box">
                            <i class="bi bi-shield-fill"></i>
                        </div>
                        <span class="feature-text">Secure & Reliable Platform</span>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon-box">
                            <i class="bi bi-lightning-fill"></i>
                        </div>
                        <span class="feature-text">Real-time Updates</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>
