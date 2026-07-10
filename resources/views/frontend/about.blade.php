@extends('frontend.layouts.app')

@section('title', 'About Us')

@section('content')

<!-- Hero Banner -->
<section class="about-hero">
    <div class="about-hero-bg">
        <div class="about-hero-pattern"></div>
        <div class="about-hero-overlay"></div>
    </div>
    <div class="container">
        <div class="about-hero-content">
            <span class="about-badge">
                <i class="fa-solid fa-mountain-sun"></i>
                Discover Dev Bhoomi
            </span>
            <h1 class="about-title">About Us</h1>
            <p class="about-subtitle">Experience the divine beauty and spiritual essence of Uttarakhand with world-class hospitality</p>
            <div class="about-divider">
                <span></span>
                <i class="fa-solid fa-om"></i>
                <span></span>
            </div>
        </div>
    </div>
    <div class="about-wave">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#f8fafc"/>
        </svg>
    </div>
</section>

<!-- About Section -->
<section class="about-main-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="about-image-wrapper">
                    <div class="about-image-main">
                        <img src="{{ asset('assets/image/about.png') }}" alt="About Uttarakhand Tourism" loading="lazy">
                        <div class="about-image-shine"></div>
                    </div>
                    <div class="about-float-card">
                        <div class="float-card-icon">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <div class="float-card-text">
                            <span class="float-card-number">10+</span>
                            <span class="float-card-label">Years Experience</span>
                        </div>
                    </div>
                    <div class="about-float-card-2">
                        <div class="float-card-icon-2">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div class="float-card-text-2">
                            <span class="float-card-number-2">15K+</span>
                            <span class="float-card-label-2">Happy Guests</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="about-content-wrapper">
                    <span class="about-section-tag">
                        <i class="fa-solid fa-circle-info"></i> About Us
                    </span>
                    <h2 class="about-heading">Welcome To Uttarakhand Tourism</h2>
                    <p class="about-lead">
                        Uttarakhand Tourism is dedicated to providing unforgettable travel experiences across the beautiful state of Uttarakhand.
                    </p>
                    <p class="about-body">
                        We offer premium hotels, comfortable stays, customized holiday packages and complete travel assistance for individuals, families and groups. Our mission is to showcase the pristine beauty of the Himalayas while ensuring world-class hospitality.
                    </p>

                    <div class="about-features">
                        <div class="about-feature-item">
                            <div class="feature-item-icon">
                                <i class="fa-solid fa-check-circle"></i>
                            </div>
                            <span>Verified Hotels & Stays</span>
                        </div>
                        <div class="about-feature-item">
                            <div class="feature-item-icon">
                                <i class="fa-solid fa-check-circle"></i>
                            </div>
                            <span>Customized Tour Packages</span>
                        </div>
                        <div class="about-feature-item">
                            <div class="feature-item-icon">
                                <i class="fa-solid fa-check-circle"></i>
                            </div>
                            <span>24/7 Travel Support</span>
                        </div>
                        <div class="about-feature-item">
                            <div class="feature-item-icon">
                                <i class="fa-solid fa-check-circle"></i>
                            </div>
                            <span>Best Price Guarantee</span>
                        </div>
                    </div>

                    <a href="{{ route('packages') }}" class="btn-about-cta">
                        <i class="fa-solid fa-paper-plane"></i>
                        Explore Packages
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Counter Section -->
<section class="about-counter-section">
    <div class="container">
        <div class="counter-grid">
            <div class="counter-card">
                <div class="counter-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="counter-info">
                    <span class="counter-number" data-count="15000">0</span>
                    <span class="counter-label">Happy Travelers</span>
                </div>
            </div>
            <div class="counter-card">
                <div class="counter-icon">
                    <i class="fa-solid fa-hotel"></i>
                </div>
                <div class="counter-info">
                    <span class="counter-number" data-count="500">0</span>
                    <span class="counter-label">Premium Hotels</span>
                </div>
            </div>
            <div class="counter-card">
                <div class="counter-icon">
                    <i class="fa-solid fa-suitcase-rolling"></i>
                </div>
                <div class="counter-info">
                    <span class="counter-number" data-count="200">0</span>
                    <span class="counter-label">Tour Packages</span>
                </div>
            </div>
            <div class="counter-card">
                <div class="counter-icon">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div class="counter-info">
                    <span class="counter-number" data-count="24">0</span>
                    <span class="counter-label">×7 Support</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="why-choose-section">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-tag">
                <i class="fa-solid fa-gem"></i> Our Advantages
            </span>
            <h2 class="section-title">Why Choose Us</h2>
            <p class="section-desc">Your trusted travel partner for exploring the Himalayas</p>
            <div class="section-divider"></div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-card-image">
                        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&h=400&fit=crop" alt="Best Hotels">
                        <div class="why-card-overlay"></div>
                    </div>
                    <div class="why-card-body">
                        <div class="why-card-icon">
                            <i class="fa-solid fa-hotel"></i>
                        </div>
                        <h4>Best Hotels</h4>
                        <p>Handpicked luxury and budget hotels across Uttarakhand with stunning mountain views and modern amenities.</p>
                        <a href="{{ route('hotels') }}" class="why-card-link">
                            Explore Hotels <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-card-image">
                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&h=400&fit=crop" alt="Customized Packages">
                        <div class="why-card-overlay"></div>
                    </div>
                    <div class="why-card-body">
                        <div class="why-card-icon">
                            <i class="fa-solid fa-route"></i>
                        </div>
                        <h4>Customized Packages</h4>
                        <p>Travel packages designed according to your needs, preferences and budget for a perfect holiday.</p>
                        <a href="{{ route('packages') }}" class="why-card-link">
                            View Packages <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="why-card">
                    <div class="why-card-image">
                        <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=600&h=400&fit=crop" alt="24x7 Support">
                        <div class="why-card-overlay"></div>
                    </div>
                    <div class="why-card-body">
                        <div class="why-card-icon">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <h4>24×7 Support</h4>
                        <p>Our dedicated support team is always ready to help you anytime, anywhere during your journey.</p>
                        <a href="{{ route('contact') }}" class="why-card-link">
                            Contact Us <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="mission-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fa-solid fa-bullseye"></i>
                    </div>
                    <h3>Our Mission</h3>
                    <p>To provide unforgettable travel experiences that showcase the pristine beauty of Uttarakhand while promoting sustainable tourism and supporting local communities.</p>
                    <div class="mission-progress">
                        <div class="progress-item">
                            <span>Customer Satisfaction</span>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 98%"></div>
                            </div>
                            <span class="progress-percent">98%</span>
                        </div>
                        <div class="progress-item">
                            <span>Service Quality</span>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 95%"></div>
                            </div>
                            <span class="progress-percent">95%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mission-card vision">
                    <div class="mission-icon">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    <h3>Our Vision</h3>
                    <p>To become the most trusted and preferred travel partner for exploring Uttarakhand, known for exceptional service and authentic Himalayan experiences.</p>
                    <div class="mission-features">
                        <div class="mission-feature">
                            <i class="fa-solid fa-leaf"></i>
                            <span>Eco-Friendly Tourism</span>
                        </div>
                        <div class="mission-feature">
                            <i class="fa-solid fa-hand-holding-heart"></i>
                            <span>Community Support</span>
                        </div>
                        <div class="mission-feature">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Safe Travel</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="about-testimonials">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-tag">
                <i class="fa-solid fa-comments"></i> Testimonials
            </span>
            <h2 class="section-title">What Our Customers Say</h2>
            <p class="section-desc">Real experiences from real travelers</p>
            <div class="section-divider"></div>
        </div>

        <div class="row g-4">
            @forelse($testimonials as $testimonial)
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-premium">
                        <div class="testimonial-stars">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <div class="testimonial-quote">
                            <i class="fa-solid fa-quote-left"></i>
                        </div>
                        <p class="testimonial-message">"{{ $testimonial->message }}"</p>
                        <div class="testimonial-author-premium">
                            @if ($testimonial->image)
                                <img src="{{ asset('uploads/testimonials/' . $testimonial->image) }}" alt="{{ $testimonial->name }}">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($testimonial->name) }}&background=0ea5e9&color=fff&size=80" alt="{{ $testimonial->name }}">
                            @endif
                            <div class="author-details">
                                <h5>{{ $testimonial->name }}</h5>
                                <span>{{ $testimonial->designation ?? 'Happy Traveler' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-testimonial">
                        <div class="empty-testimonial-icon">
                            <i class="fa-regular fa-comments"></i>
                        </div>
                        <h4>No Testimonials Yet</h4>
                        <p>Be the first to share your experience with us!</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="about-cta-section">
    <div class="container">
        <div class="cta-card">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2>Ready To Explore Uttarakhand?</h2>
                    <p>Book your dream vacation today and create memories that last a lifetime.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <a href="{{ route('packages') }}" class="btn-cta-premium">
                        <i class="fa-solid fa-paper-plane"></i>
                        Book Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
