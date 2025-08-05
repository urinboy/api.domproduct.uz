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
    this.toggleWishlist(productId);
  },
  // Toggle wishlist function
  toggleWishlist: function toggleWishlist(productId) {
    var _this = this;
    fetch("/wishlist/toggle/".concat(productId), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    }).then(function (response) {
      if (!response.ok) {
        if (response.status === 401) {
          throw new Error('Для добавления в избранное необходимо войти в систему');
        }
        throw new Error('Ошибка сервера');
      }
      return response.json();
    }).then(function (data) {
      if (data.success) {
        var action = data.action; // 'added' or 'removed'
        var message = action === 'added' ? 'Товар добавлен в избранное!' : 'Товар удален из избранного!';
        _this.showNotification(message, 'success');

        // Update wishlist badge
        _this.updateWishlistBadge();

        // Update wishlist button appearance
        _this.updateWishlistButton(productId, action === 'added');
      } else {
        _this.showNotification(data.message || 'Ошибка при обновлении избранного', 'error');
      }
    })["catch"](function (error) {
      console.error('Wishlist error:', error);
      if (error.message.includes('войти в систему')) {
        _this.showNotification(error.message, 'warning');
        // Redirect to login after short delay
        setTimeout(function () {
          window.location.href = '/login';
        }, 2000);
      } else {
        _this.showNotification('Произошла ошибка. Попробуйте еще раз.', 'error');
      }
    });
  },
  // Remove from wishlist
  removeFromWishlist: function removeFromWishlist(productId) {
    var _this2 = this;
    fetch("/wishlist/remove/".concat(productId), {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    }).then(function (response) {
      if (!response.ok) {
        throw new Error('Ошибка сервера');
      }
      return response.json();
    }).then(function (data) {
      if (data.success) {
        _this2.showNotification('Товар удален из избранного!', 'success');

        // Update wishlist badge
        _this2.updateWishlistBadge();

        // Remove product from wishlist page if we're on it
        var productElement = document.querySelector("[data-product-id=\"".concat(productId, "\"]"));
        if (productElement && window.location.pathname.includes('/wishlist')) {
          productElement.remove();
        }

        // Update wishlist button appearance
        _this2.updateWishlistButton(productId, false);
      } else {
        _this2.showNotification(data.message || 'Ошибка при удалении из избранного', 'error');
      }
    })["catch"](function (error) {
      console.error('Wishlist removal error:', error);
      _this2.showNotification('Произошла ошибка. Попробуйте еще раз.', 'error');
    });
  },
  // Update wishlist badge
  updateWishlistBadge: function updateWishlistBadge() {
    fetch('/wishlist/count', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    }).then(function (response) {
      return response.json();
    }).then(function (data) {
      var wishlistBadge = document.querySelector('.wishlist-badge');
      if (wishlistBadge && data.count !== undefined) {
        wishlistBadge.textContent = data.count;
        wishlistBadge.style.display = data.count > 0 ? 'inline' : 'none';
      }
    })["catch"](function (error) {
      console.error('Error updating wishlist badge:', error);
    });
  },
  // Update wishlist button appearance
  updateWishlistButton: function updateWishlistButton(productId, isInWishlist) {
    var buttons = document.querySelectorAll("[data-wishlist-product=\"".concat(productId, "\"]"));
    buttons.forEach(function (button) {
      var icon = button.querySelector('i') || button.querySelector('svg');
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
  clearWishlist: function clearWishlist() {
    var _this3 = this;
    if (!confirm('Вы уверены, что хотите очистить список избранного?')) {
      return;
    }
    fetch('/wishlist/clear', {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    }).then(function (response) {
      if (!response.ok) {
        throw new Error('Ошибка сервера');
      }
      return response.json();
    }).then(function (data) {
      if (data.success) {
        _this3.showNotification('Список избранного очищен!', 'success');

        // Update wishlist badge
        _this3.updateWishlistBadge();

        // Reload wishlist page if we're on it
        if (window.location.pathname.includes('/wishlist')) {
          window.location.reload();
        }
      } else {
        _this3.showNotification(data.message || 'Ошибка при очистке списка', 'error');
      }
    })["catch"](function (error) {
      console.error('Clear wishlist error:', error);
      _this3.showNotification('Произошла ошибка. Попробуйте еще раз.', 'error');
    });
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

  // Add to wishlist buttons and wishlist toggle buttons
  document.addEventListener('click', function (e) {
    // Handle wishlist toggle buttons (with data-wishlist-product attribute)
    if (e.target.matches('[data-wishlist-product]') || e.target.closest('[data-wishlist-product]')) {
      e.preventDefault();
      var button = e.target.matches('[data-wishlist-product]') ? e.target : e.target.closest('[data-wishlist-product]');
      var productId = button.getAttribute('data-wishlist-product');
      if (productId) {
        webUtils.toggleWishlist(productId);
      }
    }
    // Handle legacy add to wishlist buttons
    else if (e.target.matches('.add-to-wishlist-btn') || e.target.closest('.add-to-wishlist-btn')) {
      e.preventDefault();
      var _button = e.target.matches('.add-to-wishlist-btn') ? e.target : e.target.closest('.add-to-wishlist-btn');
      var _productId = _button.getAttribute('data-product-id');
      if (_productId) {
        webUtils.addToWishlist(_productId);
      }
    }
    // Handle wishlist remove buttons
    else if (e.target.matches('.remove-from-wishlist-btn') || e.target.closest('.remove-from-wishlist-btn')) {
      e.preventDefault();
      var _button2 = e.target.matches('.remove-from-wishlist-btn') ? e.target : e.target.closest('.remove-from-wishlist-btn');
      var _productId2 = _button2.getAttribute('data-product-id');
      if (_productId2) {
        webUtils.removeFromWishlist(_productId2);
      }
    }
    // Handle clear wishlist button
    else if (e.target.matches('.clear-wishlist-btn') || e.target.closest('.clear-wishlist-btn')) {
      e.preventDefault();
      webUtils.clearWishlist();
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