@extends('frontend.layouts.app')

@section('title', 'Contact Us')

@section('content')

<!-- Hero Banner -->
<section class="contact-hero">
    <div class="contact-hero-bg">
        <div class="contact-hero-pattern"></div>
        <div class="contact-hero-overlay"></div>
    </div>
    <div class="container">
        <div class="contact-hero-content">
            <span class="contact-badge">
                <i class="fa-solid fa-headset"></i>
                Get In Touch
            </span>
            <h1 class="contact-title">Contact Us</h1>
            <p class="contact-subtitle">We'd love to hear from you. Reach out for any queries or assistance.</p>
            <div class="contact-divider">
                <span></span>
                <i class="fa-solid fa-paper-plane"></i>
                <span></span>
            </div>
        </div>
    </div>
    <div class="contact-wave">
        <svg viewBox="0 0 1440 120" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#f8fafc"/>
        </svg>
    </div>
</section>

<!-- Contact Info Cards -->
<section class="contact-info-section">
    <div class="container">
        <div class="info-cards-row">
            <div class="info-card-premium">
                <div class="info-card-icon">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <h4>Visit Us</h4>
                <p>Dehradun, Uttarakhand, India</p>
                <a href="https://maps.google.com/?q=Dehradun,Uttarakhand" target="_blank" class="info-card-link">
                    View on Map <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            <div class="info-card-premium">
                <div class="info-card-icon">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <h4>Call Us</h4>
                <p>+91 98765 43210</p>
                <a href="tel:+919876543210" class="info-card-link">
                    Call Now <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            <div class="info-card-premium">
                <div class="info-card-icon">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <h4>Email Us</h4>
                <p>info@uttarakhandtourism.com</p>
                <a href="mailto:info@uttarakhandtourism.com" class="info-card-link">
                    Send Email <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            <div class="info-card-premium">
                <div class="info-card-icon">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <h4>Working Hours</h4>
                <p>Mon - Sat: 9AM - 7PM</p>
                <span class="info-card-status">
                    <span class="status-dot"></span> Available Now
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-main-section">
    <div class="container">
        <div class="row g-4">
            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="contact-form-premium">
                    <div class="form-premium-header">
                        <span class="form-tag">
                            <i class="fa-solid fa-pen-to-square"></i> Send Message
                        </span>
                        <h2>Get In Touch</h2>
                        <p>Fill out the form below and we'll get back to you shortly</p>
                    </div>

                    @if(session('success'))
                        <div class="alert-premium-success">
                            <div class="alert-icon">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <div class="alert-content">
                                <strong>Success!</strong>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    <form action="#" method="POST" class="premium-form">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-field">
                                    <label>Full Name <span>*</span></label>
                                    <div class="input-group-premium">
                                        <span class="input-icon">
                                            <i class="fa-solid fa-user"></i>
                                        </span>
                                        <input type="text" name="name" class="form-control-premium" placeholder="John Doe" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-field">
                                    <label>Email Address <span>*</span></label>
                                    <div class="input-group-premium">
                                        <span class="input-icon">
                                            <i class="fa-solid fa-envelope"></i>
                                        </span>
                                        <input type="email" name="email" class="form-control-premium" placeholder="john@example.com" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-field">
                                    <label>Phone Number</label>
                                    <div class="input-group-premium">
                                        <span class="input-icon">
                                            <i class="fa-solid fa-phone"></i>
                                        </span>
                                        <input type="tel" name="mobile" class="form-control-premium" placeholder="+91 98765 43210">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-field">
                                    <label>Subject <span>*</span></label>
                                    <div class="input-group-premium">
                                        <span class="input-icon">
                                            <i class="fa-solid fa-tag"></i>
                                        </span>
                                        <select name="subject" class="form-control-premium" required>
                                            <option value="">Select Subject</option>
                                            <option value="general">General Inquiry</option>
                                            <option value="booking">Booking Support</option>
                                            <option value="package">Package Query</option>
                                            <option value="hotel">Hotel Query</option>
                                            <option value="feedback">Feedback</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-field">
                                    <label>Message <span>*</span></label>
                                    <div class="input-group-premium textarea-group">
                                        <span class="input-icon">
                                            <i class="fa-solid fa-message"></i>
                                        </span>
                                        <textarea name="message" rows="5" class="form-control-premium" placeholder="Tell us about your travel plans..." required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn-premium-submit">
                            <span>Send Message</span>
                            <div class="btn-icon">
                                <i class="fa-solid fa-paper-plane"></i>
                            </div>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Sidebar -->
            <div class="col-lg-5">
                <div class="contact-sidebar">
                    <!-- Quick Connect -->
                    <div class="sidebar-card">
                        <h4><i class="fa-solid fa-bolt"></i> Quick Connect</h4>
                        <p>Need urgent assistance? Connect with us instantly.</p>
                        <div class="quick-buttons">
                            <a href="https://wa.me/919876543210" target="_blank" class="quick-btn whatsapp">
                                <i class="fa-brands fa-whatsapp"></i>
                                <span>WhatsApp</span>
                            </a>
                            <a href="tel:+919876543210" class="quick-btn call">
                                <i class="fa-solid fa-phone"></i>
                                <span>Call Now</span>
                            </a>
                        </div>
                    </div>

                    <!-- FAQ Preview -->
                    <div class="sidebar-card faq-card">
                        <h4><i class="fa-solid fa-circle-question"></i> Popular Questions</h4>
                        <div class="faq-list">
                            <div class="faq-item">
                                <i class="fa-solid fa-chevron-right"></i>
                                <span>How do I book a tour package?</span>
                            </div>
                            <div class="faq-item">
                                <i class="fa-solid fa-chevron-right"></i>
                                <span>What is the cancellation policy?</span>
                            </div>
                            <div class="faq-item">
                                <i class="fa-solid fa-chevron-right"></i>
                                <span>Do you offer customized packages?</span>
                            </div>
                            <div class="faq-item">
                                <i class="fa-solid fa-chevron-right"></i>
                                <span>What payment methods are accepted?</span>
                            </div>
                        </div>
                        <a href="#" class="view-all-faq">View All FAQs <i class="fa-solid fa-arrow-right"></i></a>
                    </div>

                    <!-- Social Connect -->
                    <div class="sidebar-card social-card">
                        <h4><i class="fa-solid fa-share-nodes"></i> Follow Us</h4>
                        <p>Stay updated with latest offers and travel stories.</p>
                        <div class="social-grid">
                            <a href="#" class="social-btn facebook">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-btn instagram">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                            <a href="#" class="social-btn twitter">
                                <i class="fa-brands fa-twitter"></i>
                            </a>
                            <a href="#" class="social-btn youtube">
                                <i class="fa-brands fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="map-premium-section">
    <div class="container">
        <div class="map-header">
            <span class="map-tag">
                <i class="fa-solid fa-map-location-dot"></i> Find Us
            </span>
            <h2>Our Location</h2>
            <p>Visit our office in Dehradun for in-person assistance</p>
        </div>
        <div class="map-wrapper-premium">
            <iframe
                src="https://www.google.com/maps?q=Dehradun,Uttarakhand,India&output=embed"
                width="100%"
                height="100%"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
            <div class="map-overlay">
                <div class="map-location-pin">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>Uttarakhand Travels HQ</span>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
