document.addEventListener('DOMContentLoaded', function() {

    // Sidebar Toggle
    const menuToggle = document.getElementById('menu-toggle');
    const wrapper = document.querySelector('.user-dashboard .wrapper');
    const sidebar = document.getElementById('userSidebar');
    const mobileLayout = window.matchMedia('(max-width: 991.98px)');

    if (menuToggle && wrapper && sidebar) {
        const syncSidebar = () => {
            menuToggle.setAttribute('aria-expanded', String(
                mobileLayout.matches
                    ? sidebar.classList.contains('active')
                    : !wrapper.classList.contains('sidebar-collapsed')
            ));
        };

        menuToggle.addEventListener('click', function() {
            if (mobileLayout.matches) {
                sidebar.classList.toggle('active');
            } else {
                wrapper.classList.toggle('sidebar-collapsed');
                localStorage.setItem('userSidebarCollapsed', wrapper.classList.contains('sidebar-collapsed'));
            }
            syncSidebar();
        });

        if (!mobileLayout.matches && localStorage.getItem('userSidebarCollapsed') === 'true') {
            wrapper.classList.add('sidebar-collapsed');
        }

        mobileLayout.addEventListener('change', function(event) {
            sidebar.classList.remove('active');
            wrapper.classList.toggle('sidebar-collapsed', !event.matches && localStorage.getItem('userSidebarCollapsed') === 'true');
            syncSidebar();
        });
        syncSidebar();
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
