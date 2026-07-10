@extends('frontend.layouts.app')

@section('title','Tour Packages')

@section('content')

<!-- ===== HERO BANNER ===== -->
<section class="hero-banner">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <span class="hero-tagline">
            <i class="fa-solid fa-compass"></i> Discover Uttarakhand
        </span>
        <h1 class="hero-title">Tour Packages</h1>
        <p class="hero-subtitle">Explore Amazing Uttarakhand Tour Packages — From Sacred Pilgrimages to Himalayan Adventures</p>
        <div class="hero-divider">
            <span></span>
            <i class="fa-solid fa-mountain-sun"></i>
            <span></span>
        </div>
    </div>
</section>

<!-- ===== SEARCH BAR ===== -->
<section class="search-section">
    <div class="container">
        <div class="search-card">
            <form action="{{ route('packages') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <label class="search-label">
                            <i class="fa-solid fa-magnifying-glass"></i> Search Package
                        </label>
                        <input type="text"
                               class="form-control search-input"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="e.g. Kedarnath Yatra, Badrinath...">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="search-label">
                            <i class="fa-solid fa-map-location-dot"></i> Destination
                        </label>
                        <select class="form-select search-select" name="destination">
                            <option value="">All Destinations</option>
                            <option {{ request('destination') == 'Barkot' ? 'selected' : '' }}>Barkot</option>
                            <option {{ request('destination') == 'Kedarnath' ? 'selected' : '' }}>Kedarnath</option>
                            <option {{ request('destination') == 'Haridwar' ? 'selected' : '' }}>Haridwar</option>
                            <option {{ request('destination') == 'Rishikesh' ? 'selected' : '' }}>Rishikesh</option>
                            <option {{ request('destination') == 'Nainital' ? 'selected' : '' }}>Nainital</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="search-label">
                            <i class="fa-solid fa-indian-rupee-sign"></i> Budget
                        </label>
                        <select class="form-select search-select" name="price">
                            <option value="">Any Price</option>
                            <option value="5000" {{ request('price') == '5000' ? 'selected' : '' }}>Under ₹5,000</option>
                            <option value="10000" {{ request('price') == '10000' ? 'selected' : '' }}>Under ₹10,000</option>
                            <option value="20000" {{ request('price') == '20000' ? 'selected' : '' }}>Under ₹20,000</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <button class="btn btn-search w-100">
                            <i class="fa-solid fa-paper-plane"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- ===== PACKAGES GRID ===== -->
<section class="packages-section">
    <div class="container">

        <!-- Trust Badges -->
        <div class="trust-badges">
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <div class="trust-item">
                        <div class="trust-icon">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <div class="trust-title">Verified Hotels</div>
                        <div class="trust-desc">Handpicked stays</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="trust-item">
                        <div class="trust-icon">
                            <i class="fa-solid fa-tag"></i>
                        </div>
                        <div class="trust-title">Best Price</div>
                        <div class="trust-desc">Guaranteed deals</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="trust-item">
                        <div class="trust-icon">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <div class="trust-title">24/7 Support</div>
                        <div class="trust-desc">Always available</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="trust-item">
                        <div class="trust-icon">
                            <i class="fa-solid fa-gem"></i>
                        </div>
                        <div class="trust-title">Premium Quality</div>
                        <div class="trust-desc">Top experiences</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Header -->
        <div class="section-header text-center">
            <span class="section-tag">
                <i class="fa-solid fa-fire"></i> Curated Experiences
            </span>
            <h2 class="section-title">Our Premium Packages</h2>
            <p class="section-desc">Handpicked destinations for an unforgettable journey through the Himalayas</p>
        </div>

        <div class="row g-4">
            @forelse($packages as $package)
            <div class="col-lg-4 col-md-6">
                <div class="package-card-premium h-100">
                    <!-- Image Container -->
                    <div class="package-image-wrap">
                        @if($package->thumbnail)
                        <img src="{{ asset('uploads/packages/'.$package->thumbnail) }}" alt="{{ $package->package_name }}" class="package-image" loading="lazy">
                        @else
                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&h=400&fit=crop" alt="Package" class="package-image" loading="lazy">
                        @endif

                        <div class="package-badge">
                            <i class="fa-solid fa-crown"></i> Premium
                        </div>

                        <!-- Optional: Show discount badge if you have original_price field -->
                        {{--
                        <div class="discount-badge">
                            <i class="fa-solid fa-percent"></i> 20% OFF
                        </div>
                        --}}

                        <div class="package-overlay">
                            <span class="image-meta-overlay">
                                <i class="fa-solid fa-location-dot"></i> {{ $package->destination }}
                            </span>
                            <a href="{{ route('package.details',$package->id) }}" class="btn-view" title="View Details">
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="package-body-premium">
                        <div class="package-meta">
                            <span class="meta-location">
                                <i class="fa-solid fa-location-dot"></i> {{ $package->destination }}
                            </span>
                            <span class="meta-duration">
                                <i class="fa-regular fa-clock"></i> {{ $package->duration }}
                            </span>
                        </div>

                        <h4 class="package-title">{{ $package->package_name }}</h4>

                        <p class="package-desc">
                            {!! \Illuminate\Support\Str::limit(strip_tags($package->description), 100) !!}
                        </p>

                        <!-- Optional Rating (uncomment if you have rating data) -->
                        {{--
                        <div class="package-rating">
                            <div class="rating-stars">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="rating-text">4.5 (128 reviews)</span>
                        </div>
                        --}}

                        <div class="package-footer-premium">
                            <div class="price-block">
                                <span class="price-label">Starting from</span>
                                <h5 class="price-value">
                                    ₹{{ number_format($package->price) }}
                                    <span>/ person</span>
                                    {{-- <span class="original-price">₹{{ number_format($package->price * 1.2) }}</span> --}}
                                </h5>
                            </div>
                            <a href="{{ route('package.details',$package->id) }}" class="btn-details">
                                View Details <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fa-solid fa-suitcase-rolling"></i>
                    </div>
                    <h4>No Packages Found</h4>
                    <p>Try adjusting your search filters or browse all destinations to find your perfect trip.</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="pagination-wrap">
            {{ $packages->links() }}
        </div>
    </div>
</section>

@endsection
