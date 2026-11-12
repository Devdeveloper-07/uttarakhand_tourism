@extends('frontend.layouts.app')

@section('title', $hotel->hotel_name)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

@section('content')

{{-- ===================== HERO / SINGLE IMAGE ===================== --}}
<section class="mmt-hero" id="mmtHero">
    <div class="mmt-hero-top">
        <div class="container" id="mmtHeroContainer">
            <div class="mmt-breadcrumb" id="mmtBreadcrumb">
                <a href="{{ route('home') }}" id="mmtBreadcrumbHome">Home</a>
                <i class="fa-solid fa-chevron-right" id="mmtBreadcrumbSep"></i>
                <a href="{{ route('hotels') }}" id="mmtBreadcrumbHotels">Hotels</a>
                <i class="fa-solid fa-chevron-right" id="mmtBreadcrumbSep2"></i>
                <span id="mmtBreadcrumbCurrent">{{ $hotel->hotel_name }}</span>
            </div>
            <div class="mmt-hero-header" id="mmtHeroHeader">
                <div class="mmt-hotel-info" id="mmtHotelInfo">
                    <h1 id="mmtHotelName">{{ $hotel->hotel_name }}</h1>
                    <div class="mmt-hotel-meta" id="mmtHotelMeta">
                        <span class="mmt-star-badge" id="mmtStarBadge">
                            <i class="fa-solid fa-star" id="mmtStarIcon"></i> {{ $hotel->star_rating ?? '4' }} Star
                        </span>
                        <span class="mmt-location-text" id="mmtLocationText">
                            <i class="fa-solid fa-location-dot" id="mmtLocationIcon"></i>
                            {{ $hotel->location }}@if($hotel->city), {{ $hotel->city }}@endif
                        </span>
                    </div>
                </div>
                <div class="mmt-hotel-actions" id="mmtHotelActions">
                    <button class="mmt-action-btn" id="mmtShareBtn" onclick="shareHotel()">
                        <i class="fa-solid fa-share-nodes"></i> Share
                    </button>
                    <button class="mmt-action-btn mmt-save-btn" id="mmtSaveBtn" onclick="toggleSave(this)">
                        <i class="fa-regular fa-heart"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Single Image --}}
    <div class="mmt-gallery-wrap" id="mmtGalleryWrap">
        <div class="container" id="mmtGalleryContainer">
            <div class="mmt-single-image" id="mmtSingleImage">
                @if($hotel->thumbnail)
                    <img src="{{ asset('uploads/hotels/'.$hotel->thumbnail) }}" alt="{{ $hotel->hotel_name }}" id="mmtMainImage" loading="eager">
                @else
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&h=700&fit=crop" alt="{{ $hotel->hotel_name }}" id="mmtMainImage">
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ===================== STICKY BOOKING BAR ===================== --}}
<div class="mmt-sticky-bar" id="mmtStickyBar">
    <div class="container" id="mmtStickyContainer">
        <div class="mmt-sticky-inner" id="mmtStickyInner">
            <div class="mmt-sticky-info" id="mmtStickyInfo">
                <div class="mmt-sticky-price" id="mmtStickyPrice">
                    <span class="mmt-sticky-old" id="mmtStickyOld"><s>₹{{ number_format(($hotel->starting_price ?? $hotel->price ?? 0) * 1.4) }}</s></span>
                    <span class="mmt-sticky-curr" id="mmtStickyCurr">₹{{ number_format($hotel->starting_price ?? $hotel->price ?? 0) }}</span>
                    <span class="mmt-sticky-unit" id="mmtStickyUnit">/ night</span>
                </div>
                <div class="mmt-sticky-rating" id="mmtStickyRating">
                    <span class="mmt-sticky-score" id="mmtStickyScore">{{ number_format($hotel->rating ?? 4.5, 1) }}</span>
                    <span class="mmt-sticky-reviews" id="mmtStickyReviews">(2,847 reviews)</span>
                </div>
            </div>
            <div class="mmt-sticky-actions" id="mmtStickyActions">
                <a href="#bookingSection" class="mmt-sticky-btn mmt-sticky-primary" id="mmtStickyBook">Book Now</a>
                <a href="tel:+919876543210" class="mmt-sticky-btn mmt-sticky-secondary" id="mmtStickyCall">
                    <i class="fa-solid fa-phone"></i> Call
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MAIN CONTENT ===================== --}}
<section class="mmt-main" id="mmtMain">
    <div class="container" id="mmtMainContainer">
        <div class="row" id="mmtMainRow">
            <div class="col-lg-8" id="mmtMainLeft">

                {{-- Rating & Highlights Strip --}}
                <div class="mmt-rating-strip" id="mmtRatingStrip">
                    <div class="mmt-rating-box" id="mmtRatingBox">
                        <div class="mmt-rating-score" id="mmtRatingScore">{{ number_format($hotel->rating ?? 4.5, 1) }}</div>
                        <div class="mmt-rating-label" id="mmtRatingLabel">Excellent</div>
                        <div class="mmt-rating-count" id="mmtRatingCount">2,847 Ratings</div>
                    </div>
                    <div class="mmt-highlights" id="mmtHighlights">
                        <div class="mmt-highlight-item" id="mmtHighlight1">
                            <i class="fa-solid fa-thumbs-up" id="mmtHighlightIcon1"></i>
                            <span id="mmtHighlightText1">Recommended by 94% guests</span>
                        </div>
                        <div class="mmt-highlight-item" id="mmtHighlight2">
                            <i class="fa-solid fa-award" id="mmtHighlightIcon2"></i>
                            <span id="mmtHighlightText2">MMT Assured</span>
                        </div>
                        <div class="mmt-highlight-item" id="mmtHighlight3">
                            <i class="fa-solid fa-shield-halved" id="mmtHighlightIcon3"></i>
                            <span id="mmtHighlightText3">Best Price Guarantee</span>
                        </div>
                        <div class="mmt-highlight-item" id="mmtHighlight4">
                            <i class="fa-solid fa-rotate-left" id="mmtHighlightIcon4"></i>
                            <span id="mmtHighlightText4">Free Cancellation</span>
                        </div>
                    </div>
                </div>

                {{-- Quick Info Cards --}}
                <div class="mmt-quick-info" id="mmtQuickInfo">
                    <div class="mmt-info-card" id="mmtInfoCard1">
                        <div class="mmt-info-icon" id="mmtInfoIcon1"><i class="fa-regular fa-clock"></i></div>
                        <div class="mmt-info-text" id="mmtInfoText1">
                            <span class="mmt-info-label" id="mmtInfoLabel1">Check-in / Check-out</span>
                            <span class="mmt-info-value" id="mmtInfoValue1">12:00 PM / 11:00 AM</span>
                        </div>
                    </div>
                    <div class="mmt-info-card" id="mmtInfoCard2">
                        <div class="mmt-info-icon" id="mmtInfoIcon2"><i class="fa-solid fa-hotel"></i></div>
                        <div class="mmt-info-text" id="mmtInfoText2">
                            <span class="mmt-info-label" id="mmtInfoLabel2">Property Type</span>
                            <span class="mmt-info-value" id="mmtInfoValue2">Luxury Resort</span>
                        </div>
                    </div>
                    <div class="mmt-info-card" id="mmtInfoCard3">
                        <div class="mmt-info-icon" id="mmtInfoIcon3"><i class="fa-solid fa-wifi"></i></div>
                        <div class="mmt-info-text" id="mmtInfoText3">
                            <span class="mmt-info-label" id="mmtInfoLabel3">Free WiFi</span>
                            <span class="mmt-info-value" id="mmtInfoValue3">Available</span>
                        </div>
                    </div>
                    <div class="mmt-info-card" id="mmtInfoCard4">
                        <div class="mmt-info-icon" id="mmtInfoIcon4"><i class="fa-solid fa-mug-hot"></i></div>
                        <div class="mmt-info-text" id="mmtInfoText4">
                            <span class="mmt-info-label" id="mmtInfoLabel4">Breakfast</span>
                            <span class="mmt-info-value" id="mmtInfoValue4">Complimentary</span>
                        </div>
                    </div>
                </div>

                {{-- Overview --}}
                <div class="mmt-card" id="mmtOverviewCard">
                    <div class="mmt-card-header" id="mmtOverviewHeader">
                        <i class="fa-solid fa-hotel" id="mmtOverviewIcon"></i>
                        <h2 id="mmtOverviewTitle">About This Property</h2>
                    </div>
                    <div class="mmt-card-body" id="mmtOverviewBody">
                        <p class="mmt-description" id="mmtDescription">{!! nl2br(e($hotel->description)) !!}</p>
                        <div class="mmt-read-more" id="mmtReadMore">
                            <button onclick="toggleDescription()" id="mmtReadMoreBtn">Read More <i class="fa-solid fa-chevron-down"></i></button>
                        </div>
                    </div>
                </div>

                {{-- Amenities --}}
                <div class="mmt-card" id="mmtAmenitiesCard">
                    <div class="mmt-card-header" id="mmtAmenitiesHeader">
                        <i class="fa-solid fa-list-check" id="mmtAmenitiesIcon"></i>
                        <h2 id="mmtAmenitiesTitle">Amenities</h2>
                    </div>
                    <div class="mmt-card-body" id="mmtAmenitiesBody">
                        <div class="mmt-amenities-grid" id="mmtAmenitiesGrid">
                            <div class="mmt-amenity-item" id="mmtAmenity1">
                                <div class="mmt-amenity-icon" id="mmtAmenityIcon1"><i class="fa-solid fa-wifi"></i></div>
                                <span id="mmtAmenityText1">Free WiFi</span>
                            </div>
                            <div class="mmt-amenity-item" id="mmtAmenity2">
                                <div class="mmt-amenity-icon" id="mmtAmenityIcon2"><i class="fa-solid fa-utensils"></i></div>
                                <span id="mmtAmenityText2">Restaurant</span>
                            </div>
                            <div class="mmt-amenity-item" id="mmtAmenity3">
                                <div class="mmt-amenity-icon" id="mmtAmenityIcon3"><i class="fa-solid fa-car"></i></div>
                                <span id="mmtAmenityText3">Free Parking</span>
                            </div>
                            <div class="mmt-amenity-item" id="mmtAmenity4">
                                <div class="mmt-amenity-icon" id="mmtAmenityIcon4"><i class="fa-solid fa-snowflake"></i></div>
                                <span id="mmtAmenityText4">Air Conditioning</span>
                            </div>
                            <div class="mmt-amenity-item" id="mmtAmenity5">
                                <div class="mmt-amenity-icon" id="mmtAmenityIcon5"><i class="fa-solid fa-spa"></i></div>
                                <span id="mmtAmenityText5">Spa & Wellness</span>
                            </div>
                            <div class="mmt-amenity-item" id="mmtAmenity6">
                                <div class="mmt-amenity-icon" id="mmtAmenityIcon6"><i class="fa-solid fa-dumbbell"></i></div>
                                <span id="mmtAmenityText6">Fitness Center</span>
                            </div>
                            <div class="mmt-amenity-item" id="mmtAmenity7">
                                <div class="mmt-amenity-icon" id="mmtAmenityIcon7"><i class="fa-solid fa-person-swimming"></i></div>
                                <span id="mmtAmenityText7">Swimming Pool</span>
                            </div>
                            <div class="mmt-amenity-item" id="mmtAmenity8">
                                <div class="mmt-amenity-icon" id="mmtAmenityIcon8"><i class="fa-solid fa-broom"></i></div>
                                <span id="mmtAmenityText8">Room Service</span>
                            </div>
                            <div class="mmt-amenity-item" id="mmtAmenity9">
                                <div class="mmt-amenity-icon" id="mmtAmenityIcon9"><i class="fa-solid fa-tv"></i></div>
                                <span id="mmtAmenityText9">Flat Screen TV</span>
                            </div>
                            <div class="mmt-amenity-item" id="mmtAmenity10">
                                <div class="mmt-amenity-icon" id="mmtAmenityIcon10"><i class="fa-solid fa-mug-hot"></i></div>
                                <span id="mmtAmenityText10">Tea/Coffee Maker</span>
                            </div>
                            <div class="mmt-amenity-item" id="mmtAmenity11">
                                <div class="mmt-amenity-icon" id="mmtAmenityIcon11"><i class="fa-solid fa-bell-concierge"></i></div>
                                <span id="mmtAmenityText11">24/7 Front Desk</span>
                            </div>
                            <div class="mmt-amenity-item" id="mmtAmenity12">
                                <div class="mmt-amenity-icon" id="mmtAmenityIcon12"><i class="fa-solid fa-shirt"></i></div>
                                <span id="mmtAmenityText12">Laundry Service</span>
                            </div>
                        </div>
                        <button class="mmt-show-all-btn" id="mmtShowAllAmenities" onclick="toggleAmenities()">
                            Show All Amenities <i class="fa-solid fa-chevron-down"></i>
                        </button>
                    </div>
                </div>

                {{-- Location --}}
                <div class="mmt-card" id="mmtLocationCard">
                    <div class="mmt-card-header" id="mmtLocationHeader">
                        <i class="fa-solid fa-map-location-dot" id="mmtLocationHeaderIcon"></i>
                        <h2 id="mmtLocationTitle">Location</h2>
                    </div>
                    <div class="mmt-card-body" id="mmtLocationBody">
                        <div class="mmt-location-info" id="mmtLocationInfo">
                            <div class="mmt-location-icon-big" id="mmtLocationIconBig">
                                <i class="fa-solid fa-location-crosshairs"></i>
                            </div>
                            <div class="mmt-location-text" id="mmtLocationTextBlock">
                                <h4 id="mmtLocationName">{{ $hotel->location }}</h4>
                                <p id="mmtLocationAddress">{{ $hotel->address ?? 'Situated in a prime location with easy access to major attractions and transport hubs.' }}</p>
                            </div>
                        </div>

                        @php
                            $fullAddress = trim(
                                ($hotel->location ?? '') . ' ' .
                                ($hotel->address ?? '') . ' ' .
                                ($hotel->city ?? '') . ' ' .
                                ($hotel->state ?? '') . ' ' .
                                ($hotel->pincode ?? '')
                            );
                        @endphp

                        <div class="mmt-map-wrapper" id="mmtMapWrapper">
                            <div id="hotelMap" class="mmt-map-container"></div>
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($fullAddress) }}" target="_blank" class="mmt-direction-btn" id="mmtDirectionBtn">
                                <i class="fa-solid fa-location-arrow"></i>
                                <span>Get Directions</span>
                            </a>
                        </div>

                        <div class="mmt-nearby" id="mmtNearby">
                            <h4 id="mmtNearbyTitle">What's Nearby</h4>
                            <div class="mmt-nearby-grid" id="mmtNearbyGrid">
                                <div class="mmt-nearby-item" id="mmtNearby1">
                                    <i class="fa-solid fa-plane"></i>
                                    <span>Airport <small>12 km</small></span>
                                </div>
                                <div class="mmt-nearby-item" id="mmtNearby2">
                                    <i class="fa-solid fa-train"></i>
                                    <span>Railway Station <small>5 km</small></span>
                                </div>
                                <div class="mmt-nearby-item" id="mmtNearby3">
                                    <i class="fa-solid fa-bus"></i>
                                    <span>Bus Stand <small>2 km</small></span>
                                </div>
                                <div class="mmt-nearby-item" id="mmtNearby4">
                                    <i class="fa-solid fa-mountain-sun"></i>
                                    <span>Tourist Spot <small>3 km</small></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Reviews --}}
                <div class="mmt-card" id="mmtReviewsCard">
                    <div class="mmt-card-header" id="mmtReviewsHeader">
                        <i class="fa-solid fa-comments" id="mmtReviewsIcon"></i>
                        <h2 id="mmtReviewsTitle">Guest Reviews</h2>
                    </div>
                    <div class="mmt-card-body" id="mmtReviewsBody">
                        <div class="mmt-review-summary" id="mmtReviewSummary">
                            <div class="mmt-review-score-box" id="mmtReviewScoreBox">
                                <span class="mmt-review-number" id="mmtReviewNumber">{{ number_format($hotel->rating ?? 4.5, 1) }}</span>
                                <div class="mmt-review-stars" id="mmtReviewStars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star {{ $i <= ($hotel->rating ?? 4.5) ? '' : 'empty' }}"></i>
                                    @endfor
                                </div>
                                <span class="mmt-review-based" id="mmtReviewBased">Based on 2,847 guest reviews</span>
                            </div>
                            <div class="mmt-review-bars" id="mmtReviewBars">
                                <div class="mmt-review-bar-item" id="mmtReviewBar1">
                                    <span class="mmt-bar-label">Cleanliness</span>
                                    <div class="mmt-bar"><div class="mmt-bar-fill" style="width: 92%"></div></div>
                                    <span class="mmt-bar-value">9.2</span>
                                </div>
                                <div class="mmt-review-bar-item" id="mmtReviewBar2">
                                    <span class="mmt-bar-label">Service</span>
                                    <div class="mmt-bar"><div class="mmt-bar-fill" style="width: 88%"></div></div>
                                    <span class="mmt-bar-value">8.8</span>
                                </div>
                                <div class="mmt-review-bar-item" id="mmtReviewBar3">
                                    <span class="mmt-bar-label">Comfort</span>
                                    <div class="mmt-bar"><div class="mmt-bar-fill" style="width: 95%"></div></div>
                                    <span class="mmt-bar-value">9.5</span>
                                </div>
                                <div class="mmt-review-bar-item" id="mmtReviewBar4">
                                    <span class="mmt-bar-label">Location</span>
                                    <div class="mmt-bar"><div class="mmt-bar-fill" style="width: 90%"></div></div>
                                    <span class="mmt-bar-value">9.0</span>
                                </div>
                                <div class="mmt-review-bar-item" id="mmtReviewBar5">
                                    <span class="mmt-bar-label">Value for Money</span>
                                    <div class="mmt-bar"><div class="mmt-bar-fill" style="width: 85%"></div></div>
                                    <span class="mmt-bar-value">8.5</span>
                                </div>
                            </div>
                        </div>

                        <div class="mmt-recent-reviews" id="mmtRecentReviews">
                            <h4 id="mmtRecentTitle">What guests loved most</h4>
                            <div class="mmt-review-tags" id="mmtReviewTags">
                                <span class="mmt-review-tag" id="mmtTag1"><i class="fa-solid fa-thumbs-up"></i> Clean Rooms</span>
                                <span class="mmt-review-tag" id="mmtTag2"><i class="fa-solid fa-thumbs-up"></i> Friendly Staff</span>
                                <span class="mmt-review-tag" id="mmtTag3"><i class="fa-solid fa-thumbs-up"></i> Great Location</span>
                                <span class="mmt-review-tag" id="mmtTag4"><i class="fa-solid fa-thumbs-up"></i> Delicious Food</span>
                                <span class="mmt-review-tag" id="mmtTag5"><i class="fa-solid fa-thumbs-up"></i> Beautiful View</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- House Rules --}}
                <div class="mmt-card" id="mmtRulesCard">
                    <div class="mmt-card-header" id="mmtRulesHeader">
                        <i class="fa-solid fa-clipboard-list" id="mmtRulesIcon"></i>
                        <h2 id="mmtRulesTitle">House Rules & Policies</h2>
                    </div>
                    <div class="mmt-card-body" id="mmtRulesBody">
                        <div class="mmt-rules-grid" id="mmtRulesGrid">
                            <div class="mmt-rule-item" id="mmtRule1">
                                <i class="fa-solid fa-clock"></i>
                                <div>
                                    <strong>Check-in</strong>
                                    <span>From 12:00 PM</span>
                                </div>
                            </div>
                            <div class="mmt-rule-item" id="mmtRule2">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                <div>
                                    <strong>Check-out</strong>
                                    <span>Until 11:00 AM</span>
                                </div>
                            </div>
                            <div class="mmt-rule-item" id="mmtRule3">
                                <i class="fa-solid fa-ban-smoking"></i>
                                <div>
                                    <strong>No Smoking</strong>
                                    <span>Smoking not allowed</span>
                                </div>
                            </div>
                            <div class="mmt-rule-item" id="mmtRule4">
                                <i class="fa-solid fa-paw"></i>
                                <div>
                                    <strong>Pets</strong>
                                    <span>Not allowed</span>
                                </div>
                            </div>
                            <div class="mmt-rule-item" id="mmtRule5">
                                <i class="fa-solid fa-champagne-glasses"></i>
                                <div>
                                    <strong>Parties</strong>
                                    <span>Not allowed</span>
                                </div>
                            </div>
                            <div class="mmt-rule-item" id="mmtRule6">
                                <i class="fa-solid fa-child-reaching"></i>
                                <div>
                                    <strong>Children</strong>
                                    <span>All ages welcome</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ===================== SIDEBAR ===================== --}}
            <div class="col-lg-4" id="mmtMainRight">
                <div class="mmt-booking-card" id="bookingSection">
                    {{-- Price Header --}}
                    <div class="mmt-booking-header" id="mmtBookingHeader">
                        <div class="mmt-price-tag" id="mmtPriceTag">
                            <div class="mmt-price-discount" id="mmtPriceDiscount">
                                <span class="mmt-old-price" id="mmtOldPrice"><s>₹{{ number_format(($hotel->starting_price ?? $hotel->price ?? 0) * 1.4) }}</s></span>
                                <span class="mmt-discount-badge" id="mmtDiscountBadge">29% OFF</span>
                            </div>
                            <div class="mmt-price-wrap" id="mmtPriceWrap">
                                <span class="mmt-price-currency" id="mmtPriceCurrency">₹</span>
                                <span class="mmt-price-value" id="mmtPriceValue">{{ number_format($hotel->starting_price ?? $hotel->price ?? 0) }}</span>
                            </div>
                            <span class="mmt-price-unit" id="mmtPriceUnit">per room / night · GST by tariff slab</span>
                        </div>
                        <div class="mmt-price-guarantee" id="mmtPriceGuarantee">
                            <i class="fa-solid fa-tag" id="mmtPriceGuaranteeIcon"></i>
                            <span id="mmtPriceGuaranteeText">Best Price Guarantee</span>
                        </div>
                    </div>

                    {{-- Booking Form --}}
                    <div class="mmt-booking-body" id="mmtBookingBody">
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif
                        <form id="mmtBookingForm" class="mmt-booking-form" method="POST" action="{{ route('hotel.booking.start', $hotel) }}">
                            @csrf
                            <input type="hidden" name="checkout_token" value="{{ session('new_checkout_token', old('checkout_token', (string) \Illuminate\Support\Str::uuid())) }}">
                            <div class="mmt-form-row" id="mmtFormRow">
                                <div class="mmt-form-group" id="mmtFormGroupCheckin">
                                    <label for="mmtCheckinDate" id="mmtLabelCheckin">
                                        <i class="fa-regular fa-calendar" id="mmtIconCheckin"></i> Check-in
                                    </label>
                                    <div class="mmt-input-box" id="mmtInputBoxCheckin">
                                        <input type="date" name="check_in" id="mmtCheckinDate" min="{{ now()->toDateString() }}" value="{{ old('check_in') }}" required>
                                    </div>
                                </div>
                                <div class="mmt-form-group" id="mmtFormGroupCheckout">
                                    <label for="mmtCheckoutDate" id="mmtLabelCheckout">
                                        <i class="fa-regular fa-calendar-check" id="mmtIconCheckout"></i> Check-out
                                    </label>
                                    <div class="mmt-input-box" id="mmtInputBoxCheckout">
                                        <input type="date" name="check_out" id="mmtCheckoutDate" min="{{ now()->addDay()->toDateString() }}" value="{{ old('check_out') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mmt-form-group" id="mmtFormGroupGuests">
                                <label for="mmtGuestSelect" id="mmtLabelGuests">
                                    <i class="fa-solid fa-user-group" id="mmtIconGuests"></i> Guests
                                </label>
                                <div class="mmt-input-box" id="mmtInputBoxGuests">
                                    <select id="mmtGuestSelect" name="guests" required>
                                        <option value="" disabled {{ old('guests') ? '' : 'selected' }}>Select guests</option>
                                        @foreach (range(1, 20) as $guestCount)
                                            <option value="{{ $guestCount }}" {{ old('guests') == $guestCount ? 'selected' : '' }}>{{ $guestCount }} {{ $guestCount === 1 ? 'Guest' : 'Guests' }}</option>
                                        @endforeach
                                    </select>
                                    <i class="fa-solid fa-chevron-down mmt-select-arrow" id="mmtGuestSelectArrow"></i>
                                </div>
                            </div>

                            @if ($hotel->rooms->isNotEmpty())
                                <div class="mmt-form-group">
                                    <label><i class="fa-solid fa-bed"></i> Select rooms (up to 4)</label>
                                    <div class="d-grid gap-2">
                                        @foreach ($hotel->rooms as $room)
                                            <label class="d-flex align-items-center justify-content-between gap-3 border rounded-3 bg-white p-3">
                                                <span>
                                                    <strong class="d-block">{{ $room->room_name }}</strong>
                                                    <small class="text-muted">
                                                        {{ $room->roomType?->name ?? 'Room' }} · Sleeps {{ $room->capacity }} ·
                                                        ₹{{ number_format((float) $room->price_per_night, 2) }} / night ·
                                                        {{ $room->available_rooms }} available
                                                    </small>
                                                </span>
                                                <input class="form-control" style="width: 84px" type="number"
                                                    name="room_allocations[{{ $room->id }}]" min="0"
                                                    max="{{ min(4, $room->available_rooms) }}"
                                                    value="{{ old("room_allocations.{$room->id}", 0) }}"
                                                    aria-label="Quantity of {{ $room->room_name }}">
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @elseif ($hasRoomInventory)
                                <div class="alert alert-warning">No rooms are currently available at this hotel.</div>
                            @else
                                <div class="mmt-form-group" id="mmtFormGroupRooms">
                                    <label for="mmtRoomSelect" id="mmtLabelRooms">
                                        <i class="fa-solid fa-bed" id="mmtIconRooms"></i> Rooms
                                    </label>
                                    <div class="mmt-input-box" id="mmtInputBoxRooms">
                                        <select id="mmtRoomSelect" name="rooms" required>
                                            @foreach (range(1, 4) as $roomCount)
                                                <option value="{{ $roomCount }}" {{ old('rooms', 1) == $roomCount ? 'selected' : '' }}>
                                                    {{ $roomCount.' '.($roomCount === 1 ? 'Room' : 'Rooms') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <i class="fa-solid fa-chevron-down mmt-select-arrow" id="mmtRoomSelectArrow"></i>
                                    </div>
                                </div>
                            @endif

                            <div class="mmt-form-group" id="mmtFormGroupName">
                                <label for="mmtGuestName"><i class="fa-solid fa-user"></i> Full Name</label>
                                <div class="mmt-input-box">
                                    <input type="text" name="name" id="mmtGuestName" value="{{ old('name') }}" autocomplete="name" maxlength="120" required>
                                </div>
                            </div>

                            <div class="mmt-form-group" id="mmtFormGroupPhone">
                                <label for="mmtGuestPhone"><i class="fa-solid fa-phone"></i> Phone Number</label>
                                <div class="mmt-input-box">
                                    <input type="tel" name="phone" id="mmtGuestPhone" value="{{ old('phone') }}" autocomplete="tel" maxlength="24" required>
                                </div>
                            </div>

                            <div class="mmt-form-group" id="mmtFormGroupEmail">
                                <label for="mmtGuestEmail"><i class="fa-solid fa-envelope"></i> Email</label>
                                <div class="mmt-input-box">
                                    <input type="email" name="email" id="mmtGuestEmail" value="{{ old('email') }}" autocomplete="email" maxlength="255" required>
                                </div>
                            </div>

                            {{-- Trust Features --}}
                            <div class="mmt-trust-features" id="mmtTrustFeatures">
                                <div class="mmt-trust-item" id="mmtTrust1">
                                    <div class="mmt-trust-icon" id="mmtTrustIcon1"><i class="fa-solid fa-check"></i></div>
                                    <span id="mmtTrustText1">Contact us for cancellation policy</span>
                                </div>
                                <div class="mmt-trust-item" id="mmtTrust2">
                                    <div class="mmt-trust-icon" id="mmtTrustIcon2"><i class="fa-solid fa-check"></i></div>
                                    <span id="mmtTrustText2">Secure online payment</span>
                                </div>
                                <div class="mmt-trust-item" id="mmtTrust3">
                                    <div class="mmt-trust-icon" id="mmtTrustIcon3"><i class="fa-solid fa-check"></i></div>
                                    <span id="mmtTrustText3">Confirmation after successful payment</span>
                                </div>
                                <div class="mmt-trust-item" id="mmtTrust4">
                                    <div class="mmt-trust-icon" id="mmtTrustIcon4"><i class="fa-solid fa-check"></i></div>
                                    <span id="mmtTrustText4">Breakfast included</span>
                                </div>
                            </div>

                            <button type="submit" class="mmt-btn-book" id="mmtBtnBook">
                                <span id="mmtBtnBookText">Book Now</span>
                                <i class="fa-solid fa-arrow-right" id="mmtBtnBookIcon"></i>
                            </button>
                        </form>

                        <div class="mmt-booking-note" id="mmtBookingNote">
                            <i class="fa-solid fa-shield-halved" id="mmtBookingNoteIcon"></i>
                            <span id="mmtBookingNoteText">Secure SSL encrypted payment</span>
                        </div>
                    </div>

                    {{-- Quick Contact --}}
                    <div class="mmt-quick-contact" id="mmtQuickContact">
                        <a href="https://wa.me/919876543210" target="_blank" class="mmt-quick-btn mmt-whatsapp" id="mmtBtnWhatsApp">
                            <i class="fa-brands fa-whatsapp" id="mmtBtnWhatsAppIcon"></i>
                            <span id="mmtBtnWhatsAppText">WhatsApp</span>
                        </a>
                        <a href="tel:+919876543210" class="mmt-quick-btn mmt-call" id="mmtBtnCall">
                            <i class="fa-solid fa-phone" id="mmtBtnCallIcon"></i>
                            <span id="mmtBtnCallText">Call Now</span>
                        </a>
                    </div>

                    {{-- FAQ Section --}}
                    <div class="mmt-faq-section" id="mmtFaqSection">
                        <h4 id="mmtFaqTitle"><i class="fa-regular fa-circle-question" id="mmtFaqTitleIcon"></i> Quick FAQs</h4>
                        <div class="mmt-faq-item" id="mmtFaqItem1">
                            <button class="mmt-faq-question" onclick="toggleFaq(this)" id="mmtFaqQuestion1">
                                <span id="mmtFaqQText1">Is breakfast included?</span>
                                <i class="fa-solid fa-chevron-down" id="mmtFaqQIcon1"></i>
                            </button>
                            <div class="mmt-faq-answer" id="mmtFaqAnswer1"><p id="mmtFaqAnsText1">Yes, complimentary breakfast is included with all room bookings.</p></div>
                        </div>
                        <div class="mmt-faq-item" id="mmtFaqItem2">
                            <button class="mmt-faq-question" onclick="toggleFaq(this)" id="mmtFaqQuestion2">
                                <span id="mmtFaqQText2">Pet-friendly policy?</span>
                                <i class="fa-solid fa-chevron-down" id="mmtFaqQIcon2"></i>
                            </button>
                            <div class="mmt-faq-answer" id="mmtFaqAnswer2"><p id="mmtFaqAnsText2">We welcome pets with prior notice. Additional charges may apply.</p></div>
                        </div>
                        <div class="mmt-faq-item" id="mmtFaqItem3">
                            <button class="mmt-faq-question" onclick="toggleFaq(this)" id="mmtFaqQuestion3">
                                <span id="mmtFaqQText3">Airport transfer available?</span>
                                <i class="fa-solid fa-chevron-down" id="mmtFaqQIcon3"></i>
                            </button>
                            <div class="mmt-faq-answer" id="mmtFaqAnswer3"><p id="mmtFaqAnsText3">Yes, paid airport transfers can be arranged. Contact us for details.</p></div>
                        </div>
                    </div>

                    {{-- Social Grid --}}
                    <div class="mmt-social-grid" id="mmtSocialGrid">
                        <h4 id="mmtSocialTitle">Follow Us</h4>
                        <div class="mmt-social-links" id="mmtSocialLinks">
                            <a href="#" class="mmt-social-link" id="mmtSocialFacebook"><i class="fa-brands fa-facebook-f" id="mmtSocialFacebookIcon"></i></a>
                            <a href="#" class="mmt-social-link" id="mmtSocialInstagram"><i class="fa-brands fa-instagram" id="mmtSocialInstagramIcon"></i></a>
                            <a href="#" class="mmt-social-link" id="mmtSocialTwitter"><i class="fa-brands fa-x-twitter" id="mmtSocialTwitterIcon"></i></a>
                            <a href="#" class="mmt-social-link" id="mmtSocialYoutube"><i class="fa-brands fa-youtube" id="mmtSocialYoutubeIcon"></i></a>
                        </div>
                    </div>

                    {{-- Contact Footer --}}
                    <div class="mmt-booking-footer" id="mmtBookingFooter">
                        <div class="mmt-contact-item" id="mmtContactItemPhone">
                            <div class="mmt-contact-icon" id="mmtContactIconPhone"><i class="fa-solid fa-phone" id="mmtContactIconPhoneImg"></i></div>
                            <div class="mmt-contact-text" id="mmtContactTextPhone">
                                <small id="mmtContactLabelPhone">Call Us</small>
                                <span id="mmtContactValuePhone">+91 98765 43210</span>
                            </div>
                        </div>
                        <div class="mmt-contact-item" id="mmtContactItemEmail">
                            <div class="mmt-contact-icon" id="mmtContactIconEmail"><i class="fa-solid fa-envelope" id="mmtContactIconEmailImg"></i></div>
                            <div class="mmt-contact-text" id="mmtContactTextEmail">
                                <small id="mmtContactLabelEmail">Email</small>
                                <span id="mmtContactValueEmail">bookings@uttarakhand.com</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== RELATED HOTELS ===================== --}}
