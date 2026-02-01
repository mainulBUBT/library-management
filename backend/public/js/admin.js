/**
 * Admin Panel JavaScript
 * Handles sidebar toggle and mobile menu functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.getElementById('navbar-vertical');
    const overlay = document.getElementById('sidebar-overlay');
    const content = document.getElementById('app-layout-content');
    const navToggle = document.getElementById('nav-toggle');
    const logoutOpen = document.getElementById('logout-open');
    const logoutModal = document.getElementById('logout-modal');
    const logoutOverlay = document.getElementById('logout-modal-overlay');
    const logoutConfirm = document.getElementById('logout-confirm');
    const logoutForm = document.getElementById('logout-form');
    const userMenu = document.getElementById('user-menu');
    const userMenuButton = document.getElementById('user-menu-button');

    // Mobile menu toggle
    if (navToggle) {
        navToggle.addEventListener('click', function(e) {
            e.preventDefault();
            navbar.classList.toggle('open');
            overlay.classList.toggle('open');
        });
    }

    // Close sidebar on overlay click
    if (overlay) {
        overlay.addEventListener('click', function() {
            navbar.classList.remove('open');
            overlay.classList.remove('open');
        });
    }

    // User menu toggle
    if (userMenuButton && userMenu) {
        userMenuButton.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenu.classList.toggle('hidden');
        });

        // Close user menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!userMenu.contains(e.target) && !userMenuButton.contains(e.target)) {
                userMenu.classList.add('hidden');
            }
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 1024) {
            if (!navbar.contains(e.target) && !navToggle.contains(e.target)) {
                navbar.classList.remove('open');
                overlay.classList.remove('open');
            }
        }
    });

    // Logout modal
    const openLogoutModal = () => {
        if (!logoutModal) return;
        logoutModal.classList.remove('hidden');
        logoutModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    };

    const closeLogoutModal = () => {
        if (!logoutModal) return;
        logoutModal.classList.add('hidden');
        logoutModal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    };

    if (logoutOpen) {
        logoutOpen.addEventListener('click', function() {
            // Close user menu dropdown
            if (userMenu && !userMenu.classList.contains('hidden')) {
                userMenu.classList.add('hidden');
            }
            // Open logout confirmation modal
            openLogoutModal();
        });
    }

    if (logoutOverlay) {
        logoutOverlay.addEventListener('click', function() {
            closeLogoutModal();
        });
    }

    if (logoutConfirm) {
        logoutConfirm.addEventListener('click', function() {
            if (logoutForm) {
                logoutForm.submit();
            }
        });
    }

    document.querySelectorAll('[data-logout-close]').forEach(function(button) {
        button.addEventListener('click', function() {
            closeLogoutModal();
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLogoutModal();
            if (userMenu && !userMenu.classList.contains('hidden')) {
                userMenu.classList.add('hidden');
            }
        }
    });
});

/**
 * Toggle sidebar (desktop collapse/expand)
 */
function toggleSidebar() {
    const navbar = document.getElementById('navbar-vertical');
    const content = document.getElementById('app-layout-content');
    const isDesktop = window.innerWidth >= 1024;

    if (isDesktop) {
        content.classList.toggle('collapsed');
    } else {
        navbar.classList.toggle('open');
        document.getElementById('sidebar-overlay').classList.toggle('open');
    }
}

/**
 * Close sidebar (mobile)
 */
function closeSidebar() {
    const navbar = document.getElementById('navbar-vertical');
    const overlay = document.getElementById('sidebar-overlay');

    navbar.classList.remove('open');
    overlay.classList.remove('open');
}
