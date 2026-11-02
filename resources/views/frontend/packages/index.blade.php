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

        @forelse($packages as $package)
            <article class="mmt-list-card">
                <div class="mmt-img-wrap">
                    @if($package->thumbnail)
                        <img src="{{ asset('uploads/packages/'.$package->thumbnail) }}" alt="{{ $package->package_name }}" loading="lazy">
                    @else
                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&h=400&fit=crop" alt="Package">
                    @endif
                    @if ($package->duration)
                        <span class="mmt-badge">{{ $package->duration }}</span>
                    @endif
                </div>
                <div class="mmt-card-body">
                    <h4>{{ $package->package_name }}</h4>
                    <div class="mmt-meta">
                        <i class="fa-solid fa-location-dot"></i> {{ $package->destination }}
                        @if($package->duration)
                            &nbsp; · &nbsp; <i class="fa-regular fa-clock"></i> {{ $package->duration }}
                        @endif
                    </div>
                    <p>{!! \Illuminate\Support\Str::limit(strip_tags($package->description), 140) !!}</p>
                </div>
                <div class="mmt-card-price">
                    <span class="mmt-unit">Starting from</span>
                    <div class="mmt-price">₹{{ number_format($package->booking_price) }}</div>
                    <span class="mmt-unit">per person</span>
                    <a href="{{ route('package.details',$package->id) }}" class="mmt-book">BOOK NOW</a>
                </div>
            </article>
        @empty
            <div class="empty-state">
                <div class="empty-icon"><i class="fa-solid fa-suitcase-rolling"></i></div>
                <h4>No Packages Found</h4>
                <p>Try adjusting your search filters or browse all destinations to find your perfect trip.</p>
            </div>
        @endforelse

        <!-- Pagination -->
        <div class="pagination-wrap">
            {{ $packages->links() }}
        </div>
    </div>
</section>

@endsection
