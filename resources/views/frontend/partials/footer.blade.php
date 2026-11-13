<footer class="site-footer">
    <!-- Newsletter Bar -->
    <div class="footer-newsletter">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="newsletter-content">
                        <i class="fa-solid fa-paper-plane"></i>
                        <div>
                            <h4>Subscribe to Our Newsletter</h4>
                            <p>Get exclusive deals and travel updates directly in your inbox.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0">
                    <form class="newsletter-form">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Enter your email address" required>
                            <button type="submit" class="btn btn-subscribe">
                                Subscribe <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Footer -->
    <div class="footer-main">
        <div class="container">
            <div class="row g-4 g-lg-5">
                <!-- Brand Column -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand">
                        <a href="{{ route('home') }}" class="footer-logo">
                            <span class="footer-brand-emblem" aria-hidden="true"><i class="fa-solid fa-mountain-sun"></i></span>
                            <div class="footer-logo-text">
                                <span>Uttarakhand</span>
                                <small>Travels</small>
                            </div>
                        </a>
                        <p class="footer-desc">
                            Discover the untouched beauty of Uttarakhand with premium hotels, curated tour packages, and unforgettable Himalayan experiences.
                        </p>
                        <div class="footer-contact-mini">
                            <div class="contact-item">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>Dehradun, Uttarakhand, India</span>
                            </div>
                            <div class="contact-item">
                                <i class="fa-solid fa-phone"></i>
                                <span>+91 98765 43210</span>
                            </div>
                            <div class="contact-item">
                                <i class="fa-solid fa-envelope"></i>
                                <span>info@uttarakhandtourism.com</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <div class="footer-links">
                        <h5>Quick Links</h5>
                        <ul>
                            <li>
                                <a href="{{ route('home') }}">
                                    <i class="fa-solid fa-chevron-right"></i> Home
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('hotels') }}">
                                    <i class="fa-solid fa-chevron-right"></i> Hotels
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('packages') }}">
                                    <i class="fa-solid fa-chevron-right"></i> Packages
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('gallery') }}">
                                    <i class="fa-solid fa-chevron-right"></i> Gallery
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('about') }}">
                                    <i class="fa-solid fa-chevron-right"></i> About Us
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('contact') }}">
                                    <i class="fa-solid fa-chevron-right"></i> Contact
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Top Destinations -->
                <div class="col-lg-3 col-md-6">
                    <div class="footer-links">
                        <h5>Top Destinations</h5>
                        <ul>
                            <li>
                                <a href="{{ route('packages') }}?destination=Kedarnath">
                                    <i class="fa-solid fa-chevron-right"></i> Kedarnath Yatra
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('packages') }}?destination=Badrinath">
                                    <i class="fa-solid fa-chevron-right"></i> Badrinath Dham
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('packages') }}?destination=Nainital">
                                    <i class="fa-solid fa-chevron-right"></i> Nainital Lake
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('packages') }}?destination=Rishikesh">
                                    <i class="fa-solid fa-chevron-right"></i> Rishikesh
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('packages') }}?destination=Mussoorie">
                                    <i class="fa-solid fa-chevron-right"></i> Mussoorie Hills
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('packages') }}?destination=Haridwar">
                                    <i class="fa-solid fa-chevron-right"></i> Haridwar Ghat
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Follow Us & Payment -->
                <div class="col-lg-3 col-md-6">
                    <div class="footer-social">
                        <h5>Follow Us</h5>
                        <p>Stay connected for the latest updates and travel inspiration.</p>
                        <div class="social-icons">
                            <a href="#" class="social-link facebook" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link instagram" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link youtube" title="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="#" class="social-link twitter" title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link whatsapp" title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>

                    <div class="footer-payment">
                        <h5>We Accept</h5>
                        <div class="payment-icons">
                            <div class="payment-icon"><i class="fa-brands fa-cc-visa"></i></div>
                            <div class="payment-icon"><i class="fa-brands fa-cc-mastercard"></i></div>
                            <div class="payment-icon"><i class="fa-brands fa-cc-amex"></i></div>
                            <div class="payment-icon"><i class="fa-brands fa-google-pay"></i></div>
                            <div class="payment-icon"><i class="fa-brands fa-cc-paypal"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <div class="copyright">
                    <i class="fa-solid fa-copyright"></i>
                    {{ date('Y') }} Uttarakhand Travels. All Rights Reserved.
                </div>
                <div class="footer-bottom-links">
                    <a href="#">Privacy Policy</a>
                    <span class="separator">|</span>
                    <a href="#">Terms of Service</a>
                    <span class="separator">|</span>
                    <a href="#">Refund Policy</a>
                    <span class="separator">|</span>
                    <a href="#">Sitemap</a>
                </div>
            </div>
        </div>
    </div>
</footer>
