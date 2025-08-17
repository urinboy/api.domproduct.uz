// ===== WEB TEMPLATE JAVASCRIPT =====

// Global variables
let isLoading = false;
let currentModal = null;
let cartCount = 0;
let wishlistCount = 0;
let compareCount = 0;

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
    initializeWebTemplate();
});

// Main initialization function
function initializeWebTemplate() {
    initializeHeader();
    initializeNavigation();
    initializeSearch();
    initializeModals();
    initializeTooltips();
    initializeLazyLoading();
    initializeScrollEffects();
    updateCounts();
    
    // Check authentication status
    checkAuthStatus();
}

// ===== HEADER FUNCTIONS =====
function initializeHeader() {
    const header = document.querySelector('.header');
    const userToggle = document.querySelector('.user-toggle');
    const userDropdown = document.querySelector('.user-dropdown');
    const langToggle = document.querySelector('.lang-toggle');
    const langDropdown = document.querySelector('.lang-dropdown');
    
    // Sticky header
    let lastScrollTop = 0;
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 100) {
            header.classList.add('header-sticky');
        } else {
            header.classList.remove('header-sticky');
        }
        
        // Hide header on scroll down, show on scroll up
        if (scrollTop > lastScrollTop && scrollTop > 200) {
            header.classList.add('header-hidden');
        } else {
            header.classList.remove('header-hidden');
        }
        lastScrollTop = scrollTop;
    });
    
    // User dropdown toggle
    if (userToggle && userDropdown) {
        userToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            userDropdown.classList.toggle('show');
            langDropdown?.classList.remove('show');
        });
    }
    
    // Language dropdown toggle
    if (langToggle && langDropdown) {
        langToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            langDropdown.classList.toggle('show');
            userDropdown?.classList.remove('show');
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        userDropdown?.classList.remove('show');
        langDropdown?.classList.remove('show');
    });
}

// ===== NAVIGATION FUNCTIONS =====
function initializeNavigation() {
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    const mobileOverlay = document.querySelector('.mobile-nav-overlay');
    const megaMenus = document.querySelectorAll('.nav-item.has-dropdown');
    
    // Mobile menu toggle
    if (mobileToggle && mobileOverlay) {
        mobileToggle.addEventListener('click', function() {
            document.body.classList.toggle('mobile-menu-open');
            mobileOverlay.classList.toggle('show');
        });
        
        // Close mobile menu
        mobileOverlay.addEventListener('click', function(e) {
            if (e.target === mobileOverlay) {
                closeMobileMenu();
            }
        });
        
        // Close button
        const closeBtn = mobileOverlay.querySelector('.close-mobile-menu');
        if (closeBtn) {
            closeBtn.addEventListener('click', closeMobileMenu);
        }
    }
    
    // Mega menu functionality
    megaMenus.forEach(item => {
        const dropdown = item.querySelector('.mega-menu');
        let timeout;
        
        item.addEventListener('mouseenter', function() {
            clearTimeout(timeout);
            closeAllMegaMenus();
            dropdown.classList.add('show');
            item.classList.add('active');
        });
        
        item.addEventListener('mouseleave', function() {
            timeout = setTimeout(() => {
                dropdown.classList.remove('show');
                item.classList.remove('active');
            }, 100);
        });
    });
}

function closeMobileMenu() {
    document.body.classList.remove('mobile-menu-open');
    document.querySelector('.mobile-nav-overlay')?.classList.remove('show');
}

function closeAllMegaMenus() {
    document.querySelectorAll('.mega-menu.show').forEach(menu => {
        menu.classList.remove('show');
        menu.closest('.nav-item').classList.remove('active');
    });
}

// ===== SEARCH FUNCTIONS =====
function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchSuggestions = document.querySelector('.search-suggestions');
    const searchForm = document.getElementById('searchForm');
    let searchTimeout;
    
    if (!searchInput) return;
    
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length >= 2) {
            searchTimeout = setTimeout(() => {
                showSearchSuggestions(query);
            }, 300);
        } else {
            hideSearchSuggestions();
        }
    });
    
    searchInput.addEventListener('focus', function() {
        const query = this.value.trim();
        if (query.length >= 2) {
            showSearchSuggestions(query);
        }
    });
    
    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-wrapper')) {
            hideSearchSuggestions();
        }
    });
    
    // Handle search form submission
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = searchInput.value.trim();
            if (query) {
                performSearch(query);
            }
        });
    }
}

