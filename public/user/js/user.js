document.addEventListener('DOMContentLoaded', function() {

    // Sidebar Toggle
    const menuToggle = document.getElementById('menu-toggle');
    const wrapper = document.querySelector('.wrapper');

    if (menuToggle && wrapper) {
        menuToggle.addEventListener('click', function() {
            wrapper.classList.toggle('sidebar-collapsed');

            // Save state to localStorage
            const isCollapsed = wrapper.classList.contains('sidebar-collapsed');
            localStorage.setItem('userSidebarCollapsed', isCollapsed);
        });

        // Restore state
        const savedState = localStorage.getItem('userSidebarCollapsed');
        if (savedState === 'true') {
            wrapper.classList.add('sidebar-collapsed');
        }
    }

    // Mobile Sidebar Toggle
    const sidebar = document.querySelector('.user-sidebar');

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
