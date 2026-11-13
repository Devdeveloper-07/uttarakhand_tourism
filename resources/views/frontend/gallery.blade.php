@extends('frontend.layouts.app')

@section('title', 'Photo Gallery')

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/gallery-page.css') }}?v={{ filemtime(public_path('frontend/css/gallery-page.css')) }}">
@endpush

@section('content')

<!-- Page Banner -->
<section class="page-banner gallery-page-banner">
    <div class="hero-bg">
        <div class="hero-pattern"></div>
        <div class="hero-gradient-overlay"></div>
    </div>
    <div class="container text-center">
        <div class="banner-content">
            <span class="banner-label">
                <i class="fa-solid fa-camera"></i> Visual Journey
            </span>
            <h1>Photo Gallery</h1>
            <p>Explore the breathtaking beauty of Uttarakhand through our curated collection</p>
            <div class="divider"></div>
        </div>
    </div>
</section>

<!-- Gallery Stats -->
<section class="gallery-stats gallery-stats-section">
    <div class="container">
        <div class="stats-bar">
            <div class="stat">
                <i class="fa-solid fa-images"></i>
                <span class="stat-num">{{ $totalImages ?? count($galleries) * 4 }}</span>
                <span class="stat-text">Photos</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <i class="fa-solid fa-mountain"></i>
                <span class="stat-num">{{ count($galleries) }}</span>
                <span class="stat-text">Destinations</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <i class="fa-solid fa-camera"></i>
                <span class="stat-num">4K</span>
                <span class="stat-text">Quality</span>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Filter -->
<section class="gallery-section gallery-page-section">
    <div class="container">
        <div class="gallery-header">
            <div class="filter-tabs">
                <button class="filter-btn filter-chip active" data-filter="all">All Photos</button>
                @foreach($galleries as $gallery)
                    <button class="filter-btn filter-chip" data-filter="cat-{{ $loop->index }}">{{ $gallery->title ?? 'Category ' . ($loop->index + 1) }}</button>
                @endforeach
            </div>
        </div>

        <div class="gallery-masonry" id="galleryGrid">
            @php $imageIndex = 0; @endphp

            @forelse($galleries as $gallery)
                @php
                    $images = is_array($gallery->image) ? $gallery->image : json_decode($gallery->image, true);
                @endphp

                @if (!empty($images))
                    @foreach ($images as $image)
                        @php $imageIndex++; @endphp
                        <div class="gallery-item" data-category="cat-{{ $loop->parent->index }}">
                            <div class="gallery-card gallery-item-card" onclick="openLightbox({{ $imageIndex - 1 }})">
                                <img src="{{ asset('uploads/gallery/' . $image) }}" alt="Gallery Image {{ $imageIndex }}" loading="lazy">
                                <div class="gallery-overlay">
                                    <div class="overlay-content">
                                        <div class="zoom-icon">
                                            <i class="fa-solid fa-expand"></i>
                                        </div>
                                        <span class="overlay-title">View Image</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            @empty
                <div class="empty-state gallery-empty">
                    <div class="empty-icon">
                        <i class="fa-regular fa-images"></i>
                    </div>
                    <h4>Gallery Coming Soon</h4>
                    <p>We're curating the best moments from Uttarakhand for you.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Lightbox -->
<div class="lightbox" id="lightbox">
    <div class="lightbox-backdrop" onclick="closeLightbox()"></div>
    <div class="lightbox-loader" id="lightboxLoader"></div>
    <div class="lightbox-img-container">
        <img src="" alt="Gallery" id="lightboxImg" onload="hideLoader()">
    </div>
    <button class="lightbox-close" onclick="closeLightbox()">
        <i class="fa-solid fa-xmark"></i>
    </button>
    <button class="lightbox-nav prev" onclick="changeImage(-1)">
        <i class="fa-solid fa-chevron-left"></i>
    </button>
    <button class="lightbox-nav next" onclick="changeImage(1)">
        <i class="fa-solid fa-chevron-right"></i>
    </button>
    <div class="lightbox-counter" id="lightboxCounter">1 / 12</div>
</div>

@endsection