function showSearchSuggestions(query) {
    const suggestions = document.querySelector('.search-suggestions');
    if (!suggestions) return;
    
    // Show loading
    suggestions.innerHTML = `
        <div class="search-loading">
            <i class="fas fa-spinner fa-spin"></i>
            <span>Qidirilmoqda...</span>
        </div>
    `;
    suggestions.classList.add('show');
    
    // Fetch suggestions
    fetch(`/api/search/suggestions?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderSearchSuggestions(data.suggestions);
            } else {
                suggestions.innerHTML = '<div class="no-suggestions">Hech narsa topilmadi</div>';
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            suggestions.innerHTML = '<div class="search-error">Xatolik yuz berdi</div>';
        });
}

function renderSearchSuggestions(suggestions) {
    const container = document.querySelector('.search-suggestions');
    if (!container || !suggestions) return;
    
    let html = '';
    
    // Products
    if (suggestions.products?.length > 0) {
        html += '<div class="suggestions-group">';
        html += '<h4 class="suggestions-title">Mahsulotlar</h4>';
        suggestions.products.forEach(product => {
            html += `
                <div class="suggestion-item product-suggestion">
                    <img src="${product.image}" alt="${product.name}" class="suggestion-image">
                    <div class="suggestion-content">
                        <div class="suggestion-name">${product.name}</div>
                        <div class="suggestion-price">${formatPrice(product.price)} so'm</div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
    }
    
    // Categories
    if (suggestions.categories?.length > 0) {
        html += '<div class="suggestions-group">';
        html += '<h4 class="suggestions-title">Kategoriyalar</h4>';
        suggestions.categories.forEach(category => {
            html += `
                <div class="suggestion-item category-suggestion">
                    <i class="fas fa-folder suggestion-icon"></i>
                    <span class="suggestion-name">${category.name}</span>
                </div>
            `;
        });
        html += '</div>';
    }
    
    // Popular searches
    if (suggestions.popular?.length > 0) {
        html += '<div class="suggestions-group">';
        html += '<h4 class="suggestions-title">Mashhur qidiruvlar</h4>';
        suggestions.popular.forEach(term => {
            html += `
                <div class="suggestion-item popular-suggestion">
                    <i class="fas fa-search suggestion-icon"></i>
                    <span class="suggestion-name">${term}</span>
                </div>
            `;
        });
        html += '</div>';
    }
    
    container.innerHTML = html;
    
    // Add click handlers
    container.querySelectorAll('.suggestion-item').forEach(item => {
        item.addEventListener('click', function() {
            const name = this.querySelector('.suggestion-name').textContent;
            document.getElementById('searchInput').value = name;
            performSearch(name);
            hideSearchSuggestions();
        });
    });
}

function hideSearchSuggestions() {
    const suggestions = document.querySelector('.search-suggestions');
    if (suggestions) {
        suggestions.classList.remove('show');
    }
}

function performSearch(query) {
    window.location.href = `/search?q=${encodeURIComponent(query)}`;
}

// ===== MODAL FUNCTIONS =====
function initializeModals() {
    // Close modal handlers
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            closeModal(e.target.closest('.modal'));
        }
        
        if (e.target.classList.contains('modal-close') || e.target.closest('.modal-close')) {
            closeModal(e.target.closest('.modal'));
        }
    });
    
    // Escape key handler
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && currentModal) {
            closeModal(currentModal);
        }
    });
}

function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    
    currentModal = modal;
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Focus on first input
    const firstInput = modal.querySelector('input, textarea, select');
    if (firstInput) {
        setTimeout(() => firstInput.focus(), 100);
    }
}

function closeModal(modal) {
    if (!modal) return;
    
    modal.classList.remove('show');
    document.body.style.overflow = '';
    currentModal = null;
}

function showAuthModal(tab = 'login') {
    showModal('authModal');
    switchAuthTab(tab);
}

