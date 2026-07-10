@extends('layouts.admin')

@section('title', 'Gallery Details')

@section('content')



<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Gallery Details</h2>
            <p class="page-subtitle">View Gallery Image Information</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('admin.galleries.edit', $gallery->id) }}" class="btn btn-warning btn-sm">
                <i class="fa-solid fa-pen"></i> Edit
            </a>
        </div>
    </div>

    <!-- Gallery Info Card -->
    <div class="content-card">
        <div class="card-header-custom">
            <div class="d-flex align-items-center gap-2">
                <h5><i class="fa-solid fa-image"></i> Gallery Information</h5>
                @if($gallery->status)
                    <span class="status-badge active">
                        <span class="status-dot"></span> Active
                    </span>
                @else
                    <span class="status-badge inactive">
                        <span class="status-dot"></span> Inactive
                    </span>
                @endif
            </div>
        </div>

        <div class="card-body-custom">
            <div class="row">
                @php
                    $galleryImages = $gallery->image;
                    $isMultiple = is_array($galleryImages) && count($galleryImages) > 1;
                    $hasImage = !empty($galleryImages);
                @endphp

                <!-- Gallery Image Section -->
                <div class="col-lg-5 mb-4">
                    @if($hasImage)
                        @if($isMultiple)
                            <!-- Multiple Images - Carousel with Auto Slide -->
                            <div class="gallery-carousel-container" id="galleryCarousel">
                                <div class="gallery-image-counter">
                                    <span id="currentSlideNum">1</span> / <span id="totalSlideNum">{{ count($galleryImages) }}</span>
                                </div>
                                <div class="gallery-carousel">
                                    <div class="gallery-carousel-inner" id="carouselInner">
                                        @foreach($galleryImages as $index => $img)
                                            <div class="gallery-carousel-item">
                                                <img src="{{ asset('uploads/gallery/'.$img) }}" alt="{{ $gallery->title }} - {{ $index + 1 }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="button" class="gallery-carousel-btn prev" id="prevBtn">
                                    <i class="fa-solid fa-chevron-left"></i>
                                </button>
                                <button type="button" class="gallery-carousel-btn next" id="nextBtn">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </button>
                                <div class="gallery-carousel-indicators" id="carouselIndicators">
                                    @foreach($galleryImages as $index => $img)
                                        <div class="gallery-carousel-indicator {{ $index == 0 ? 'active' : '' }}" data-slide="{{ $index }}"></div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <!-- Single Image - Normal Display -->
                            <div class="gallery-single-image">
                                @php
                                    $singleImage = is_array($galleryImages) ? $galleryImages[0] : $galleryImages;
                                @endphp
                                <img src="{{ asset('uploads/gallery/'.$singleImage) }}" alt="{{ $gallery->title }}">
                            </div>
                        @endif
                    @else
                        <!-- No Image -->
                        <div class="gallery-single-image">
                            <div class="gallery-no-image">
                                <i class="fa-solid fa-image"></i>
                                <span>No Image Available</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Gallery Details -->
                <div class="col-lg-7">
                    <div class="info-grid">
                        <!-- Title -->
                        <div class="info-item">
                            <div class="info-icon" style="background: var(--primary-light); color: var(--primary);">
                                <i class="fa-solid fa-signature"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Title</span>
                                <span class="info-value">{{ $gallery->title }}</span>
                            </div>
                        </div>

                        <!-- Type -->
                        <div class="info-item">
                            <div class="info-icon" style="background: var(--info-light); color: var(--info);">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Type</span>
                                <span class="info-value">{{ ucfirst($gallery->type) }}</span>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="info-item">
                            <div class="info-icon" style="background: {{ $gallery->status ? 'var(--success-light)' : 'var(--danger-light)' }}; color: {{ $gallery->status ? 'var(--success)' : 'var(--danger)' }};">
                                <i class="fa-solid {{ $gallery->status ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Status</span>
                                <span class="info-value" style="color: {{ $gallery->status ? 'var(--success)' : 'var(--danger)' }};">
                                    {{ $gallery->status ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>

                        <!-- Created At -->
                        <div class="info-item">
                            <div class="info-icon" style="background: var(--gray-100); color: var(--gray-600);">
                                <i class="fa-solid fa-calendar-plus"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Created At</span>
                                <span class="info-value">{{ $gallery->created_at->format('d M Y h:i A') }}</span>
                            </div>
                        </div>

                        <!-- Updated At -->
                        <div class="info-item">
                            <div class="info-icon" style="background: var(--gray-100); color: var(--gray-600);">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Last Updated</span>
                                <span class="info-value">{{ $gallery->updated_at->format('d M Y h:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Section -->
            <div class="description-section mt-4">
                <h6>
                    <i class="fa-solid fa-align-left"></i> Description
                </h6>
                <div class="description-box">
                    {!! nl2br(e($gallery->description)) !!}
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carouselInner = document.getElementById('carouselInner');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const indicators = document.querySelectorAll('.gallery-carousel-indicator');
        const currentSlideNum = document.getElementById('currentSlideNum');
        const carouselContainer = document.getElementById('galleryCarousel');

        if (!carouselInner || !prevBtn || !nextBtn) return;

        let currentSlideIndex = 0;
        const totalSlides = indicators.length;
        let autoSlideTimer = null;

        function updateCarousel() {
            carouselInner.style.transform = 'translateX(-' + (currentSlideIndex * 100) + '%)';

            // Update indicators
            indicators.forEach(function(ind, index) {
                if (index === currentSlideIndex) {
                    ind.classList.add('active');
                } else {
                    ind.classList.remove('active');
                }
            });

            // Update counter
            if (currentSlideNum) {
                currentSlideNum.textContent = currentSlideIndex + 1;
            }
        }

        function nextSlide() {
            currentSlideIndex = (currentSlideIndex + 1) % totalSlides;
            updateCarousel();
        }

        function prevSlide() {
            currentSlideIndex = (currentSlideIndex - 1 + totalSlides) % totalSlides;
            updateCarousel();
        }

        function goToSlide(index) {
            currentSlideIndex = index;
            updateCarousel();
            resetAutoSlide();
        }

        function startAutoSlide() {
            if (autoSlideTimer) clearInterval(autoSlideTimer);
            autoSlideTimer = setInterval(function() {
                nextSlide();
            }, 3000); // Auto slide every 3 seconds
        }

        function resetAutoSlide() {
            if (autoSlideTimer) clearInterval(autoSlideTimer);
            startAutoSlide();
        }

        function stopAutoSlide() {
            if (autoSlideTimer) clearInterval(autoSlideTimer);
        }

        // Event listeners for buttons
        prevBtn.addEventListener('click', function() {
            prevSlide();
            resetAutoSlide();
        });

        nextBtn.addEventListener('click', function() {
            nextSlide();
            resetAutoSlide();
        });

        // Event listeners for indicators
        indicators.forEach(function(indicator, index) {
            indicator.addEventListener('click', function() {
                goToSlide(index);
            });
        });

        // Pause on hover
        if (carouselContainer) {
            carouselContainer.addEventListener('mouseenter', stopAutoSlide);
            carouselContainer.addEventListener('mouseleave', startAutoSlide);
        }

        // Start auto sliding
        startAutoSlide();
    });
</script>
@endsection
