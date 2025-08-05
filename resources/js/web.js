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
        this.toggleWishlist(productId);
    },

    // Toggle wishlist function
    toggleWishlist: function(productId) {
        fetch(`/wishlist/toggle/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    throw new Error('Для добавления в избранное необходимо войти в систему');
                }
                throw new Error('Ошибка сервера');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const action = data.action; // 'added' or 'removed'
                const message = action === 'added' ?
                    'Товар добавлен в избранное!' :
                    'Товар удален из избранного!';

                this.showNotification(message, 'success');

                // Update wishlist badge
                this.updateWishlistBadge();

                // Update wishlist button appearance
                this.updateWishlistButton(productId, action === 'added');
            } else {
                this.showNotification(data.message || 'Ошибка при обновлении избранного', 'error');
            }
        })
        .catch(error => {
            console.error('Wishlist error:', error);
            if (error.message.includes('войти в систему')) {
                this.showNotification(error.message, 'warning');
                // Redirect to login after short delay
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else {
                this.showNotification('Произошла ошибка. Попробуйте еще раз.', 'error');
            }
        });
    },

    // Remove from wishlist
    removeFromWishlist: function(productId) {
        fetch(`/wishlist/remove/${productId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Ошибка сервера');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                this.showNotification('Товар удален из избранного!', 'success');

                // Update wishlist badge
                this.updateWishlistBadge();

                // Remove product from wishlist page if we're on it
                const productElement = document.querySelector(`[data-product-id="${productId}"]`);
                if (productElement && window.location.pathname.includes('/wishlist')) {
                    productElement.remove();
                }

                // Update wishlist button appearance
                this.updateWishlistButton(productId, false);
            } else {
                this.showNotification(data.message || 'Ошибка при удалении из избранного', 'error');
            }
        })
        .catch(error => {
            console.error('Wishlist removal error:', error);
            this.showNotification('Произошла ошибка. Попробуйте еще раз.', 'error');
        });
    },

    // Update wishlist badge
    updateWishlistBadge: function() {
        fetch('/wishlist/count', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            const wishlistBadge = document.querySelector('.wishlist-badge');
            if (wishlistBadge && data.count !== undefined) {
                wishlistBadge.textContent = data.count;
                wishlistBadge.style.display = data.count > 0 ? 'inline' : 'none';
            }
        })
        .catch(error => {
            console.error('Error updating wishlist badge:', error);
        });
    },

    // Update wishlist button appearance
    updateWishlistButton: function(productId, isInWishlist) {
        const buttons = document.querySelectorAll(`[data-wishlist-product="${productId}"]`);
        buttons.forEach(button => {
            const icon = button.querySelector('i') || button.querySelector('svg');
            if (icon) {
                if (isInWishlist) {
                    icon.classList.remove('far');
                    icon.classList.add('fas', 'text-red-500');
                } else {
                    icon.classList.remove('fas', 'text-red-500');
                    icon.classList.add('far');
                }
            }

            // Update title
            button.title = isInWishlist ? 'Удалить из избранного' : 'Добавить в избранное';
        });
    },

    // Clear wishlist
    clearWishlist: function() {
        if (!confirm('Вы уверены, что хотите очистить список избранного?')) {
            return;
        }

        fetch('/wishlist/clear', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Ошибка сервера');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                this.showNotification('Список избранного очищен!', 'success');

                // Update wishlist badge
                this.updateWishlistBadge();

                // Reload wishlist page if we're on it
                if (window.location.pathname.includes('/wishlist')) {
                    window.location.reload();
                }
            } else {
                this.showNotification(data.message || 'Ошибка при очистке списка', 'error');
            }
        })
        .catch(error => {
            console.error('Clear wishlist error:', error);
            this.showNotification('Произошла ошибка. Попробуйте еще раз.', 'error');
        });
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

    // Add to wishlist buttons and wishlist toggle buttons
    document.addEventListener('click', function(e) {
        // Handle wishlist toggle buttons (with data-wishlist-product attribute)
        if (e.target.matches('[data-wishlist-product]') || e.target.closest('[data-wishlist-product]')) {
            e.preventDefault();
            const button = e.target.matches('[data-wishlist-product]') ? e.target : e.target.closest('[data-wishlist-product]');
            const productId = button.getAttribute('data-wishlist-product');

            if (productId) {
                webUtils.toggleWishlist(productId);
            }
        }
        // Handle legacy add to wishlist buttons
        else if (e.target.matches('.add-to-wishlist-btn') || e.target.closest('.add-to-wishlist-btn')) {
            e.preventDefault();
            const button = e.target.matches('.add-to-wishlist-btn') ? e.target : e.target.closest('.add-to-wishlist-btn');
            const productId = button.getAttribute('data-product-id');

            if (productId) {
                webUtils.addToWishlist(productId);
            }
        }
        // Handle wishlist remove buttons
        else if (e.target.matches('.remove-from-wishlist-btn') || e.target.closest('.remove-from-wishlist-btn')) {
            e.preventDefault();
            const button = e.target.matches('.remove-from-wishlist-btn') ? e.target : e.target.closest('.remove-from-wishlist-btn');
            const productId = button.getAttribute('data-product-id');

            if (productId) {
                webUtils.removeFromWishlist(productId);
            }
        }
        // Handle clear wishlist button
        else if (e.target.matches('.clear-wishlist-btn') || e.target.closest('.clear-wishlist-btn')) {
            e.preventDefault();
            webUtils.clearWishlist();
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
