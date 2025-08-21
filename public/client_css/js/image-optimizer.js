// Image Optimizer for Performance
(function() {
    'use strict';
    
    // Lazy loading for images
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        } else {
            // Fallback for older browsers
            document.querySelectorAll('img[data-src]').forEach(img => {
                img.src = img.dataset.src;
                img.classList.remove('lazy');
            });
        }
    }
    
    // Optimize image loading
    function optimizeImages() {
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            // Add loading="lazy" for images below the fold
            if (!img.hasAttribute('loading')) {
                img.setAttribute('loading', 'lazy');
            }
            
            // Add decoding="async" for better performance
            if (!img.hasAttribute('decoding')) {
                img.setAttribute('decoding', 'async');
            }
            
            // Handle image errors
            img.addEventListener('error', function() {
                this.src = '/client_css/images/placeholder.svg';
                this.classList.add('image-error');
            });
        });
    }
    
    // Preload critical images
    function preloadCriticalImages() {
        const criticalImages = [
            '/client_css/images/logo_techvicom.png',
            '/client_css/images/placeholder.svg'
        ];
        
        criticalImages.forEach(src => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'image';
            link.href = src;
            document.head.appendChild(link);
        });
    }
    
    // Optimize background images
    function optimizeBackgroundImages() {
        const elements = document.querySelectorAll('[style*="background-image"]');
        elements.forEach(el => {
            const style = el.style.backgroundImage;
            if (style && style.includes('url(')) {
                // Add will-change for better performance
                el.style.willChange = 'auto';
            }
        });
    }
    
    // Initialize when DOM is ready
    function init() {
        preloadCriticalImages();
        optimizeImages();
        optimizeBackgroundImages();
        initLazyLoading();
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Export functions for global use
    window.imageOptimizer = {
        initLazyLoading,
        optimizeImages,
        preloadCriticalImages,
        optimizeBackgroundImages
    };
})();