<section class="mmt-related-section" id="mmtRelatedSection">
    <div class="container" id="mmtRelatedContainer">
        <div class="mmt-section-header" id="mmtRelatedHeader">
            <span class="mmt-section-label" id="mmtRelatedLabel">You May Also Like</span>
            <h2 id="mmtRelatedTitle">Related Hotels</h2>
            <p id="mmtRelatedDesc">Discover more premium stays in similar locations</p>
            <div class="mmt-divider" id="mmtRelatedDivider"></div>
        </div>

        @if($relatedHotels && $relatedHotels->count() > 0)
            <div class="row g-4" id="mmtRelatedRow">
                @foreach($relatedHotels as $item)
                    <div class="col-lg-3 col-md-6" id="mmtRelatedCol{{ $item->id }}">
                        <div class="mmt-related-card" id="mmtRelatedCard{{ $item->id }}">
                            <div class="mmt-related-img-wrap" id="mmtRelatedImgWrap{{ $item->id }}">
                                @if($item->thumbnail)
                                    <img src="{{ asset('uploads/hotels/'.$item->thumbnail) }}" alt="{{ $item->hotel_name }}" loading="lazy" id="mmtRelatedImg{{ $item->id }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&h=400&fit=crop" alt="{{ $item->hotel_name }}" id="mmtRelatedImg{{ $item->id }}">
                                @endif
                                <div class="mmt-related-badge" id="mmtRelatedBadge{{ $item->id }}">
                                    <i class="fa-solid fa-star" id="mmtRelatedBadgeIcon{{ $item->id }}"></i>
                                    <span id="mmtRelatedBadgeValue{{ $item->id }}">{{ number_format($item->rating ?? 4.5, 1) }}</span>
                                </div>
                                <div class="mmt-related-save" id="mmtRelatedSave{{ $item->id }}" onclick="event.preventDefault(); toggleSave(this)">
                                    <i class="fa-regular fa-heart"></i>
                                </div>
                            </div>
                            <div class="mmt-related-body" id="mmtRelatedBody{{ $item->id }}">
                                <h4 id="mmtRelatedName{{ $item->id }}">{{ $item->hotel_name }}</h4>
                                <div class="mmt-related-location" id="mmtRelatedLocation{{ $item->id }}">
                                    <i class="fa-solid fa-location-dot" id="mmtRelatedLocationIcon{{ $item->id }}"></i>
                                    <span id="mmtRelatedLocationText{{ $item->id }}">{{ $item->location }}</span>
                                </div>
                                <div class="mmt-related-footer" id="mmtRelatedFooter{{ $item->id }}">
                                    <div class="mmt-related-price" id="mmtRelatedPrice{{ $item->id }}">
                                        <span id="mmtRelatedPriceValue{{ $item->id }}">₹{{ number_format($item->starting_price ?? $item->price ?? 0) }}</span>
                                        <small id="mmtRelatedPriceUnit{{ $item->id }}">/ night</small>
                                    </div>
                                    <a href="{{ route('hotel.details', $item->id) }}" class="mmt-btn-related" id="mmtBtnRelated{{ $item->id }}">
                                        <span id="mmtBtnRelatedText{{ $item->id }}">View</span> <i class="fa-solid fa-arrow-right" id="mmtBtnRelatedIcon{{ $item->id }}"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="mmt-no-related" id="mmtNoRelated">
                <i class="fa-solid fa-hotel" id="mmtNoRelatedIcon"></i>
                <h4 id="mmtNoRelatedTitle">No Related Hotels Found</h4>
                <p id="mmtNoRelatedText">Explore all hotels to find your perfect stay.</p>
                <a href="{{ route('hotels') }}" class="mmt-btn-explore" id="mmtBtnExplore">Browse All Hotels</a>
            </div>
        @endif
    </div>
