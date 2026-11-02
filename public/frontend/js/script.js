// Add scrolled class on scroll
window.addEventListener('scroll', function() {
    const header = document.querySelector('.site-header');
    if (!header) return;

    if (window.scrollY > 10) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});


    let galleryImages = [];
    let currentIndex = 0;

    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.gallery-item-card img');
        galleryImages = Array.from(cards).map(img => img.getAttribute('src'));

        const filterChips = document.querySelectorAll('.filter-chip');
        filterChips.forEach(chip => {
            chip.addEventListener('click', function() {
                filterChips.forEach(c => c.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;
                const items = document.querySelectorAll('.gallery-item');

                items.forEach(item => {
                    if (filter === 'all' || item.dataset.category === filter) {
                        item.style.display = '';
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'scale(1)';
                        }, 50);
                    } else {
                        item.style.opacity = '0';
                        item.style.transform = 'scale(0.9)';
                        setTimeout(() => item.style.display = 'none', 400);
                    }
                });
            });
        });
    });

    function showLoader() {
        document.getElementById('lightboxLoader')?.classList.add('active');
    }

    function hideLoader() {
        document.getElementById('lightboxLoader')?.classList.remove('active');
    }

    function openLightbox(index) {
        if (!galleryImages.length || index < 0 || index >= galleryImages.length) return;

        currentIndex = index;
        const lightbox = document.getElementById('lightbox');
        const img = document.getElementById('lightboxImg');
        const counter = document.getElementById('lightboxCounter');
        if (!lightbox || !img || !counter) return;

        showLoader();
        img.style.opacity = '0';
        img.src = galleryImages[currentIndex];

        img.onload = function() {
            img.style.opacity = '1';
            hideLoader();
        };

        img.onerror = function() {
            hideLoader();
            img.style.opacity = '1';
        };

        counter.textContent = (currentIndex + 1) + ' / ' + galleryImages.length;
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        if (!lightbox) return;

        lightbox.classList.remove('active');
        document.body.style.overflow = '';

        setTimeout(function() {
            const img = document.getElementById('lightboxImg');
            if (img) img.src = '';
        }, 400);
    }

    function changeImage(dir) {
        if (!galleryImages.length) return;

        currentIndex += dir;
        if (currentIndex < 0) currentIndex = galleryImages.length - 1;
        if (currentIndex >= galleryImages.length) currentIndex = 0;

        const img = document.getElementById('lightboxImg');
        const counter = document.getElementById('lightboxCounter');
        if (!img || !counter) return;

        showLoader();
        img.style.opacity = '0';
        img.src = galleryImages[currentIndex];

        img.onload = function() {
            img.style.opacity = '1';
            hideLoader();
        };

        img.onerror = function() {
            img.style.opacity = '1';
            hideLoader();
        };

        counter.textContent = (currentIndex + 1) + ' / ' + galleryImages.length;
    }

    function downloadImage() {
        const link = document.createElement('a');
        link.href = galleryImages[currentIndex];
        link.download = 'uttarakhand-gallery-' + (currentIndex + 1) + '.jpg';
        link.click();
    }

    function shareImage() {
        if (navigator.share) {
            navigator.share({
                title: 'Uttarakhand Tourism Gallery',
                url: galleryImages[currentIndex]
            });
        } else {
            alert('Share feature not available');
        }
    }

    document.addEventListener('keydown', function(e) {
        const lightbox = document.getElementById('lightbox');
        if (!lightbox || !lightbox.classList.contains('active')) return;

        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') changeImage(-1);
        if (e.key === 'ArrowRight') changeImage(1);
    });

    let touchStartX = 0;
    let touchEndX = 0;

    document.addEventListener('touchstart', function(e) {
        const lightbox = document.getElementById('lightbox');
        if (!lightbox || !lightbox.classList.contains('active')) return;
        touchStartX = e.changedTouches[0].screenX;
    });

    document.addEventListener('touchend', function(e) {
        const lightbox = document.getElementById('lightbox');
        if (!lightbox || !lightbox.classList.contains('active')) return;
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });

    function handleSwipe() {
        var swipeThreshold = 50;
        if (touchEndX < touchStartX - swipeThreshold) changeImage(1);
        if (touchEndX > touchStartX + swipeThreshold) changeImage(-1);
    }



    // show package details modal
