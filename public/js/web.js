/******/ (() => { // webpackBootstrap
/*!*****************************!*\
  !*** ./resources/js/web.js ***!
  \*****************************/
/* Web Frontend JavaScript */

// Global utility functions
window.webUtils = {
  // Show notification
  showNotification: function showNotification(message) {
    var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'success';
    var notification = document.createElement('div');
    notification.className = "fixed top-4 right-4 z-50 p-4 rounded-lg text-white ".concat(type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500');
    notification.textContent = message;
    document.body.appendChild(notification);

    // Auto remove after 3 seconds
    setTimeout(function () {
      if (notification.parentNode) {
        notification.parentNode.removeChild(notification);
      }
    }, 3000);
  },
  // Format price
  formatPrice: function formatPrice(price) {
    return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD'
    }).format(price);
  },
  // Add to cart function
  addToCart: function addToCart(productId) {
    var quantity = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1;
    // In a real application, this would make an AJAX request
    console.log("Adding product ".concat(productId, " to cart with quantity ").concat(quantity));
    this.showNotification('Товар добавлен в корзину!', 'success');

    // Update cart badge
    var cartBadge = document.querySelector('.cart-badge');
    if (cartBadge) {
      var currentCount = parseInt(cartBadge.textContent) || 0;
      cartBadge.textContent = currentCount + quantity;
    }
  },
  // Add to wishlist function
  addToWishlist: function addToWishlist(productId) {
    console.log("Adding product ".concat(productId, " to wishlist"));
    this.showNotification('Товар добавлен в избранное!', 'success');

    // Update wishlist badge
    var wishlistBadge = document.querySelector('.wishlist-badge');
    if (wishlistBadge) {
      var currentCount = parseInt(wishlistBadge.textContent) || 0;
      wishlistBadge.textContent = currentCount + 1;
    }
  },
  // Newsletter subscription
  subscribeNewsletter: function subscribeNewsletter(email) {
    if (!email) {
      this.showNotification('Пожалуйста, введите email адрес', 'error');
      return;
    }

    // Simple email validation
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      this.showNotification('Пожалуйста, введите корректный email адрес', 'error');
      return;
    }

    // In a real application, this would make an AJAX request
    console.log("Subscribing email: ".concat(email));
    this.showNotification('Вы успешно подписались на рассылку!', 'success');
  },
  // Search functionality
  performSearch: function performSearch(query) {
    if (!query.trim()) {
      this.showNotification('Введите поисковый запрос', 'warning');
      return;
    }

    // Redirect to search page
    window.location.href = "/search?q=".concat(encodeURIComponent(query.trim()));
  },
  // Mobile menu toggle
  toggleMobileMenu: function toggleMobileMenu() {
    var mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenu) {
      mobileMenu.classList.toggle('hidden');
    }
  }
};

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function () {
  // Mobile menu toggle
  var mobileMenuButton = document.querySelector('[data-mobile-menu-toggle]');
  if (mobileMenuButton) {
    mobileMenuButton.addEventListener('click', function () {
      webUtils.toggleMobileMenu();
    });
  }

  // Search form submission
  var searchForm = document.querySelector('.search-form');
  if (searchForm) {
    searchForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var searchInput = this.querySelector('input[name="q"]');
      if (searchInput) {
        webUtils.performSearch(searchInput.value);
      }
    });
  }

  // Newsletter subscription
  var newsletterForm = document.querySelector('.newsletter-form');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var emailInput = this.querySelector('input[name="email"]');
      if (emailInput) {
        webUtils.subscribeNewsletter(emailInput.value);
        emailInput.value = ''; // Clear input
      }
    });
  }

  // Add to cart buttons
  document.addEventListener('click', function (e) {
    if (e.target.matches('.add-to-cart-btn') || e.target.closest('.add-to-cart-btn')) {
      e.preventDefault();
      var button = e.target.matches('.add-to-cart-btn') ? e.target : e.target.closest('.add-to-cart-btn');
      var productId = button.getAttribute('data-product-id');
      var quantity = button.getAttribute('data-quantity') || 1;
      if (productId) {
        webUtils.addToCart(productId, parseInt(quantity));
      }
    }
  });

  // Add to wishlist buttons
  document.addEventListener('click', function (e) {
    if (e.target.matches('.add-to-wishlist-btn') || e.target.closest('.add-to-wishlist-btn')) {
      e.preventDefault();
      var button = e.target.matches('.add-to-wishlist-btn') ? e.target : e.target.closest('.add-to-wishlist-btn');
      var productId = button.getAttribute('data-product-id');
      if (productId) {
        webUtils.addToWishlist(productId);

        // Toggle heart icon
        var heartIcon = button.querySelector('svg');
        if (heartIcon) {
          heartIcon.classList.toggle('text-gray-600');
          heartIcon.classList.toggle('text-red-500');
        }
      }
    }
  });

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      var target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({
          behavior: 'smooth'
        });
      }
    });
  });

  // Lazy loading for images
  if ('IntersectionObserver' in window) {
    var imageObserver = new IntersectionObserver(function (entries, observer) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var img = entry.target;
          img.src = img.dataset.src;
          img.classList.remove('lazy');
          imageObserver.unobserve(img);
        }
      });
    });
    document.querySelectorAll('img[data-src]').forEach(function (img) {
      imageObserver.observe(img);
    });
  }
});
/******/ })()
;