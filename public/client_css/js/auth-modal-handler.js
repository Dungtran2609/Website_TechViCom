// Auth Modal Handler
// This file handles opening the auth modal from various links and routes

(function() {
    'use strict';

    // Function to open auth modal with specific form
    window.openAuthModalWithForm = function(formType, data = {}) {
        // Open the modal first
        if (typeof window.openAuthModal === 'function') {
            window.openAuthModal();
        }

        // Show the appropriate form after a short delay
        setTimeout(() => {
            switch(formType) {
                case 'login':
                    if (typeof window.showLoginForm === 'function') {
                        window.showLoginForm();
                    }
                    break;
                case 'register':
                    if (typeof window.showRegisterForm === 'function') {
                        window.showRegisterForm();
                    }
                    break;
                case 'forgot-password':
                    if (typeof window.showForgotPasswordForm === 'function') {
                        window.showForgotPasswordForm();
                    }
                    break;
                case 'reset-password':
                    if (typeof window.showResetPasswordForm === 'function' && data.token) {
                        window.showResetPasswordForm(data.token, data.email || '');
                    }
                    break;
                case 'confirm-password':
                    if (typeof window.showConfirmPasswordForm === 'function') {
                        window.showConfirmPasswordForm();
                    }
                    break;
                case 'verify-email':
                    if (typeof window.showVerifyEmailForm === 'function') {
                        window.showVerifyEmailForm();
                    }
                    break;
                default:
                    if (typeof window.showLoginForm === 'function') {
                        window.showLoginForm();
                    }
            }
        }, 100);
    };

    // Handle clicks on auth links
    document.addEventListener('click', function(e) {
        const target = e.target.closest('[data-auth-modal]');
        if (target) {
            e.preventDefault();
            const formType = target.getAttribute('data-auth-modal');
            const token = target.getAttribute('data-token') || '';
            const email = target.getAttribute('data-email') || '';
            
            openAuthModalWithForm(formType, { token, email });
        }
    });

    // Handle URL parameters for opening modal
    function handleUrlParams() {
        const urlParams = new URLSearchParams(window.location.search);
        const authModal = urlParams.get('auth_modal');
        const token = urlParams.get('token') || '';
        const email = urlParams.get('email') || '';

        if (authModal) {
            // Remove the parameters from URL
            const newUrl = new URL(window.location);
            newUrl.searchParams.delete('auth_modal');
            newUrl.searchParams.delete('token');
            newUrl.searchParams.delete('email');
            window.history.replaceState({}, '', newUrl);

            // Open modal with form
            openAuthModalWithForm(authModal, { token, email });
        }
    }

    // Handle hash fragments for opening modal
    function handleHashFragment() {
        const hash = window.location.hash;
        if (hash && hash.startsWith('#auth_modal=')) {
            const formType = hash.replace('#auth_modal=', '');
            
            // Remove the hash
            window.history.replaceState({}, '', window.location.pathname + window.location.search);
            
            // Open modal with form
            openAuthModalWithForm(formType);
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            handleUrlParams();
            handleHashFragment();
        });
    } else {
        handleUrlParams();
        handleHashFragment();
    }

    // Handle browser back/forward buttons
    window.addEventListener('popstate', function() {
        handleUrlParams();
        handleHashFragment();
    });

    // Global function to open modal from anywhere
    window.openAuthModalFromLink = function(link) {
        const url = new URL(link);
        const path = url.pathname;
        
        // Map routes to form types
        const routeMap = {
            '/login': 'login',
            '/register': 'register',
            '/forgot-password': 'forgot-password',
            '/confirm-password': 'confirm-password',
            '/verify-email': 'verify-email'
        };

        // Handle reset password route
        if (path.startsWith('/reset-password/')) {
            const token = path.split('/').pop();
            const email = url.searchParams.get('email') || '';
            openAuthModalWithForm('reset-password', { token, email });
            return;
        }

        // Handle other routes
        const formType = routeMap[path];
        if (formType) {
            openAuthModalWithForm(formType);
        }
    };

})();
