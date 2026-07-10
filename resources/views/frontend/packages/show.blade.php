    @extends('frontend.layouts.app')

    @section('title',' Tour Package')

    @section('content')

    <section class="detail-hero">
        <div class="container">
            @php
                $mainImage = $package->thumbnail ? asset('uploads/packages/'.$package->thumbnail) : 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=600&fit=crop';
                $galleryImages = [];
                if(!empty($package->gallery_images)) {
                    if(is_string($package->gallery_images)) {
                        $decoded = json_decode($package->gallery_images, true);
                        if(is_array($decoded)) {
                            $galleryImages = $decoded;
                        }
                    } elseif(is_array($package->gallery_images)) {
                        $galleryImages = $package->gallery_images;
                    }
                }

                $allImages = [$mainImage];
                foreach($galleryImages as $img) {
                    $allImages[] = asset('uploads/packages/gallery/'.$img);
                }

                $hasGallery = count($galleryImages) > 0;
            @endphp

            <div class="detail-gallery {{ $hasGallery ? 'has-thumbs' : 'no-thumbs' }}">
                <div class="gallery-main">
                    <img src="{{ $mainImage }}" alt="{{ $package->package_name }}" id="mainGalleryImage" class="main-image">
                    <div class="gallery-overlay">
                        @if(count($allImages) > 1)
                        <button class="btn-gallery-nav prev" onclick="changeMainImage(-1)">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button class="btn-gallery-nav next" onclick="changeMainImage(1)">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                        @endif
                        @if(count($allImages) > 1)
                        <button class="btn-view-all" onclick="openLightbox()">
                            <i class="fa-solid fa-images"></i> View All Photos ({{ count($allImages) }})
                        </button>
                        @endif
                    </div>
                </div>

                @if($hasGallery)
                <div class="gallery-thumbs">
                    <div class="thumb-item active" onclick="setMainImage('{{ $mainImage }}',this)">
                        <img src="{{ $mainImage }}" alt="Main">
                    </div>
                    @foreach($galleryImages as $img)
                    <div class="thumb-item" onclick="setMainImage('{{ asset('uploads/packages/gallery/'.$img) }}',this)">
                        <img src="{{ asset('uploads/packages/gallery/'.$img) }}" alt="Gallery">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>

    <section class="detail-header">
        <div class="container">
            <div class="detail-header-inner">
                <div class="detail-header-left">
                    <nav class="detail-breadcrumb">
                        <a href="{{ route('home') }}">Home</a>
                        <i class="fa-solid fa-chevron-right"></i>
                        <a href="{{ route('packages') }}">Packages</a>
                        <i class="fa-solid fa-chevron-right"></i>
                        <span>{{ $package->package_name }}</span>
                    </nav>

                    <div class="detail-title-wrap">
                        <h1 class="detail-title">{{ $package->package_name }}</h1>
                        <div class="detail-badges">
                            @if($package->discount > 0)
                            <span class="detail-badge discount">
                                <i class="fa-solid fa-tag"></i> {{ $package->discount }}% OFF
                            </span>
                            @endif
                            @if($package->is_best_seller)
                            <span class="detail-badge bestseller">
                                <i class="fa-solid fa-fire"></i> Best Seller
                            </span>
                            @endif
                            <span class="detail-badge verified">
                                <i class="fa-solid fa-shield-halved"></i> Verified
                            </span>
                        </div>
                    </div>

                    <div class="detail-meta">
                        <span class="meta-item">
                            <i class="fa-solid fa-location-dot"></i> {{ $package->destination }}
                        </span>
                        <span class="meta-divider"></span>
                        <span class="meta-item">
                            <i class="fa-regular fa-clock"></i> {{ $package->duration }}
                        </span>
                        <span class="meta-divider"></span>
                        <span class="meta-item">
                            <i class="fa-solid fa-users"></i> Group: {{ $package->group_size ?? '2-15' }} People
                        </span>
                        <span class="meta-divider"></span>
                        <span class="meta-item rating">
                            @php $rating = $package->rating ?? 4.5; @endphp
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($rating))
                                    <i class="fa-solid fa-star"></i>
                                @elseif($i - 0.5 <= $rating)
                                    <i class="fa-solid fa-star-half-stroke"></i>
                                @else
                                    <i class="fa-regular fa-star"></i>
                                @endif
                            @endfor
                            <strong>{{ $rating }}</strong> ({{ $package->reviews_count ?? rand(50,500) }} reviews)
                        </span>
                    </div>
                </div>

                <div class="detail-header-right d-none d-lg-block">
                    <div class="price-card-sticky">
                        <div class="price-display">
                            @if($package->original_price > $package->price)
                            <span class="original-price">₹{{ number_format($package->original_price) }}</span>
                            @endif
                            <div class="current-price">
                                <span class="price-label">Starting from</span>
                                <span class="price-value">₹{{ number_format($package->price) }}</span>
                                <span class="price-per">per person</span>
                            </div>
                            @if($package->discount > 0)
                            <span class="save-tag">Save ₹{{ number_format($package->original_price - $package->price) }}</span>
                            @endif
                        </div>
                        <button class="btn-book-now" onclick="scrollToBooking()">
                            <i class="fa-solid fa-calendar-check"></i> Book Now
                        </button>
                        <div class="price-guarantee">
                            <i class="fa-solid fa-check-circle"></i> Best Price Guarantee
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="detail-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">

                    <div class="content-card highlights-card">
                        <h3 class="card-title"><i class="fa-solid fa-bolt"></i> Quick Highlights</h3>
                        <div class="highlights-grid">
                            <div class="highlight-item">
                                <div class="highlight-icon"><i class="fa-solid fa-hotel"></i></div>
                                <div class="highlight-text">
                                    <span class="highlight-label">Accommodation</span>
                                    <span class="highlight-value">3/4 Star Hotels</span>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon"><i class="fa-solid fa-utensils"></i></div>
                                <div class="highlight-text">
                                    <span class="highlight-label">Meals</span>
                                    <span class="highlight-value">Breakfast & Dinner</span>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon"><i class="fa-solid fa-bus"></i></div>
                                <div class="highlight-text">
                                    <span class="highlight-label">Transport</span>
                                    <span class="highlight-value">AC Vehicle</span>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon"><i class="fa-solid fa-camera"></i></div>
                                <div class="highlight-text">
                                    <span class="highlight-label">Sightseeing</span>
                                    <span class="highlight-value">All Major Spots</span>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon"><i class="fa-solid fa-user-tie"></i></div>
                                <div class="highlight-text">
                                    <span class="highlight-label">Guide</span>
                                    <span class="highlight-value">Expert Local Guide</span>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <div class="highlight-icon"><i class="fa-solid fa-headset"></i></div>
                                <div class="highlight-text">
                                    <span class="highlight-label">Support</span>
                                    <span class="highlight-value">24/7 Assistance</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content-card">
                        <h3 class="card-title"><i class="fa-solid fa-circle-info"></i> About This Package</h3>
                        <div class="package-description">
                            {!! $package->description !!}
                        </div>
                    </div>

                    <div class="content-card" id="itinerarySection">
                        <h3 class="card-title"><i class="fa-solid fa-route"></i> Itinerary</h3>
                        <div class="itinerary-timeline">
                            @php
                                $itineraryData = [];
                                if(!empty($package->itinerary)) {
                                    if(is_array($package->itinerary)) {
                                        $itineraryData = $package->itinerary;
                                    } elseif(is_string($package->itinerary)) {
                                        $decoded = json_decode($package->itinerary, true);
                                        if(is_array($decoded)) {
                                            $itineraryData = $decoded;
                                        } else {
                                            $lines = explode("\n", $package->itinerary);
                                            $currentDay = null;
                                            $currentDesc = [];

                                            foreach($lines as $line) {
                                                $line = trim($line);
                                                if(empty($line)) continue;

                                                if(preg_match('/^Day\s+(\d+)\s*[–\-–]\s*(.+)$/i', $line, $matches)) {
                                                    if($currentDay !== null) {
                                                        $itineraryData[] = [
                                                            'day' => 'Day ' . $currentDay['num'],
                                                            'title' => $currentDay['title'],
                                                            'description' => implode("\n", $currentDesc)
                                                        ];
                                                    }
                                                    $currentDay = ['num' => $matches[1], 'title' => $matches[2]];
                                                    $currentDesc = [];
                                                } elseif($currentDay !== null && !preg_match('/^\d+\s+Days/i', $line)) {
                                                    $currentDesc[] = $line;
                                                }
                                            }

                                            if($currentDay !== null) {
                                                $itineraryData[] = [
                                                    'day' => 'Day ' . $currentDay['num'],
                                                    'title' => $currentDay['title'],
                                                    'description' => implode("\n", $currentDesc)
                                                ];
                                            }
                                        }
                                    }
                                }

                                if(empty($itineraryData)) {
                                    $itineraryData = [
                                        ['day' => 'Day 1', 'title' => 'Arrival & Check-in', 'description' => 'Welcome to your destination. Check-in to hotel and rest. Evening local sightseeing.'],
                                        ['day' => 'Day 2', 'title' => 'Full Day Exploration', 'description' => 'Visit major attractions and enjoy local cuisine.'],
                                        ['day' => 'Day 3', 'title' => 'Adventure Activities', 'description' => 'Experience thrilling adventure activities and nature walks.'],
                                        ['day' => 'Day 4', 'title' => 'Departure', 'description' => 'After breakfast, check-out from hotel. Transfer to airport/railway station.']
                                    ];
                                }
                            @endphp

                            @foreach($itineraryData as $index => $day)
                            <div class="timeline-item">
                                <div class="timeline-marker">
                                    <span class="day-number">{{ $index + 1 }}</span>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-header" onclick="toggleItinerary(this)">
                                        <div class="timeline-title-wrap">
                                            <h4 class="timeline-title">{{ $day['title'] ?? 'Day ' . ($index + 1) }}</h4>
                                            <span class="timeline-day">{{ $day['day'] ?? 'Day ' . ($index + 1) }}</span>
                                        </div>
                                        <i class="fa-solid fa-chevron-down toggle-icon"></i>
                                    </div>
                                    <div class="timeline-body">
                                        <div class="timeline-desc">
                                            {!! nl2br(e($day['description'] ?? 'Detailed itinerary for this day.')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="content-card">
                        <div class="inex-tabs">
                            <button class="inex-tab active" onclick="switchInEx('inclusions',this)">
                                <i class="fa-solid fa-check-circle"></i> Inclusions
                            </button>
                            <button class="inex-tab" onclick="switchInEx('exclusions',this)">
                                <i class="fa-solid fa-times-circle"></i> Exclusions
                            </button>
                        </div>

                        @php
                            $inclusions = [];
                            if(!empty($package->inclusions)) {
                                if(is_array($package->inclusions)) {
                                    $inclusions = $package->inclusions;
                                } elseif(is_string($package->inclusions)) {
                                    $decoded = json_decode($package->inclusions, true);
                                    $inclusions = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode("\n", $package->inclusions)));
                                }
                            }
                            if(empty($inclusions)) {
                                $inclusions = ['Hotel accommodation on twin sharing basis','Daily breakfast and dinner','AC vehicle for all transfers & sightseeing','All applicable taxes','Professional tour guide','Entry fees to monuments'];
                            }

                            $exclusions = [];
                            if(!empty($package->exclusions)) {
                                if(is_array($package->exclusions)) {
                                    $exclusions = $package->exclusions;
                                } elseif(is_string($package->exclusions)) {
                                    $decoded = json_decode($package->exclusions, true);
                                    $exclusions = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode("\n", $package->exclusions)));
                                }
                            }
                            if(empty($exclusions)) {
                                $exclusions = ['Airfare / Train tickets','Personal expenses','Travel insurance','Lunch meals','Optional activities','Tips and gratuities'];
                            }
                        @endphp

                        <div class="inex-content" id="inclusionsContent">
                            <ul class="inex-list">
                                @foreach($inclusions as $item)
                                <li><i class="fa-solid fa-check"></i> {{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="inex-content hidden" id="exclusionsContent">
                            <ul class="inex-list exclusions">
                                @foreach($exclusions as $item)
                                <li><i class="fa-solid fa-times"></i> {{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="content-card">
                        <h3 class="card-title"><i class="fa-solid fa-circle-exclamation"></i> Important Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <i class="fa-solid fa-calendar-days"></i>
                                <div>
                                    <span class="info-label">Best Time</span>
                                    <span class="info-value">{{ $package->best_time ?? 'March - June, Sept - Nov' }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fa-solid fa-temperature-half"></i>
                                <div>
                                    <span class="info-label">Temperature</span>
                                    <span class="info-value">{{ $package->temperature ?? '15°C - 25°C' }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fa-solid fa-person-hiking"></i>
                                <div>
                                    <span class="info-label">Difficulty</span>
                                    <span class="info-value">{{ $package->difficulty ?? 'Easy to Moderate' }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fa-solid fa-language"></i>
                                <div>
                                    <span class="info-label">Languages</span>
                                    <span class="info-value">{{ $package->languages ?? 'Hindi, English, Local' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content-card" id="reviewsSection">
                        <h3 class="card-title"><i class="fa-solid fa-star"></i> Customer Reviews</h3>
                        @php $rating = $package->rating ?? 4.5; @endphp
                        <div class="reviews-summary">
                            <div class="rating-big">
                                <span class="big-number">{{ $rating }}</span>
                                <div class="big-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($rating))
                                            <i class="fa-solid fa-star"></i>
                                        @elseif($i - 0.5 <= $rating)
                                            <i class="fa-solid fa-star-half-stroke"></i>
                                        @else
                                            <i class="fa-regular fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="big-count">Based on {{ $package->reviews_count ?? rand(50,500) }} reviews</span>
                            </div>
                            <div class="rating-bars">
                                @php $ratingDistribution = [5 => 65, 4 => 20, 3 => 10, 2 => 3, 1 => 2]; @endphp
                                @foreach($ratingDistribution as $star => $percent)
                                <div class="bar-row">
                                    <span class="bar-label">{{ $star }} <i class="fa-solid fa-star"></i></span>
                                    <div class="bar-track">
                                        <div class="bar-fill" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="bar-percent">{{ $percent }}%</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="reviews-list">
                            @forelse($package->reviews ?? [] as $review)
                            <div class="review-card">
                                <div class="review-header">
                                    <div class="reviewer-avatar">{{ strtoupper(substr($review->user_name ?? 'U', 0, 1)) }}</div>
                                    <div class="reviewer-info">
                                        <h5>{{ $review->user_name ?? 'Happy Traveler' }}</h5>
                                        <span class="review-date">{{ $review->created_at ?? '2 weeks ago' }}</span>
                                    </div>
                                    <div class="review-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= ($review->rating ?? 5))
                                                <i class="fa-solid fa-star"></i>
                                            @else
                                                <i class="fa-regular fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <p class="review-text">{{ $review->comment ?? 'Amazing experience! The package was well organized.' }}</p>
                            </div>
                            @empty
                            <div class="review-card">
                                <div class="review-header">
                                    <div class="reviewer-avatar">R</div>
                                    <div class="reviewer-info">
                                        <h5>Rahul Sharma</h5>
                                        <span class="review-date">2 weeks ago</span>
                                    </div>
                                    <div class="review-rating">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                </div>
                                <p class="review-text">Amazing experience! The package was well organized and the guide was very knowledgeable. The hotels were clean and comfortable. Would definitely recommend to everyone!</p>
                            </div>
                            <div class="review-card">
                                <div class="review-header">
                                    <div class="reviewer-avatar">P</div>
                                    <div class="reviewer-info">
                                        <h5>Priya Patel</h5>
                                        <span class="review-date">1 month ago</span>
                                    </div>
                                    <div class="review-rating">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star-half-stroke"></i>
                                    </div>
                                </div>
                                <p class="review-text">Great value for money! The itinerary was perfectly planned. We got to see all the major attractions without feeling rushed.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="content-card">
                        <h3 class="card-title"><i class="fa-solid fa-circle-question"></i> Frequently Asked Questions</h3>
                        @php
                            $faqs = [];
                            if(!empty($package->faqs)) {
                                if(is_array($package->faqs)) {
                                    $faqs = $package->faqs;
                                } elseif(is_string($package->faqs)) {
                                    $decoded = json_decode($package->faqs, true);
                                    if(is_array($decoded)) {
                                        $faqs = $decoded;
                                    }
                                }
                            }
                            if(empty($faqs)) {
                                $faqs = [
                                    ['question' => 'What is the cancellation policy?', 'answer' => 'Free cancellation up to 7 days before the trip. 50% refund for cancellations 3-7 days before.'],
                                    ['question' => 'Is travel insurance included?', 'answer' => 'Basic travel insurance is included. We recommend purchasing additional coverage.'],
                                    ['question' => 'Are meals included?', 'answer' => 'Daily breakfast and dinner are included. Lunch is not included to give you flexibility.'],
                                    ['question' => 'What should I pack?', 'answer' => 'Comfortable clothing, walking shoes, sunscreen, personal medications, and a valid ID proof.'],
                                    ['question' => 'Is this package suitable for children?', 'answer' => 'Yes, this package is family-friendly. Children above 5 years are welcome.']
                                ];
                            }
                        @endphp
                        <div class="faq-accordion">
                            @foreach($faqs as $index => $faq)
                            <div class="faq-item">
                                <div class="faq-question" onclick="toggleFaq(this)">
                                    <span class="faq-q">Q{{ $index + 1 }}. {{ is_array($faq) ? ($faq['question'] ?? $faq['q'] ?? '') : $faq }}</span>
                                    <i class="fa-solid fa-plus faq-icon"></i>
                                </div>
                                <div class="faq-answer">
                                    <p>{{ is_array($faq) ? ($faq['answer'] ?? $faq['a'] ?? '') : '' }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="detail-sidebar">
                        <div class="sidebar-card booking-card" id="bookingCard">
                            <h4><i class="fa-solid fa-calendar-check"></i> Book This Package</h4>
                            <form action="" method="POST">
                                @csrf
                                <input type="hidden" name="package_id" value="{{ $package->id }}">

                                <div class="form-group">
                                    <label><i class="fa-regular fa-calendar"></i> Travel Date</label>
                                    <input type="date" class="form-control" name="travel_date" required min="{{ date('Y-m-d') }}">
                                </div>

                                <div class="form-group">
                                    <label><i class="fa-solid fa-users"></i> Number of Travelers</label>
                                    <div class="traveler-counter">
                                        <button type="button" class="counter-btn" onclick="updateTravelers(-1)">-</button>
                                        <input type="number" name="travelers" value="2" min="1" max="20" id="travelerCount" readonly>
                                        <button type="button" class="counter-btn" onclick="updateTravelers(1)">+</button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><i class="fa-solid fa-user"></i> Full Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter your name" required>
                                </div>

                                <div class="form-group">
                                    <label><i class="fa-solid fa-phone"></i> Phone Number</label>
                                    <input type="tel" class="form-control" name="phone" placeholder="+91 98765 43210" required>
                                </div>

                                <div class="form-group">
                                    <label><i class="fa-solid fa-envelope"></i> Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="your@email.com" required>
                                </div>

                                <div class="price-calc">
                                    <div class="calc-row">
                                        <span>Package Price (per person)</span>
                                        <span>₹{{ number_format($package->price) }}</span>
                                    </div>
                                    <div class="calc-row">
                                        <span>Travelers</span>
                                        <span id="calcTravelers">x 2</span>
                                    </div>
                                    @if($package->discount > 0)
                                    <div class="calc-row discount">
                                        <span>Discount ({{ $package->discount }}%)</span>
                                        <span id="calcDiscount">-₹{{ number_format(($package->price * $package->discount / 100) * 2) }}</span>
                                    </div>
                                    @endif
                                    <div class="calc-row total">
                                        <span>Total Amount</span>
                                        <span id="calcTotal">₹{{ number_format($package->price * 2) }}</span>
                                    </div>
                                </div>

                                <button type="submit" class="btn-book-submit">
                                    <i class="fa-solid fa-paper-plane"></i> Confirm Booking
                                </button>

                                <div class="booking-trust">
                                    <span><i class="fa-solid fa-lock"></i> Secure Payment</span>
                                    <span><i class="fa-solid fa-rotate-left"></i> Free Cancellation</span>
                                </div>
                            </form>
                        </div>

                        <div class="sidebar-card">
                            <h4><i class="fa-solid fa-thumbs-up"></i> Why Book With Us?</h4>
                            <ul class="why-us-list">
                                <li><i class="fa-solid fa-check"></i> Best Price Guarantee</li>
                                <li><i class="fa-solid fa-check"></i> 24/7 Customer Support</li>
                                <li><i class="fa-solid fa-check"></i> Verified Local Guides</li>
                                <li><i class="fa-solid fa-check"></i> Free Cancellation</li>
                                <li><i class="fa-solid fa-check"></i> No Hidden Charges</li>
                                <li><i class="fa-solid fa-check"></i> Customizable Packages</li>
                            </ul>
                        </div>

                        <div class="sidebar-card help-card">
                            <h4><i class="fa-solid fa-headset"></i> Need Help?</h4>
                            <p>Our travel experts are here to assist you 24/7</p>
                            <a href="tel:+919876543210" class="help-phone">
                                <i class="fa-solid fa-phone"></i> +91 98765 43210
                            </a>
                            <a href="https://wa.me/919876543210" class="help-whatsapp" target="_blank">
                                <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($relatedPackages->count() > 0)
    <section class="related-section">
        <div class="container">
            <div class="section-header text-center">
                <span class="section-tag">More Options</span>
                <h2 class="section-title">Similar Packages You May Like</h2>
            </div>

            <div class="related-carousel" id="relatedCarousel">
                <button class="carousel-btn prev" onclick="scrollRelated(-1)">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>

                <div class="related-track">
                    @foreach($relatedPackages as $related)
                    <div class="related-card">
                        <div class="related-image">
                            @if($related->thumbnail)
                            <img src="{{ asset('uploads/packages/'.$related->thumbnail) }}" alt="{{ $related->package_name }}">
                            @else
                            <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=250&fit=crop" alt="{{ $related->package_name }}">
                            @endif
                            <span class="related-duration"><i class="fa-regular fa-clock"></i> {{ $related->duration }}</span>
                        </div>
                        <div class="related-body">
                            <span class="related-location"><i class="fa-solid fa-location-dot"></i> {{ $related->destination }}</span>
                            <h4 class="related-title">{{ $related->package_name }}</h4>
                            <div class="related-footer">
                                <span class="related-price">₹{{ number_format($related->price) }}</span>
                                <a href="{{ route('package.details', $related->id) }}" class="btn-related">View <i class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <button class="carousel-btn next" onclick="scrollRelated(1)">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>
    @endif

    <!-- ===== MOBILE STICKY BOOKING BAR ===== -->
    <div class="mobile-booking-bar d-lg-none">
        <div class="mobile-price">
            <span class="mobile-label">Starting from</span>
            <span class="mobile-value">₹{{ number_format($package->price) }}</span>
            <span class="mobile-per">/person</span>
        </div>
        <button class="btn-mobile-book" onclick="scrollToBooking()">
            <i class="fa-solid fa-calendar-check"></i> Book Now
        </button>
    </div>

    <!-- ===== LIGHTBOX ===== -->
    <div class="lightbox" id="lightbox">
        <button class="lightbox-close" onclick="closeLightbox()"><i class="fa-solid fa-xmark"></i></button>
        <button class="lightbox-nav prev" onclick="lightboxNav(-1)"><i class="fa-solid fa-chevron-left"></i></button>
        <img src="" alt="Gallery" id="lightboxImage">
        <button class="lightbox-nav next" onclick="lightboxNav(1)"><i class="fa-solid fa-chevron-right"></i></button>
        <div class="lightbox-counter"><span id="lightboxCurrent">1</span> / <span id="lightboxTotal">1</span></div>
    </div>

    @endsection

    @push('scripts')
    <script>
    // ===== GALLERY =====
    let currentImageIndex = 0;
    let galleryImages = [];

    // Collect all images from the page
    document.querySelectorAll('.thumb-item img').forEach(img => {
        galleryImages.push(img.src);
    });

    // If no thumbs, use main image only
    if(galleryImages.length === 0) {
        const mainImg = document.getElementById('mainGalleryImage');
        if(mainImg) galleryImages.push(mainImg.src);
    }

    function setMainImage(src, thumb) {
        const mainImg = document.getElementById('mainGalleryImage');
        if(mainImg) mainImg.src = src;
        document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
        if(thumb) thumb.classList.add('active');
    }

    function changeMainImage(direction) {
        if(galleryImages.length <= 1) return;
        const thumbs = document.querySelectorAll('.thumb-item');
        let active = 0;
        thumbs.forEach((t, i) => { if(t.classList.contains('active')) active = i; });
        const next = (active + direction + thumbs.length) % thumbs.length;
        if(thumbs[next]) thumbs[next].click();
    }

    // ===== LIGHTBOX =====
    function openLightbox() {
        if(galleryImages.length === 0) return;
        const lightbox = document.getElementById('lightbox');
        const mainImg = document.getElementById('mainGalleryImage');
        currentImageIndex = galleryImages.indexOf(mainImg?.src) || 0;
        if(currentImageIndex < 0) currentImageIndex = 0;

        const lbImg = document.getElementById('lightboxImage');
        if(lbImg) lbImg.src = galleryImages[currentImageIndex];

        const curr = document.getElementById('lightboxCurrent');
        const total = document.getElementById('lightboxTotal');
        if(curr) curr.textContent = currentImageIndex + 1;
        if(total) total.textContent = galleryImages.length;

        lightbox.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('show');
        document.body.style.overflow = '';
    }

    function lightboxNav(direction) {
        if(galleryImages.length <= 1) return;
        currentImageIndex = (currentImageIndex + direction + galleryImages.length) % galleryImages.length;
        const lbImg = document.getElementById('lightboxImage');
        if(lbImg) lbImg.src = galleryImages[currentImageIndex];
        const curr = document.getElementById('lightboxCurrent');
        if(curr) curr.textContent = currentImageIndex + 1;
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        const lightbox = document.getElementById('lightbox');
        if(!lightbox || !lightbox.classList.contains('show')) return;
        if(e.key === 'Escape') closeLightbox();
        if(e.key === 'ArrowLeft') lightboxNav(-1);
        if(e.key === 'ArrowRight') lightboxNav(1);
    });

    // ===== ITINERARY TOGGLE =====
    function toggleItinerary(header) {
        const body = header.nextElementSibling;
        const icon = header.querySelector('.toggle-icon');
        if(!body) return;
        body.classList.toggle('open');
        if(icon) icon.style.transform = body.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0)';
    }

    // ===== INCLUSIONS/EXCLUSIONS TABS =====
    function switchInEx(type, btn) {
        document.querySelectorAll('.inex-tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');

        const inc = document.getElementById('inclusionsContent');
        const exc = document.getElementById('exclusionsContent');
        if(inc) inc.classList.toggle('hidden', type !== 'inclusions');
        if(exc) exc.classList.toggle('hidden', type !== 'exclusions');
    }

    // ===== FAQ ACCORDION =====
    function toggleFaq(question) {
        const item = question.closest('.faq-item');
        if(!item) return;
        const answer = item.querySelector('.faq-answer');
        const icon = item.querySelector('.faq-icon');
        if(!answer || !icon) return;

        const isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';

        // Close all
        document.querySelectorAll('.faq-answer').forEach(a => a.style.maxHeight = null);
        document.querySelectorAll('.faq-icon').forEach(i => {
            i.classList.remove('fa-minus');
            i.classList.add('fa-plus');
        });

        if(!isOpen) {
            answer.style.maxHeight = answer.scrollHeight + 'px';
            icon.classList.remove('fa-plus');
            icon.classList.add('fa-minus');
        }
    }

    // ===== TRAVELER COUNTER =====
    function updateTravelers(change) {
        const input = document.getElementById('travelerCount');
        if(!input) return;
        let val = parseInt(input.value) + change;
        val = Math.max(1, Math.min(20, val));
        input.value = val;

        const calcTravelers = document.getElementById('calcTravelers');
        if(calcTravelers) calcTravelers.textContent = 'x ' + val;

        const price = {{ $package->price }};
        const discount = {{ $package->discount ?? 0 }};
        let total = price * val;

        if(discount > 0) {
            const discAmount = (price * discount / 100) * val;
            const calcDiscount = document.getElementById('calcDiscount');
            if(calcDiscount) calcDiscount.textContent = '-₹' + discAmount.toLocaleString('en-IN');
            total -= discAmount;
        }

        const calcTotal = document.getElementById('calcTotal');
        if(calcTotal) calcTotal.textContent = '₹' + total.toLocaleString('en-IN');
    }

    // ===== SCROLL TO BOOKING =====
    function scrollToBooking() {
        const booking = document.getElementById('bookingCard');
        if(booking) {
            booking.scrollIntoView({ behavior: 'smooth', block: 'center' });
            booking.classList.add('highlight');
            setTimeout(() => booking.classList.remove('highlight'), 2000);
        }
    }

    // ===== RELATED CAROUSEL =====
    function scrollRelated(direction) {
        const track = document.querySelector('.related-track');
        if(!track) return;
        const cardWidth = 320;
        track.scrollBy({ left: direction * cardWidth, behavior: 'smooth' });
    }

    // ===== ANIMATIONS =====
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.content-card, .related-card').forEach(el => {
        observer.observe(el);
    });
    </script>
    @endpush