function switchAuthTab(tab) {
    const loginTab = document.getElementById('loginTab');
    const registerTab = document.getElementById('registerTab');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    
    if (tab === 'login') {
        loginTab?.classList.add('active');
        registerTab?.classList.remove('active');
        loginForm?.classList.add('active');
        registerForm?.classList.remove('active');
    } else {
        loginTab?.classList.remove('active');
        registerTab?.classList.add('active');
        loginForm?.classList.remove('active');
        registerForm?.classList.add('active');
    }
}

function showQuickViewModal(html) {
    const modal = document.getElementById('quickViewModal');
    if (!modal) return;
    
    const content = modal.querySelector('.modal-content');
    if (content) {
        content.innerHTML = html;
    }
    
    showModal('quickViewModal');
}

// ===== UTILITY FUNCTIONS =====
function showLoading(message = 'Yuklanmoqda...') {
    if (isLoading) return;
    
    isLoading = true;
    const loader = document.createElement('div');
    loader.id = 'globalLoader';
    loader.className = 'global-loader';
    loader.innerHTML = `
        <div class="loader-overlay">
            <div class="loader-content">
                <div class="spinner"></div>
                <div class="loader-text">${message}</div>
            </div>
        </div>
    `;
    
    document.body.appendChild(loader);
    setTimeout(() => loader.classList.add('show'), 10);
}

function hideLoading() {
    isLoading = false;
    const loader = document.getElementById('globalLoader');
    if (loader) {
        loader.classList.remove('show');
        setTimeout(() => loader.remove(), 300);
    }
}

