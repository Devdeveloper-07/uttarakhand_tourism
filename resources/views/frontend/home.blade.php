@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')

    <!-- ================= Hero Section ================= -->
    <section id="hero-section">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div id="hero-badge">
                        <i class="fa-solid fa-mountain-sun"></i>
                        Welcome To Uttarakhand
                    </div>
                    <h1 id="hero-title">
                        Discover The Beauty Of<br>
                        <span>Dev Bhoomi Uttarakhand</span>
                    </h1>
                    <p id="hero-text">
                        Explore premium hotels, breathtaking destinations and unforgettable travel experiences across the majestic Himalayas of Uttarakhand.
                    </p>
                    <div id="hero-buttons">
                        <a href="{{ route('hotels') }}" id="btn-hero-primary">
                            <i class="fa-solid fa-hotel"></i>
                            Explore Hotels
                        </a>
                        <a href="{{ route('packages') }}" id="btn-hero-outline">
                            <i class="fa-solid fa-map-location-dot"></i>
                            Tour Packages
                        </a>
                    </div>
                    <div id="hero-trust-badges" class="d-none d-md-flex">
                        <div class="trust-item">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Secure Booking</span>
                        </div>
                        <div class="trust-item">
                            <i class="fa-solid fa-tag"></i>
                            <span>Best Price</span>
                        </div>
                        <div class="trust-item">
                            <i class="fa-solid fa-headset"></i>
                            <span>24/7 Support</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div id="hero-image-wrapper">
                        <img src="{{ asset('assets/image/home_2.png') }}" class="img-fluid" id="hero-image" alt="Uttarakhand Mountains">
                        <div id="hero-float-card" class="d-none d-lg-flex">
                            <div class="icon-box">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div class="text-box">
                                <h6>Happy Travelers</h6>
                                <p>15,000+</p>
                            </div>
                        </div>
                        <div id="hero-float-card-2" class="d-none d-lg-flex">
                            <div class="icon-box">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <div class="text-box">
                                <h6>Rated</h6>
                                <p>4.9/5</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ================= Stats Section ================= -->
    <section id="stats-section">
        <div class="container">
            <div id="stats-wrapper">
                <div class="row g-0">
                    <div class="col-6 col-md-3">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fa-solid fa-hotel"></i>
                            </div>
                            <div class="stat-number" data-count="500">0</div>
                            <div class="stat-label">Premium Hotels</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fa-solid fa-suitcase-rolling"></i>
                            </div>
                            <div class="stat-number" data-count="200">0</div>
                            <div class="stat-label">Tour Packages</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div class="stat-number" data-count="15000">0</div>
                            <div class="stat-label">Happy Travelers</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fa-solid fa-award"></i>
                            </div>
                            <div class="stat-number" data-count="4.9" data-decimal="1">0</div>
                            <div class="stat-label">Average Rating</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ================= Featured Hotels ================= -->
    <section id="hotels-section" class="py-5">
        <div class="container py-4">
            <div id="section-header-hotels" class="section-header">
                <span class="section-label"><i class="fa-solid fa-hotel"></i> Our Hotels</span>
                <h2>Featured Hotels</h2>
                <p>Discover the best handpicked hotels across Uttarakhand for an unforgettable stay.</p>
                <div class="divider"></div>
            </div>
            <div class="row g-4">
                @forelse($featuredHotels as $hotel)
                    <div class="col-lg-4 col-md-6">
                        <div class="hotel-card">
                            <div class="hotel-image-wrapper">
                                @if ($hotel->thumbnail)
                                    <img src="{{ asset('uploads/hotels/' . $hotel->thumbnail) }}" class="hotel-image" alt="{{ $hotel->hotel_name }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&h=400&fit=crop" class="hotel-image" alt="Hotel">
                                @endif
                                <span class="hotel-badge"><i class="fa-solid fa-crown"></i> Featured</span>
                                <div class="hotel-rating">
                                    <i class="fa-solid fa-star"></i> {{ number_format($hotel->rating ?? 4.5, 1) }}
                                </div>
                                <div class="hotel-wishlist">
                                    <i class="fa-regular fa-heart"></i>
                                </div>
                            </div>
                            <div class="hotel-body">
                                <h4>{{ $hotel->hotel_name }}</h4>
                                <div class="hotel-location">
                                    <i class="fa-solid fa-location-dot"></i>
                                    {{ $hotel->location }}
                                </div>
                                <div class="hotel-amenities">
                                    <span class="amenity-tag"><i class="fa-solid fa-wifi"></i> Free WiFi</span>
                                    <span class="amenity-tag"><i class="fa-solid fa-utensils"></i> Restaurant</span>
                                    <span class="amenity-tag"><i class="fa-solid fa-spa"></i> Spa</span>
                                </div>
                                <div class="hotel-footer">
                                    <div class="hotel-price">
                                        <span class="price-label">Starting from</span>
                                        <span class="price-value">₹{{ number_format($hotel->starting_price ?? 0) }} <span>/ night</span></span>
                                    </div>
                                    <a href="{{ route('hotel.details', $hotel->id) }}" class="btn-view">
                                        View <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center py-5">
                            <i class="fa-solid fa-hotel fa-2x mb-3 text-muted"></i>
                            <h5 class="text-muted">No Hotels Found</h5>
                            <p class="text-muted mb-0">Check back later for amazing hotel deals!</p>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="text-center mt-5">
                <a href="{{ route('hotels') }}" id="btn-explore-hotels" class="btn-explore">
                    View All Hotels <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>


    <!-- ================= Featured Packages ================= -->
    <section id="packages-section" class="py-5">
        <div class="container py-4">
            <div id="section-header-packages" class="section-header">
                <span class="section-label"><i class="fa-solid fa-suitcase-rolling"></i> Our Packages</span>
                <h2>Featured Tour Packages</h2>
                <p>Best selling Uttarakhand tour packages crafted for every kind of traveler.</p>
                <div class="divider"></div>
            </div>
            <div class="row g-4">
                @forelse($featuredPackages as $package)
                    <div class="col-lg-4 col-md-6">
                        <div class="package-card">
                            <div class="package-image-wrapper">
                                @if ($package->thumbnail)
                                    <img src="{{ asset('uploads/packages/' . $package->thumbnail) }}" class="package-image" alt="{{ $package->package_name }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&h=400&fit=crop" class="package-image" alt="Package">
                                @endif
                                <div class="package-duration">
                                    <i class="fa-regular fa-clock"></i> {{ $package->duration ?? '5 Days / 4 Nights' }}
                                </div>
                                <div class="package-discount">
                                    <i class="fa-solid fa-percent"></i> 20% OFF
                                </div>
                            </div>
                            <div class="package-body">
                                <h4>{{ $package->package_name }}</h4>
                                <p>{{ Str::limit($package->description, 100) }}</p>
                                <div class="package-features">
                                    <div class="package-feature">
                                        <i class="fa-solid fa-hotel"></i> Hotel Stay
                                    </div>
                                    <div class="package-feature">
                                        <i class="fa-solid fa-car"></i> Transport
                                    </div>
                                    <div class="package-feature">
                                        <i class="fa-solid fa-utensils"></i> Meals
                                    </div>
                                </div>
                                <div class="package-footer">
                                    <div class="package-price">
                                        <span class="price-label">Per Person</span>
                                        <span class="price-value">₹{{ number_format($package->price) }}</span>
                                    </div>
                                    <a href="{{ route('package.details', $package->id) }}" class="btn-package">
                                        Book Now <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center py-5">
                            <i class="fa-solid fa-suitcase fa-2x mb-3 text-muted"></i>
                            <h5 class="text-muted">No Packages Found</h5>
                            <p class="text-muted mb-0">Exciting packages coming soon!</p>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="text-center mt-5">
                <a href="{{ route('packages') }}" id="btn-explore-packages" class="btn-explore btn-explore-green">
                    View All Packages <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>


    <!-- ================= Why Choose Us ================= -->
    <section id="features-section" class="py-5">
        <div class="container py-4">
            <div id="section-header-features" class="section-header">
                <span class="section-label"><i class="fa-solid fa-gem"></i> Why Us</span>
                <h2>Why Choose Us</h2>
                <p>We make your Uttarakhand journey seamless, safe, and truly memorable.</p>
                <div class="divider"></div>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fa-solid fa-hotel"></i>
                        </div>
                        <h5>Premium Hotels</h5>
                        <p>Carefully selected hotels across Uttarakhand with verified quality and comfort.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fa-solid fa-route"></i>
                        </div>
                        <h5>Best Tour Packages</h5>
                        <p>Affordable and well-planned packages for every type of traveler and budget.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <h5>24×7 Support</h5>
                        <p>Friendly and responsive travel support available round the clock for you.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h5>Secure Booking</h5>
                        <p>Safe and secure online booking process with instant confirmation.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ================= Gallery Preview ================= -->
    <section id="gallery-section" class="py-5">
        <div class="container py-4">
            <div id="section-header-gallery" class="section-header">
                <span class="section-label"><i class="fa-solid fa-images"></i> Gallery</span>
                <h2>Travel Gallery</h2>
                <p>Beautiful destinations and moments captured across Uttarakhand.</p>
                <div class="divider"></div>
            </div>
            <div class="row g-3">
                @forelse($galleries as $gallery)
                    @php
                        $images = is_array($gallery->image) ? $gallery->image : json_decode($gallery->image, true);
                    @endphp
                    @if(!empty($images))
                        @foreach($images as $image)
                            <div class="col-lg-3 col-md-4 col-6">
                                <div class="gallery-card">
                                    <img src="{{ asset('uploads/gallery/'.$image) }}" class="gallery-image" alt="Gallery Image" loading="lazy">
                                    <div class="gallery-overlay">
                                        <div>
                                            <h5>{{ $gallery->title ?? 'Uttarakhand' }}</h5>
                                            <p>{{ $gallery->location ?? 'Beautiful Destination' }}</p>
                                        </div>
                                        <div class="gallery-zoom">
                                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @empty
                    <div class="col-12">
                        <div class="alert alert-light text-center py-5">
                            <i class="fa-solid fa-images fa-2x mb-3 text-muted"></i>
                            <h5 class="text-muted">Gallery Not Available</h5>
                            <p class="text-muted mb-0">Check back later for stunning photos!</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>


    <!-- ================= Testimonials ================= -->
    <section id="testimonials-section" class="py-5">
        <div class="container py-4">
            <div id="section-header-testimonials" class="section-header">
                <span class="section-label"><i class="fa-solid fa-comments"></i> Testimonials</span>
                <h2>What Our Travelers Say</h2>
                <p>Hear from our happy customers about their Uttarakhand experience.</p>
                <div class="divider"></div>
            </div>
            <div class="row g-4">
                @forelse($testimonials as $testimonial)
                    <div class="col-lg-4 col-md-6">
                        <div class="testimonial-card">
                            <div class="testimonial-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= ($testimonial->rating ?? 5))
                                        <i class="fa-solid fa-star"></i>
                                    @else
                                        <i class="fa-regular fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="testimonial-content">
                                <i class="fa-solid fa-quote-left quote-mark"></i>
                                <p>{{ $testimonial->message }}</p>
                            </div>
                            <div class="testimonial-user">
                                @if ($testimonial->image)
                                    <img src="{{ asset('uploads/testimonials/' . $testimonial->image) }}" class="testimonial-avatar" alt="{{ $testimonial->name }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100&h=100&fit=crop" class="testimonial-avatar" alt="User">
                                @endif
                                <div>
                                    <h6>{{ $testimonial->name }}</h6>
                                    <small>{{ $testimonial->designation ?? 'Traveler' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light text-center py-5">
                            <i class="fa-solid fa-comments fa-2x mb-3 text-muted"></i>
                            <h5 class="text-muted">No Testimonials Available</h5>
                            <p class="text-muted mb-0">Be the first to share your experience!</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>


    <!-- ================= CTA Section ================= -->
    <section id="cta-section">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2>Ready For Your Next Adventure?</h2>
                    <p>Book your dream hotel and explore the breathtaking beauty of Uttarakhand today. Your Himalayan journey awaits!</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <a href="{{ route('hotels') }}" id="btn-cta">
                        <i class="fa-solid fa-calendar-check"></i>
                        Book Now
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        function animateCounter(el) {
            const target = parseFloat(el.getAttribute('data-count'));
            const decimal = parseInt(el.getAttribute('data-decimal')) || 0;
            const duration = 2000;
            const start = 0;
            const increment = target / (duration / 16);
            let current = start;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                if (decimal > 0) {
                    el.textContent = current.toFixed(decimal) + (target >= 1000 ? '+' : '');
                } else {
                    el.textContent = Math.floor(current).toLocaleString() + (target >= 1000 ? '+' : '');
                }
            }, 16);
        }

        // Intersection Observer for counters
        const observerOptions = { threshold: 0.5 };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.stat-number').forEach(el => {
            observer.observe(el);
        });

        // Wishlist toggle
        document.querySelectorAll('.hotel-wishlist').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const icon = this.querySelector('i');
                if (icon.classList.contains('fa-regular')) {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');
                    this.style.background = '#ef4444';
                } else {
                    icon.classList.remove('fa-solid');
                    icon.classList.add('fa-regular');
                    this.style.background = 'rgba(255,255,255,0.95)';
                }
            });
        });
    </script>

@endsection
