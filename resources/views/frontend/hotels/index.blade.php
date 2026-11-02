@extends('frontend.layouts.app')

@section('title', 'Hotels')

@section('content')

<!-- ===== HERO BANNER ===== -->
<section class="hotels-hero">
    <div class="hero-bg">
        <div class="hero-pattern"></div>
    </div>
    <div class="container hero-content">
        <div class="hero-tag">
            <span class="tag-line"></span>
            <span>Luxury Stays</span>
        </div>
        <h1 class="hero-title">Find Your Perfect <em>Hotel</em></h1>
        <p class="hero-subtitle">Handpicked premium hotels across the Himalayas</p>
        <div class="hero-divider">
            <span></span>
            <i class="fa-solid fa-mountain-sun"></i>
            <span></span>
        </div>
    </div>
</section>

<!-- ===== FILTER BAR (Floating Card - Same as Packages Search) ===== -->
<section class="filter-bar">
    <div class="container">
        <div class="filter-wrapper">
            <form action="{{ route('hotels') }}" method="GET" class="filter-form" id="hotelFilterForm">
                <div class="filter-group">
                    <label><i class="fa-solid fa-magnifying-glass"></i> Search Hotel</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Hotel name or location..." id="searchInput">
                </div>
                <div class="filter-group">
                    <label><i class="fa-solid fa-location-dot"></i> Location</label>
                    <select name="location" id="locationSelect">
                        <option value="">All Cities</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>
                                {{ $loc }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="fa-solid fa-tag"></i> Budget</label>
                    <select name="price" id="priceSelect">
                        <option value="">Any Price</option>
                        <option value="1000" {{ request('price') == '1000' ? 'selected' : '' }}>Under ₹1,000</option>
                        <option value="3000" {{ request('price') == '3000' ? 'selected' : '' }}>Under ₹3,000</option>
                        <option value="5000" {{ request('price') == '5000' ? 'selected' : '' }}>Under ₹5,000</option>
                        <option value="10000" {{ request('price') == '10000' ? 'selected' : '' }}>Under ₹10,000</option>
                    </select>
                </div>
                <button type="submit" class="filter-btn">
                    <i class="fa-solid fa-paper-plane"></i> Search
                </button>
                @if(request()->has('search') || request()->has('location') || request()->has('price'))
                    <a href="{{ route('hotels') }}" class="filter-reset" title="Clear Filters">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </form>
        </div>
    </div>
</section>

<!-- ===== HOTELS GRID ===== -->
<section class="hotels-grid">
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

        <!-- Results Info -->
        <div class="results-bar">
            <div class="results-count">
                <span class="count-number">{{ $hotels->total() }}</span>
                <span class="count-label">properties found</span>
                @if(request('location'))
                    <span class="count-filter">in <strong>{{ request('location') }}</strong></span>
                @endif
            </div>
            <div class="results-sort">
                <span>Sort by:</span>
                <select onchange="window.location.href=this.value">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'recommended']) }}" {{ request('sort') == 'recommended' || !request('sort') ? 'selected' : '' }}>Recommended</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'rating']) }}" {{ request('sort') == 'rating' ? 'selected' : '' }}>Top Rated</option>
                </select>
            </div>
        </div>

        @if(request('search') || request('location') || request('price'))
            <div class="active-filters">
                <span class="filters-label">Active Filters:</span>
                @if(request('search'))
                    <span class="filter-chip">
                        Search: "{{ request('search') }}"
                        <a href="{{ route('hotels', array_diff_key(request()->except('page'), ['search' => ''])) }}"><i class="fa-solid fa-xmark"></i></a>
                    </span>
                @endif
                @if(request('location'))
                    <span class="filter-chip">
                        Location: {{ request('location') }}
                        <a href="{{ route('hotels', array_diff_key(request()->except('page'), ['location' => ''])) }}"><i class="fa-solid fa-xmark"></i></a>
                    </span>
                @endif
                @if(request('price'))
                    <span class="filter-chip">
                        Price: Under ₹{{ number_format(request('price')) }}
                        <a href="{{ route('hotels', array_diff_key(request()->except('page'), ['price' => ''])) }}"><i class="fa-solid fa-xmark"></i></a>
                    </span>
                @endif
                <a href="{{ route('hotels') }}" class="clear-all">Clear All</a>
            </div>
        @endif

        <!-- Section Header -->
        <div class="section-header text-center">
            <span class="section-tag">
                <i class="fa-solid fa-fire"></i> Premium Stays
            </span>
            <h2 class="section-title">Our Best Hotels</h2>
            <p class="section-desc">Handpicked hotels for an unforgettable stay in the Himalayas</p>
        </div>

        @forelse($hotels as $hotel)
            @php
                $nightPrice = $hotel->price ?? $hotel->starting_price ?? 0;
                $originalPrice = $nightPrice * 1.3;
            @endphp
            <article class="mmt-hotel-card">
                <div class="mmt-img-wrap">
                    @if($hotel->thumbnail)
                        <img src="{{ asset('uploads/hotels/'.$hotel->thumbnail) }}" alt="{{ $hotel->hotel_name }}" loading="lazy">
                    @else
                        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&h=400&fit=crop" alt="{{ $hotel->hotel_name }}">
                    @endif
                </div>
                <div class="mmt-card-body">
                    <h4>{{ $hotel->hotel_name }}</h4>
                    <div class="mmt-meta"><i class="fa-solid fa-location-dot"></i> {{ $hotel->location }}</div>
                    <p>{!! \Illuminate\Support\Str::limit(strip_tags($hotel->description), 120) !!}</p>
                    <div class="mmt-amenities">
                        <span>WiFi</span><span>Parking</span><span>Restaurant</span>
                    </div>
                </div>
                <div class="mmt-card-price">
                    <div class="mmt-rating">{{ number_format($hotel->rating ?? 4.5, 1) }}/5</div>
                    @if($originalPrice > 0)
                        <div class="mmt-old">₹{{ number_format($originalPrice) }}</div>
                    @endif
                    <div class="mmt-price">₹{{ number_format($nightPrice) }}</div>
                    <span class="mmt-unit">per night + taxes</span>
                    <a href="{{ route('hotel.details', $hotel->id) }}" class="mmt-book">BOOK NOW</a>
                </div>
            </article>
        @empty
            <div class="empty-state">
                <div class="empty-icon"><i class="fa-solid fa-hotel"></i></div>
                <h4>No Hotels Found</h4>
                <p>Try adjusting your search filters or browse all locations to find your perfect stay.</p>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($hotels->hasPages())
        <div class="pagination-wrap">
            {{ $hotels->appends(request()->except('page'))->links() }}
        </div>
        @endif
    </div>
</section>

<!-- ===== NEWSLETTER ===== -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-box">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h3>Get Exclusive Hotel Deals</h3>
                    <p>Subscribe to receive special offers and discounts directly in your inbox.</p>
                </div>
                <div class="col-lg-5">
                    <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Thank you for subscribing!');">
                        <input type="email" placeholder="Enter your email" required>
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.getElementById('locationSelect').addEventListener('change', function() {
        document.getElementById('hotelFilterForm').submit();
    });

    document.getElementById('priceSelect').addEventListener('change', function() {
        document.getElementById('hotelFilterForm').submit();
    });

    document.getElementById('hotelFilterForm').addEventListener('submit', function() {
        const btn = this.querySelector('.filter-btn');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Searching...';
    });
</script>

@endsection
