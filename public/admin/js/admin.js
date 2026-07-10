document.addEventListener('DOMContentLoaded', function() {

    // Sidebar Toggle
    const menuToggle = document.getElementById('menu-toggle');
    const wrapper = document.querySelector('.wrapper');

    if (menuToggle && wrapper) {
        menuToggle.addEventListener('click', function() {
            wrapper.classList.toggle('sidebar-collapsed');

            // Save state to localStorage
            const isCollapsed = wrapper.classList.contains('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        });

        // Restore state
        const savedState = localStorage.getItem('sidebarCollapsed');
        if (savedState === 'true') {
            wrapper.classList.add('sidebar-collapsed');
        }
    }

    // Mobile Sidebar Toggle
    const sidebar = document.querySelector('.sidebar');

    if (window.innerWidth < 992) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }

    // Active Nav Item
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.sidebar-nav li a');

    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.parentElement.classList.add('active');
        }
    });

    // Dropdown Animation Enhancement
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('show.bs.dropdown', function() {
            const menu = this.querySelector('.dropdown-menu');
            menu.style.opacity = '0';
            menu.style.transform = 'translateY(10px)';
            setTimeout(() => {
                menu.style.transition = 'all 0.2s ease';
                menu.style.opacity = '1';
                menu.style.transform = 'translateY(0)';
            }, 10);
        });
    });

});



       function animateCounter(el) {
            const target = parseFloat(el.getAttribute('data-count'));
            const decimal = parseInt(el.getAttribute('data-decimal')) || 0;
            const duration = 2000;
            const start = 0;
            const increment = target / (duration / 16);
            let current = start;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                if (decimal > 0) {
                    el.textContent = current.toFixed(decimal) + (target >= 1000 ? '+' : '');
                } else {
                    el.textContent = Math.floor(current).toLocaleString() + (target >= 1000 ? '+' : '');
                }
            }, 16);
        }

        // Intersection Observer for counters
        const observerOptions = { threshold: 0.5 };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.stat-number').forEach(el => {
            observer.observe(el);
        });

        // Wishlist toggle
        document.querySelectorAll('.hotel-wishlist').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const icon = this.querySelector('i');
                if (icon.classList.contains('fa-regular')) {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');
                    this.style.background = '#ef4444';
                } else {
                    icon.classList.remove('fa-solid');
                    icon.classList.add('fa-regular');
                    this.style.background = 'rgba(255,255,255,0.95)';
                }
            });
        });