</section>

{{-- ===================== LIGHTBOX ===================== --}}
<div class="mmt-lightbox" id="lightbox">
    <div class="mmt-lightbox-backdrop" onclick="closeLightbox()" id="mmtLightboxBackdrop"></div>
    <div class="mmt-lightbox-content" id="mmtLightboxContent">
        <button class="mmt-lightbox-close" onclick="closeLightbox()" id="mmtLightboxCloseBtn">
            <i class="fa-solid fa-xmark" id="mmtLightboxCloseIcon"></i>
        </button>
        @if($hotel->thumbnail)
            <img src="{{ asset('uploads/hotels/'.$hotel->thumbnail) }}" alt="{{ $hotel->hotel_name }}" id="mmtLightboxImg">
        @else
            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1600&h=800&fit=crop" alt="{{ $hotel->hotel_name }}" id="mmtLightboxImg">
        @endif
    </div>
</div>

{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
// ============================================
// LEAFLET MAP INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the map
    var hotelMap = L.map('hotelMap', {
        center: [30.3165, 78.0322], // Default Uttarakhand coordinates (change as needed)
        zoom: 14,
        scrollWheelZoom: false,
        attributionControl: false
    });

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(hotelMap);

    // Custom marker icon
    var hotelIcon = L.divIcon({
        className: 'custom-hotel-marker',
        html: '<div class="marker-pin"><i class="fa-solid fa-hotel"></i></div><div class="marker-pulse"></div>',
        iconSize: [50, 50],
        iconAnchor: [25, 50]
    });

    // Add marker
    L.marker([30.3165, 78.0322], { icon: hotelIcon }).addTo(hotelMap)
        .bindPopup('<div class="map-popup"><h6>{{ $hotel->hotel_name }}</h6><p><i class="fa-solid fa-location-dot"></i> {{ $hotel->location }}</p></div>')
        .openPopup();

    // Add attribution control manually with custom position
    L.control.attribution({
        position: 'bottomleft'
    }).addTo(hotelMap);
});

