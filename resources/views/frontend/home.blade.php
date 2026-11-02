@extends('frontend.layouts.app')

@section('title', 'Book Hotels & Holiday Packages in Uttarakhand')

@section('content')

<section class="mmt-home-hero">
    <div class="container">
        <h1>Book Hotels, Holiday Packages & more</h1>
        <p class="lead-copy">Find stays and Himalayan holiday packages across Uttarakhand — Nainital, Rishikesh, Mussoorie, Kedarnath and more.</p>

        <div class="mmt-search-card">
            <div class="mmt-search-tabs">
                <button type="button" class="mmt-search-tab active" data-tab="hotels">Hotels</button>
                <button type="button" class="mmt-search-tab" data-tab="holidays">Holiday Packages</button>
            </div>

            <form action="{{ route('hotels') }}" method="GET" class="mmt-search-body active" id="mmtHotelSearch">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 mmt-field">
                        <label>City / Hotel / Location</label>
                        <input type="text" name="search" placeholder="e.g. Nainital, Mussoorie" value="{{ request('search') }}">
                    </div>
                    <div class="col-lg-3 mmt-field">
                        <label>Location</label>
                        <select name="location">
                            <option value="">All cities</option>
                            @foreach($locations as $loc)
                                @if($loc)
                                    <option value="{{ $loc }}">{{ $loc }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 mmt-field">
                        <label>Budget per night</label>
                        <select name="price">
                            <option value="">Any price</option>
                            <option value="1000">Under ₹1,000</option>
                            <option value="3000">Under ₹3,000</option>
                            <option value="5000">Under ₹5,000</option>
                            <option value="10000">Under ₹10,000</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <button type="submit" class="mmt-search-btn">SEARCH</button>
                    </div>
                </div>
            </form>

            <form action="{{ route('packages') }}" method="GET" class="mmt-search-body" id="mmtHolidaySearch">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 mmt-field">
                        <label>Package name</label>
                        <input type="text" name="search" placeholder="e.g. Kedarnath Yatra">
                    </div>
                    <div class="col-lg-3 mmt-field">
                        <label>Destination</label>
                        <select name="destination">
                            <option value="">All destinations</option>
                            @foreach($destinations as $dest)
                                @if($dest)
                                    <option value="{{ $dest }}">{{ $dest }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 mmt-field">
                        <label>Budget</label>
                        <select name="price">
                            <option value="">Any price</option>
                            <option value="5000">Under ₹5,000</option>
                            <option value="10000">Under ₹10,000</option>
                            <option value="20000">Under ₹20,000</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <button type="submit" class="mmt-search-btn">SEARCH</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mmt-offer-strip">
            <span class="mmt-chip"><i class="fa-solid fa-tag"></i> Best hotel deals</span>
            <span class="mmt-chip"><i class="fa-solid fa-mountain-sun"></i> Char Dham packages</span>
            <span class="mmt-chip"><i class="fa-solid fa-shield-halved"></i> Verified stays</span>
            <span class="mmt-chip"><i class="fa-solid fa-headset"></i> 24x7 booking support</span>
        </div>
    </div>
</section>

<section id="stats-section">
    <div class="container">
        <div class="mmt-stat-row">
            <div class="row g-0">
                @foreach($homeStats as $stat)
                <div class="col-6 col-md-3">
                    <div class="stat-item mmt-stat">
                        <div class="stat-number" data-count="{{ $stat['count'] }}">0</div>
                        <div class="stat-label">{{ $stat['label'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="mmt-section">
    <div class="container">
        <div class="mmt-section-head">
            <div>
                <h2>Hotels in Uttarakhand</h2>
                <p>Compare stays, ratings and starting prices</p>
            </div>
            <a href="{{ route('hotels') }}" class="mmt-link">VIEW ALL</a>
        </div>

        @forelse($featuredHotels as $hotel)
            <article class="mmt-hotel-card">
                <div class="mmt-img-wrap">
                    @if ($hotel->thumbnail)
                        <img src="{{ asset('uploads/hotels/' . $hotel->thumbnail) }}" alt="{{ $hotel->hotel_name }}">
                    @else
                        <div class="home-image-placeholder"><i class="fa-solid fa-hotel"></i></div>
                    @endif
                </div>
                <div class="mmt-card-body">
                    <h4>{{ $hotel->hotel_name }}</h4>
                    <div class="mmt-meta">
                        <i class="fa-solid fa-location-dot"></i> {{ $hotel->location }}
                    </div>
                    @if ($hotel->amenities->isNotEmpty())
                        <div class="mmt-amenities">
                            @foreach ($hotel->amenities->take(4) as $amenity)
                                <span>{{ $amenity->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="mmt-card-price">
                    @if ((float) $hotel->rating > 0)
                        <div class="mmt-rating">{{ number_format($hotel->rating, 1) }}/5</div>
                    @endif
                    <span class="mmt-unit">Starting from</span>
                    <div class="mmt-price">₹{{ number_format($hotel->starting_price ?? 0) }}</div>
                    <span class="mmt-unit">per night</span>
                    <a href="{{ route('hotel.details', $hotel->id) }}" class="mmt-book">BOOK NOW</a>
                </div>
            </article>
        @empty
            <div class="alert alert-light text-center py-5">No hotels found yet.</div>
        @endforelse
    </div>
</section>

<section class="mmt-section" style="background:#fff;">
    <div class="container">
        <div class="mmt-section-head">
            <div>
                <h2>Holiday packages</h2>
                <p>Pilgrimage, hills and complete Uttarakhand trips</p>
            </div>
            <a href="{{ route('packages') }}" class="mmt-link">VIEW ALL</a>
        </div>
        <div class="row g-4">
            @forelse($featuredPackages as $package)
                <div class="col-lg-4 col-md-6">
                    <div class="mmt-grid-card">
                        <div class="mmt-img-wrap">
                            @if ($package->thumbnail)
                                <img src="{{ asset('uploads/packages/' . $package->thumbnail) }}" alt="{{ $package->package_name }}">
                            @else
                                <div class="home-image-placeholder"><i class="fa-solid fa-mountain-sun"></i></div>
                            @endif
                            @if ($package->duration)
                                <span class="mmt-badge">{{ $package->duration }}</span>
                            @endif
                            @if ($package->price > 0 && $package->booking_price < $package->price)
                                <span class="mmt-off">{{ (int) round((1 - ($package->booking_price / $package->price)) * 100) }}% OFF</span>
                            @endif
                        </div>
                        <div class="mmt-card-body">
                            <h4>{{ $package->package_name }}</h4>
                            <div class="mmt-meta"><i class="fa-solid fa-location-dot"></i> {{ $package->destination }}</div>
                            <p>{{ Str::limit($package->description, 90) }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div>
                                    <div class="mmt-unit">Per person</div>
                                    <div class="mmt-price" style="font-size:22px;">₹{{ number_format($package->booking_price) }}</div>
                                </div>
                                <a href="{{ route('package.details', $package->id) }}" class="mmt-book">VIEW</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><div class="alert alert-light text-center py-5">No packages found yet.</div></div>
            @endforelse
        </div>
    </div>
</section>

<section id="features-section" class="mmt-section mmt-why">
    <div class="container">
        <div class="mmt-section-head">
            <div>
                <h2>Why book with us</h2>
                <p>Simple booking, clear prices, Uttarakhand-focused stays and trips</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="feature-box p-4 h-100">
                    <div class="feature-icon"><i class="fa-solid fa-hotel"></i></div>
                    <h5>Verified hotels</h5>
                    <p>Browse stays with location, amenities and starting prices in one view.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box p-4 h-100">
                    <div class="feature-icon"><i class="fa-solid fa-route"></i></div>
                    <h5>Holiday packages</h5>
                    <p>Compare destinations, duration and per-person package prices.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box p-4 h-100">
                    <div class="feature-icon"><i class="fa-solid fa-tag"></i></div>
                    <h5>Clear pricing</h5>
                    <p>See the amount before checkout. No hidden steps in the booking flow.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box p-4 h-100">
                    <div class="feature-icon"><i class="fa-solid fa-headset"></i></div>
                    <h5>Trip support</h5>
                    <p>Reach the team from Contact if you need help with a booking.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="gallery-section" class="mmt-section">
    <div class="container">
        <div class="mmt-section-head">
            <div>
                <h2>Travel gallery</h2>
                <p>Moments from hills, temples and lakes of Uttarakhand</p>
            </div>
            <a href="{{ route('gallery') }}" class="mmt-link">SEE MORE</a>
        </div>
        <div class="row g-3">
            @forelse($galleries as $gallery)
                @php
                    $images = is_array($gallery->image) ? $gallery->image : json_decode($gallery->image, true);
                    $images = is_array($images) ? $images : [];
                @endphp
                @if(!empty($images))
                    @foreach($images as $image)
                        <div class="col-lg-3 col-md-4 col-6">
                            <div class="gallery-card">
                                <img src="{{ asset('uploads/gallery/'.$image) }}" class="gallery-image" alt="{{ $gallery->title ?? 'Uttarakhand' }}" loading="lazy">
                            </div>
                        </div>
                    @endforeach
                @endif
            @empty
                <div class="col-12"><div class="alert alert-light text-center py-5">Gallery photos will appear here.</div></div>
            @endforelse
        </div>
    </div>
</section>

<section id="testimonials-section" class="mmt-section" style="background:#fff;">
    <div class="container">
        <div class="mmt-section-head">
            <div>
                <h2>Traveller reviews</h2>
                <p>What guests say after their Uttarakhand trip</p>
            </div>
        </div>
        <div class="row g-4">
            @forelse($testimonials as $testimonial)
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card p-4 h-100">
                        <div class="testimonial-stars mb-2">
                            @if ((float) $testimonial->rating > 0)
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $testimonial->rating)
                                        <i class="fa-solid fa-star"></i>
                                    @else
                                        <i class="fa-regular fa-star"></i>
                                    @endif
                                @endfor
                            @endif
                        </div>
                        <p>{{ $testimonial->message }}</p>
                        <div class="d-flex align-items-center gap-3 mt-3">
                            @if ($testimonial->image)
                                <img src="{{ asset('uploads/testimonials/' . $testimonial->image) }}" class="testimonial-avatar rounded-circle" width="44" height="44" alt="{{ $testimonial->name }}">
                            @endif
                            <div>
                                <strong>{{ $testimonial->name }}</strong>
                                <div class="mmt-unit">{{ $testimonial->designation ?? 'Traveller' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><div class="alert alert-light text-center py-5">Reviews will appear here.</div></div>
            @endforelse
        </div>
    </div>
</section>

<section id="cta-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2>Ready to plan your Uttarakhand trip?</h2>
                <p>Search hotels and holiday packages, then book in a few steps.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('hotels') }}" id="btn-cta" class="mmt-book">SEARCH HOTELS</a>
            </div>
        </div>
    </div>
</section>

<script>
    document.querySelectorAll('.mmt-search-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.mmt-search-tab').forEach(function (t) { t.classList.remove('active'); });
            document.querySelectorAll('.mmt-search-body').forEach(function (b) { b.classList.remove('active'); });
            tab.classList.add('active');
            var id = tab.dataset.tab === 'holidays' ? 'mmtHolidaySearch' : 'mmtHotelSearch';
            document.getElementById(id).classList.add('active');
        });
    });

    function animateCounter(el) {
        const target = parseFloat(el.getAttribute('data-count'));
        const duration = 1400;
        const increment = target / (duration / 16);
        let current = 0;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            el.textContent = Math.floor(current).toLocaleString();
        }, 16);
    }

    document.querySelectorAll('.stat-number').forEach(function (el) {
        animateCounter(el);
    });
</script>

@endsection