function showToast(message, type = 'info', duration = 5000) {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas ${icons[type]} toast-icon"></i>
            <div class="toast-message">${message}</div>
            <button class="toast-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="toast-progress"></div>
    `;
    
    // Add to container
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    
    container.appendChild(toast);
    
    // Show animation
    setTimeout(() => toast.classList.add('show'), 10);
    
    // Progress bar animation
    const progress = toast.querySelector('.toast-progress');
    progress.style.animationDuration = `${duration}ms`;
    
    // Auto remove
    const autoRemove = setTimeout(() => {
        removeToast(toast);
    }, duration);
    
    // Close button
    toast.querySelector('.toast-close').addEventListener('click', function() {
        clearTimeout(autoRemove);
        removeToast(toast);
    });
    
    // Click to close
    toast.addEventListener('click', function() {
        clearTimeout(autoRemove);
        removeToast(toast);
    });
}

function removeToast(toast) {
    toast.classList.remove('show');
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 300);
}

function formatPrice(price) {
    return parseInt(price).toLocaleString('uz-UZ');
}

function formatCurrency(amount, currency = 'UZS') {
    return new Intl.NumberFormat('uz-UZ', {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

function formatDate(date) {
    return new Date(date).toLocaleDateString('uz-UZ', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// ===== AUTHENTICATION =====
function checkAuthStatus() {
    fetch('/api/auth/status')
        .then(response => response.json())
        .then(data => {
            if (data.authenticated) {
                updateUserMenu(data.user);
                updateCounts(data.counts);
            } else {
                showGuestMenu();
            }
        })
        .catch(error => {
            console.error('Auth check error:', error);
        });
}

function updateUserMenu(user) {
    const userInfo = document.querySelector('.user-info');
    if (userInfo) {
        userInfo.innerHTML = `
            <div class="user-avatar">
                <img src="${user.avatar || '/images/default-avatar.png'}" alt="${user.name}">
            </div>
            <div class="user-details">
                <div class="user-name">${user.name}</div>
                <div class="user-email">${user.email}</div>
            </div>
        `;
    }
}

function showGuestMenu() {
    const userActions = document.querySelector('.user-actions');
    if (userActions) {
        userActions.innerHTML = `
            <a href="#" onclick="showAuthModal('login')" class="btn btn-outline btn-sm">Kirish</a>
            <a href="#" onclick="showAuthModal('register')" class="btn btn-primary btn-sm">Ro'yxatdan o'tish</a>
        `;
    }
}

function isAuthenticated() {
    return document.body.classList.contains('authenticated');
}

// ===== COUNTS UPDATE =====
function updateCounts(counts) {
    if (counts) {
        updateCartCount(counts.cart || 0);
        updateWishlistCount(counts.wishlist || 0);
        updateCompareCount(counts.compare || 0);
    }
}

function updateCartCount(count) {
    cartCount = count;
    const counters = document.querySelectorAll('.cart-count, .mobile-cart-count');
    counters.forEach(counter => {
        counter.textContent = count;
        counter.style.display = count > 0 ? 'inline-flex' : 'none';
    });
}

function updateWishlistCount(count) {
    wishlistCount = count;
    const counters = document.querySelectorAll('.wishlist-count');
    counters.forEach(counter => {
        counter.textContent = count;
        counter.style.display = count > 0 ? 'inline-flex' : 'none';
    });
}

function updateCompareCount(count) {
    compareCount = count;
    const counters = document.querySelectorAll('.compare-count');
    counters.forEach(counter => {
        counter.textContent = count;
        counter.style.display = count > 0 ? 'inline-flex' : 'none';
    });
}

// ===== LAZY LOADING =====
function initializeLazyLoading() {
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        observer.unobserve(img);
                    }
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
}

// ===== SCROLL EFFECTS =====
function initializeScrollEffects() {
    if ('IntersectionObserver' in window) {
        const animationObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            animationObserver.observe(el);
        });
    }
}

// ===== TOOLTIPS =====
function initializeTooltips() {
    const tooltipTriggers = document.querySelectorAll('[data-tooltip]');
    
    tooltipTriggers.forEach(trigger => {
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = trigger.dataset.tooltip;
        
        trigger.addEventListener('mouseenter', function() {
            document.body.appendChild(tooltip);
            
            const rect = trigger.getBoundingClientRect();
            const tooltipRect = tooltip.getBoundingClientRect();
            
            tooltip.style.left = `${rect.left + (rect.width - tooltipRect.width) / 2}px`;
            tooltip.style.top = `${rect.top - tooltipRect.height - 10}px`;
            
            tooltip.classList.add('show');
        });
        
        trigger.addEventListener('mouseleave', function() {
            tooltip.classList.remove('show');
            setTimeout(() => {
                if (tooltip.parentNode) {
                    tooltip.parentNode.removeChild(tooltip);
                }
            }, 300);
        });
    });
}

// ===== EVENT HANDLERS =====

// Language change
function changeLanguage(lang) {
    showLoading('Til o\'zgartirilmoqda...');
    
    fetch('/language/change', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ language: lang })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            window.location.reload();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showToast('Xatolik yuz berdi', 'error');
        console.error('Language change error:', error);
    });
}

// Logout
function logout() {
    if (!confirm('Hisobdan chiqishni xohlaysizmi?')) {
        return;
    }
    
    showLoading('Chiqilmoqda...');
    
    fetch('/logout', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            window.location.href = '/';
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showToast('Xatolik yuz berdi', 'error');
        console.error('Logout error:', error);
    });
}

// ===== KEYBOARD SHORTCUTS =====
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K for search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.focus();
        }
    }
    
    // ESC to close modals
    if (e.key === 'Escape') {
        if (currentModal) {
            closeModal(currentModal);
        }
        hideSearchSuggestions();
        closeMobileMenu();
        closeAllMegaMenus();
    }
});

// ===== ERROR HANDLING =====
window.addEventListener('error', function(e) {
    console.error('JavaScript error:', e.error);
    
    // Don't show toast for network errors or minor issues
    if (e.error && e.error.name !== 'NetworkError') {
        showToast('Dasturda xatolik yuz berdi', 'error');
    }
});

// ===== PERFORMANCE MONITORING =====
window.addEventListener('load', function() {
    // Mark page as loaded
    document.body.classList.add('page-loaded');
    
    // Performance metrics
    if ('performance' in window) {
        const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
        console.log(`Page loaded in ${loadTime}ms`);
    }
});

// ===== OFFLINE DETECTION =====
window.addEventListener('offline', function() {
    showToast('Internet aloqasi uzildi', 'warning', 10000);
});

window.addEventListener('online', function() {
    showToast('Internet aloqasi qayta tiklandi', 'success');
});

// ===== EXPORT FOR GLOBAL USE =====
window.WebTemplate = {
    showModal,
    closeModal,
    showAuthModal,
    showLoading,
    hideLoading,
    showToast,
    updateCartCount,
    updateWishlistCount,
    updateCompareCount,
    formatPrice,
    formatCurrency,
    formatDate,
    isAuthenticated,
    changeLanguage,
    logout
};
