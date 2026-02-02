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
        logoutModal.classList.add('open');
        document.body.classList.add('overflow-hidden');
    };

    const closeLogoutModal = () => {
        if (!logoutModal) return;
        logoutModal.classList.remove('open');
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

/**
 * Toast Notification System
 * @param {string} message - Toast message
 * @param {string} type - 'success' | 'error' | 'info' | 'warning'
 * @param {string} title - Optional title
 * @param {number} duration - Duration in ms (default: 3000)
 */
function showToast(message, type = 'info', title = null, duration = 3000) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = 'toast';

    const icons = {
        success: '<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>',
        error: '<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>',
        info: '<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/></svg>',
        warning: '<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>'
    };

    const titleText = title || '';

    toast.innerHTML = `
        <div class="toast-content">
            <div class="toast-icon toast-icon-${type}">
                ${icons[type]}
            </div>
            <div class="toast-message">
                ${titleText ? `<div class="toast-title">${titleText}</div>` : ''}
                ${message ? `<div class="toast-description">${message}</div>` : ''}
            </div>
            <button class="toast-close" onclick="this.closest('.toast').classList.add('hiding'); setTimeout(() => this.closest('.toast').remove(), 300)">
                <svg viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
            </button>
        </div>
    `;

    container.appendChild(toast);

    // Trigger animation
    requestAnimationFrame(() => {
        toast.classList.add('show');
    });

    // Auto dismiss
    if (duration > 0) {
        setTimeout(() => {
            toast.classList.add('hiding');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }
}