// ============================================
// STICKY BAR
// ============================================
const stickyBar = document.getElementById('mmtStickyBar');
const heroSection = document.getElementById('mmtHero');

window.addEventListener('scroll', () => {
    if (window.scrollY > heroSection.offsetHeight - 100) {
        stickyBar.classList.add('visible');
    } else {
        stickyBar.classList.remove('visible');
    }
});

// ============================================
// FAQ TOGGLE
// ============================================
function toggleFaq(btn) {
    const answer = btn.nextElementSibling;
    const icon = btn.querySelector('i');
    if (answer.style.maxHeight && answer.style.maxHeight !== '0px') {
        answer.style.maxHeight = '0px';
        icon.style.transform = 'rotate(0deg)';
    } else {
        answer.style.maxHeight = answer.scrollHeight + 'px';
        icon.style.transform = 'rotate(180deg)';
    }
}

// ============================================
// LIGHTBOX
// ============================================
function openLightbox() {
    document.getElementById('lightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
    document.body.style.overflow = '';
}

// Close lightbox on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});

// ============================================
// SAVE TOGGLE (WISHLIST)
// ============================================
function toggleSave(btn) {
    const icon = btn.querySelector('i');
    if (icon.classList.contains('fa-regular')) {
        icon.classList.remove('fa-regular');
        icon.classList.add('fa-solid');
        icon.style.color = '#ef4444';
    } else {
        icon.classList.remove('fa-solid');
        icon.classList.add('fa-regular');
        icon.style.color = '';
    }
}

// ============================================
// SHARE
// ============================================
function shareHotel() {
    if (navigator.share) {
        navigator.share({
            title: document.title,
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('Link copied to clipboard!');
    }
}

// ============================================
// DESCRIPTION TOGGLE
// ============================================
function toggleDescription() {
    const desc = document.getElementById('mmtDescription');
    const btn = document.getElementById('mmtReadMoreBtn');
    desc.classList.toggle('expanded');
    if (desc.classList.contains('expanded')) {
        btn.innerHTML = 'Read Less <i class="fa-solid fa-chevron-up"></i>';
    } else {
        btn.innerHTML = 'Read More <i class="fa-solid fa-chevron-down"></i>';
    }
}

function toggleAmenities() {
    const grid = document.getElementById('mmtAmenitiesGrid');
    const btn = document.getElementById('mmtShowAllAmenities');
    grid.classList.toggle('expanded');
    if (grid.classList.contains('expanded')) {
        btn.innerHTML = 'Show Less Amenities <i class="fa-solid fa-chevron-up"></i>';
    } else {
        btn.innerHTML = 'Show All Amenities <i class="fa-solid fa-chevron-down"></i>';
    }
}

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
</script>

@endsection
