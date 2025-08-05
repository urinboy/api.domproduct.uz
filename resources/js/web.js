/* Web Frontend JavaScript */

// Global utility functions
window.webUtils = {
    // Show notification
    showNotification: function(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg text-white ${
            type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' :
            type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 3000);
    },

    // Format price
    formatPrice: function(price) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(price);
    },

    // Add to cart function
    addToCart: function(productId, quantity = 1) {
        // In a real application, this would make an AJAX request
        console.log(`Adding product ${productId} to cart with quantity ${quantity}`);
        this.showNotification('Товар добавлен в корзину!', 'success');

        // Update cart badge
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            const currentCount = parseInt(cartBadge.textContent) || 0;
            cartBadge.textContent = currentCount + quantity;
        }
    },

    // Add to wishlist function
    addToWishlist: function(productId) {
        console.log(`Adding product ${productId} to wishlist`);
        this.showNotification('Товар добавлен в избранное!', 'success');

        // Update wishlist badge
        const wishlistBadge = document.querySelector('.wishlist-badge');
        if (wishlistBadge) {
            const currentCount = parseInt(wishlistBadge.textContent) || 0;
            wishlistBadge.textContent = currentCount + 1;
        }
    },

    // Newsletter subscription
    subscribeNewsletter: function(email) {
        if (!email) {
            this.showNotification('Пожалуйста, введите email адрес', 'error');
            return;
        }

        // Simple email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            this.showNotification('Пожалуйста, введите корректный email адрес', 'error');
            return;
        }

        // In a real application, this would make an AJAX request
        console.log(`Subscribing email: ${email}`);
        this.showNotification('Вы успешно подписались на рассылку!', 'success');
    },

    // Search functionality
    performSearch: function(query) {
        if (!query.trim()) {
            this.showNotification('Введите поисковый запрос', 'warning');
            return;
        }

        // Redirect to search page
        window.location.href = `/search?q=${encodeURIComponent(query.trim())}`;
    },

    // Mobile menu toggle
    toggleMobileMenu: function() {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) {
            mobileMenu.classList.toggle('hidden');
        }
    }
};

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {

    // Mobile menu toggle
    const mobileMenuButton = document.querySelector('[data-mobile-menu-toggle]');
    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', function() {
            webUtils.toggleMobileMenu();
        });
    }

    // Search form submission
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchInput = this.querySelector('input[name="q"]');
            if (searchInput) {
                webUtils.performSearch(searchInput.value);
            }
        });
    }

    // Newsletter subscription
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = this.querySelector('input[name="email"]');
            if (emailInput) {
                webUtils.subscribeNewsletter(emailInput.value);
                emailInput.value = ''; // Clear input
            }
        });
    }

    // Add to cart buttons
    document.addEventListener('click', function(e) {
        if (e.target.matches('.add-to-cart-btn') || e.target.closest('.add-to-cart-btn')) {
            e.preventDefault();
            const button = e.target.matches('.add-to-cart-btn') ? e.target : e.target.closest('.add-to-cart-btn');
            const productId = button.getAttribute('data-product-id');
            const quantity = button.getAttribute('data-quantity') || 1;

            if (productId) {
                webUtils.addToCart(productId, parseInt(quantity));
            }
        }
    });

    // Add to wishlist buttons
    document.addEventListener('click', function(e) {
        if (e.target.matches('.add-to-wishlist-btn') || e.target.closest('.add-to-wishlist-btn')) {
            e.preventDefault();
            const button = e.target.matches('.add-to-wishlist-btn') ? e.target : e.target.closest('.add-to-wishlist-btn');
            const productId = button.getAttribute('data-product-id');

            if (productId) {
                webUtils.addToWishlist(productId);

                // Toggle heart icon
                const heartIcon = button.querySelector('svg');
                if (heartIcon) {
                    heartIcon.classList.toggle('text-gray-600');
                    heartIcon.classList.toggle('text-red-500');
                }
            }
        }
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Lazy loading for images
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
    }
});
