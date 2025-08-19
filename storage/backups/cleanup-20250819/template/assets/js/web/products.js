// ===== PRODUCTS PAGE JAVASCRIPT =====

// Global variables for products page
let currentFilters = {};
let productsPerPage = 12;
let currentSort = 'featured';
let currentView = 'grid';
let isLoadingProducts = false;

// Initialize products page
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.products-page')) {
        initializeProductsPage();
    }
});

function initializeProductsPage() {
    initializeFilters();
    initializePriceRange();
    initializeProductActions();
    updateActiveFilters();
    
    // Parse current URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    currentFilters = Object.fromEntries(urlParams);
    
    // Set initial states
    currentView = urlParams.get('view') || 'grid';
    currentSort = urlParams.get('sort') || 'featured';
    productsPerPage = urlParams.get('per_page') || 12;
    
    // Handle filter changes
    document.addEventListener('change', handleFilterChange);
    
    // Handle mobile sidebar
    initializeMobileSidebar();
}

// ===== FILTER FUNCTIONS =====
function initializeFilters() {
    const filterGroups = document.querySelectorAll('.filter-group');
    
    filterGroups.forEach(group => {
        const title = group.querySelector('.filter-title');
        const content = group.querySelector('.filter-content');
        
        if (title && content) {
            title.addEventListener('click', function() {
                toggleFilterGroup(group);
            });
        }
    });
    
    // Initialize brand search
    const brandSearch = document.querySelector('.brand-search');
    if (brandSearch) {
        brandSearch.addEventListener('input', function() {
            searchBrands(this.value);
        });
    }
}

function toggleFilterGroup(group) {
    group.classList.toggle('collapsed');
    
    const icon = group.querySelector('.toggle-icon');
    if (icon) {
        icon.style.transform = group.classList.contains('collapsed') ? 
            'rotate(-90deg)' : 'rotate(0deg)';
    }
    
    // Save state to localStorage
    const groupId = group.querySelector('.filter-title').textContent.trim();
    const isCollapsed = group.classList.contains('collapsed');
    localStorage.setItem(`filter_${groupId}_collapsed`, isCollapsed);
}

function initializePriceRange() {
    const minSlider = document.getElementById('priceMin');
    const maxSlider = document.getElementById('priceMax');
    const minInput = document.getElementById('priceMinInput');
    const maxInput = document.getElementById('priceMaxInput');
    
    if (!minSlider || !maxSlider) return;
    
    let isUpdating = false;
    
    function updatePriceRange() {
        if (isUpdating) return;
        isUpdating = true;
        
        let minVal = parseInt(minSlider.value);
        let maxVal = parseInt(maxSlider.value);
        
        // Ensure min is less than max
        if (minVal >= maxVal) {
            minVal = maxVal - 100000;
            minSlider.value = minVal;
        }
        
        // Update inputs
        minInput.value = formatNumber(minVal);
        maxInput.value = formatNumber(maxVal);
        
        // Update visual range track
        updateRangeTrack(minVal, maxVal);
        
        isUpdating = false;
    }
    
    function updateRangeTrack(min, max) {
        const rangeTrack = document.querySelector('.range-track');
        if (!rangeTrack) return;
        
        const minPercent = (min / maxSlider.max) * 100;
        const maxPercent = (max / maxSlider.max) * 100;
        
        rangeTrack.style.left = minPercent + '%';
        rangeTrack.style.width = (maxPercent - minPercent) + '%';
    }
    
    // Event listeners
    minSlider.addEventListener('input', updatePriceRange);
    maxSlider.addEventListener('input', updatePriceRange);
    
    minInput.addEventListener('change', function() {
        const value = parseInt(this.value.replace(/\D/g, ''));
        if (!isNaN(value)) {
            minSlider.value = value;
            updatePriceRange();
        }
    });
    
    maxInput.addEventListener('change', function() {
        const value = parseInt(this.value.replace(/\D/g, ''));
        if (!isNaN(value)) {
            maxSlider.value = value;
            updatePriceRange();
        }
    });
    
    // Format number inputs
    [minInput, maxInput].forEach(input => {
        input.addEventListener('input', function() {
            const value = this.value.replace(/\D/g, '');
            this.value = formatNumber(value);
        });
    });
    
    // Initialize
    updatePriceRange();
}

function handleFilterChange(event) {
    const input = event.target;
    const isMobile = window.innerWidth <= 768;
    
    if (input.closest('.products-sidebar')) {
        if (input.type === 'checkbox' || input.type === 'radio') {
            if (isMobile) {
                // Auto-apply filters on mobile
                debounce(applyFilters, 500)();
            } else {
                // Show apply button on desktop
                updateApplyButton();
            }
        }
    }
}

function updateApplyButton() {
    const applyBtn = document.querySelector('.apply-filters');
    if (applyBtn && !applyBtn.classList.contains('has-changes')) {
        applyBtn.classList.add('has-changes');
        applyBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Filtrlarni yangilash';
    }
}

function applyFilters() {
    if (isLoadingProducts) return;
    
    const filterData = collectFilterData();
    const queryString = buildQueryString(filterData);
    const newUrl = window.location.pathname + '?' + queryString;
    
    // Update URL
    window.history.pushState({ filters: filterData }, '', newUrl);
    
    // Load products
    loadProducts(newUrl);
    
    // Update current filters
    currentFilters = filterData;
}

function collectFilterData() {
    const data = {};
    
    // Price range
    const minPrice = document.getElementById('priceMinInput');
    const maxPrice = document.getElementById('priceMaxInput');
    
    if (minPrice && minPrice.value) {
        const min = parseInt(minPrice.value.replace(/\D/g, ''));
        if (min > 0) data.price_min = min;
    }
    
    if (maxPrice && maxPrice.value) {
        const max = parseInt(maxPrice.value.replace(/\D/g, ''));
        if (max < 50000000) data.price_max = max;
    }
    
    // Checkboxes and radios
    document.querySelectorAll('.products-sidebar input:checked').forEach(input => {
        const name = input.name;
        const value = input.value;
        
        if (name.includes('[]')) {
            // Array fields
            const cleanName = name.replace('[]', '');
            if (!data[cleanName]) data[cleanName] = [];
            data[cleanName].push(value);
        } else {
            // Single fields
            data[name] = value;
        }
    });
    
    // Preserve current view settings
    if (currentView !== 'grid') data.view = currentView;
    if (currentSort !== 'featured') data.sort = currentSort;
    if (productsPerPage !== 12) data.per_page = productsPerPage;
    
    return data;
}

function buildQueryString(data) {
    const params = new URLSearchParams();
    
    Object.entries(data).forEach(([key, value]) => {
        if (Array.isArray(value)) {
            value.forEach(v => params.append(key + '[]', v));
        } else {
            params.append(key, value);
        }
    });
    
    return params.toString();
}

function loadProducts(url, append = false) {
    if (isLoadingProducts) return;
    
    isLoadingProducts = true;
    const container = document.getElementById('productsContainer');
    const loading = document.getElementById('productsLoading');
    
    // Show loading
    if (append) {
        loading.style.display = 'block';
    } else {
        container.style.opacity = '0.5';
        container.style.pointerEvents = 'none';
    }
    
    // Fetch products
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (append) {
                // Append to existing products
                container.insertAdjacentHTML('beforeend', data.html);
            } else {
                // Replace products
                container.innerHTML = data.html;
            }
            
            // Update pagination
            updatePagination(data.pagination);
            
            // Update results info
            updateResultsInfo(data.results_info);
            
            // Update active filters display
            updateActiveFilters();
            
            // Reinitialize product actions
            initializeProductActions();
            
            // Reset apply button
            resetApplyButton();
            
            showToast(data.message || 'Mahsulotlar yangilandi', 'success');
        } else {
            showToast(data.message || 'Xatolik yuz berdi', 'error');
        }
    })
    .catch(error => {
        console.error('Load products error:', error);
        showToast('Xatolik yuz berdi', 'error');
    })
    .finally(() => {
        isLoadingProducts = false;
        container.style.opacity = '1';
        container.style.pointerEvents = 'auto';
        loading.style.display = 'none';
    });
}

function updatePagination(paginationHtml) {
    const paginationContainer = document.querySelector('.products-pagination');
    if (paginationContainer && paginationHtml) {
        paginationContainer.innerHTML = paginationHtml;
    }
}

function updateResultsInfo(info) {
    const resultsInfo = document.querySelector('.results-info');
    if (resultsInfo && info) {
        resultsInfo.textContent = info;
    }
}

function updateActiveFilters() {
    const container = document.getElementById('activeFilters');
    if (!container) return;
    
    const params = new URLSearchParams(window.location.search);
    let filtersHtml = '';
    
    // Price filter
    const priceMin = params.get('price_min');
    const priceMax = params.get('price_max');
    if (priceMin || priceMax) {
        const minText = priceMin ? formatPrice(priceMin) : '0';
        const maxText = priceMax ? formatPrice(priceMax) : '∞';
        filtersHtml += createActiveFilterBadge('Narx', `${minText} - ${maxText} so'm`, ['price_min', 'price_max']);
    }
    
    // Other filters
    params.forEach((value, key) => {
        if (!['price_min', 'price_max', 'sort', 'view', 'per_page', 'page'].includes(key)) {
            const label = getFilterLabel(key);
            const displayValue = getFilterDisplayValue(key, value);
            filtersHtml += createActiveFilterBadge(label, displayValue, [key]);
        }
    });
    
    container.innerHTML = filtersHtml;
}

function createActiveFilterBadge(label, value, removeKeys) {
    return `
        <div class="active-filter">
            <span class="filter-label">${label}: ${value}</span>
            <button onclick="removeFilters(${JSON.stringify(removeKeys)})" class="remove-filter">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
}

function getFilterLabel(key) {
    const labels = {
        'brands': 'Brend',
        'categories': 'Kategoriya', 
        'in_stock': 'Mavjudlik',
        'on_sale': 'Chegirma',
        'free_shipping': 'Yetkazib berish',
        'installment': 'To\'lov',
        'rating': 'Baho'
    };
    
    return labels[key] || key.charAt(0).toUpperCase() + key.slice(1);
}

function getFilterDisplayValue(key, value) {
    // Get display name for filter values
    const element = document.querySelector(`input[name="${key}"][value="${value}"], input[name="${key}[]"][value="${value}"]`);
    if (element) {
        const label = element.closest('.filter-option').querySelector('.option-label');
        return label ? label.textContent : value;
    }
    return value;
}

function removeFilters(filterKeys) {
    const params = new URLSearchParams(window.location.search);
    
    filterKeys.forEach(key => {
        params.delete(key);
        // Also remove array versions
        const arrayKey = key.includes('[]') ? key : key + '[]';
        params.delete(arrayKey);
    });
    
    const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
    window.history.pushState({}, '', newUrl);
    
    // Update form inputs
    filterKeys.forEach(key => {
        const inputs = document.querySelectorAll(`input[name="${key}"], input[name="${key}[]"]`);
        inputs.forEach(input => input.checked = false);
    });
    
    // Reset price inputs if needed
    if (filterKeys.includes('price_min')) {
        document.getElementById('priceMinInput').value = '0';
        document.getElementById('priceMin').value = 0;
    }
    if (filterKeys.includes('price_max')) {
        document.getElementById('priceMaxInput').value = '50000000';
        document.getElementById('priceMax').value = 50000000;
    }
    
    loadProducts(newUrl);
}

function clearAllFilters() {
    if (!confirm('Barcha filtrlar tozalansinmi?')) {
        return;
    }
    
    const newUrl = window.location.pathname;
    window.history.pushState({}, '', newUrl);
    
    // Reset all form inputs
    document.querySelectorAll('.products-sidebar input[type="checkbox"], .products-sidebar input[type="radio"]')
        .forEach(input => input.checked = false);
    
    // Reset price range
    document.getElementById('priceMinInput').value = '0';
    document.getElementById('priceMaxInput').value = '50000000';
    document.getElementById('priceMin').value = 0;
    document.getElementById('priceMax').value = 50000000;
    
    loadProducts(newUrl);
}

function resetApplyButton() {
    const applyBtn = document.querySelector('.apply-filters');
    if (applyBtn && applyBtn.classList.contains('has-changes')) {
        applyBtn.classList.remove('has-changes');
        applyBtn.innerHTML = '<i class="fas fa-filter"></i> Filtrlarni qo\'llash';
    }
}

// ===== VIEW AND SORT FUNCTIONS =====
function changeView(mode) {
    if (currentView === mode || isLoadingProducts) return;
    
    currentView = mode;
    const params = new URLSearchParams(window.location.search);
    
    if (mode !== 'grid') {
        params.set('view', mode);
    } else {
        params.delete('view');
    }
    
    const newUrl = window.location.pathname + '?' + params.toString();
    window.history.pushState({}, '', newUrl);
    
    // Update view mode buttons
    document.querySelectorAll('.view-mode').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.view === mode);
    });
    
    loadProducts(newUrl);
}

function changeSort(sort) {
    if (currentSort === sort || isLoadingProducts) return;
    
    currentSort = sort;
    const params = new URLSearchParams(window.location.search);
    
    if (sort !== 'featured') {
        params.set('sort', sort);
    } else {
        params.delete('sort');
    }
    
    params.delete('page'); // Reset to first page
    
    const newUrl = window.location.pathname + '?' + params.toString();
    window.history.pushState({}, '', newUrl);
    
    loadProducts(newUrl);
}

function changePerPage(perPage) {
    if (productsPerPage == perPage || isLoadingProducts) return;
    
    productsPerPage = perPage;
    const params = new URLSearchParams(window.location.search);
    
    if (perPage != 12) {
        params.set('per_page', perPage);
    } else {
        params.delete('per_page');
    }
    
    params.delete('page'); // Reset to first page
    
    const newUrl = window.location.pathname + '?' + params.toString();
    window.history.pushState({}, '', newUrl);
    
    loadProducts(newUrl);
}

// ===== MOBILE SIDEBAR =====
function initializeMobileSidebar() {
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.products-sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => toggleSidebar());
        
        // Close sidebar when clicking outside
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    toggleSidebar();
                }
            }
        });
    }
}

function toggleSidebar() {
    const sidebar = document.querySelector('.products-sidebar');
    const isOpen = sidebar.classList.contains('show');
    
    if (isOpen) {
        sidebar.classList.remove('show');
        document.body.style.overflow = '';
    } else {
        sidebar.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

// ===== BRAND SEARCH =====
function searchBrands(query) {
    const brandOptions = document.querySelectorAll('.brand-option');
    const normalizedQuery = query.toLowerCase().trim();
    
    brandOptions.forEach(option => {
        const label = option.querySelector('.option-label').textContent.toLowerCase();
        const matches = label.includes(normalizedQuery);
        option.style.display = matches ? 'flex' : 'none';
    });
}

function toggleBrandsList() {
    const brandList = document.querySelector('.brand-list');
    const toggleBtn = document.querySelector('.show-more-brands');
    
    if (!brandList || !toggleBtn) return;
    
    const isExpanded = brandList.classList.contains('expanded');
    
    if (isExpanded) {
        brandList.classList.remove('expanded');
        toggleBtn.querySelector('.show-text').style.display = 'inline';
        toggleBtn.querySelector('.hide-text').style.display = 'none';
        toggleBtn.querySelector('i').style.transform = 'rotate(0deg)';
    } else {
        brandList.classList.add('expanded');
        toggleBtn.querySelector('.show-text').style.display = 'none';
        toggleBtn.querySelector('.hide-text').style.display = 'inline';
        toggleBtn.querySelector('i').style.transform = 'rotate(180deg)';
    }
}

// ===== PRODUCT ACTIONS =====
function initializeProductActions() {
    // Reinitialize product card actions after AJAX load
    const productCards = document.querySelectorAll('.product-card, .product-card-list');
    
    productCards.forEach(card => {
        // Quantity selectors
        const qtyBtns = card.querySelectorAll('.qty-btn');
        qtyBtns.forEach(btn => {
            btn.removeEventListener('click', handleQuantityChange);
            btn.addEventListener('click', handleQuantityChange);
        });
        
        // Add to cart buttons
        const addToCartBtn = card.querySelector('.add-to-cart-btn');
        if (addToCartBtn) {
            addToCartBtn.removeEventListener('click', handleAddToCart);
            addToCartBtn.addEventListener('click', handleAddToCart);
        }
    });
}

function handleQuantityChange(event) {
    const btn = event.currentTarget;
    const isPlus = btn.classList.contains('plus');
    const change = isPlus ? 1 : -1;
    
    const input = btn.parentNode.querySelector('.qty-input');
    const currentValue = parseInt(input.value) || 1;
    const newValue = Math.max(1, Math.min(parseInt(input.max) || 99, currentValue + change));
    
    input.value = newValue;
}

function handleAddToCart(event) {
    const btn = event.currentTarget;
    const card = btn.closest('.product-card, .product-card-list');
    const productId = btn.onclick.toString().match(/\d+/)[0]; // Extract product ID
    const qtyInput = card.querySelector('.qty-input');
    const quantity = qtyInput ? parseInt(qtyInput.value) : 1;
    
    addToCart(productId, btn, quantity);
}

// ===== UTILITY FUNCTIONS =====
function formatNumber(num) {
    return parseInt(num).toLocaleString('uz-UZ');
}

function formatPrice(price) {
    return parseInt(price).toLocaleString('uz-UZ');
}

function debounce(func, delay) {
    let timeoutId;
    return function (...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(this, args), delay);
    };
}

// ===== RECENTLY VIEWED =====
function clearViewHistory() {
    if (!confirm('Ko\'rish tarixi tozalansinmi?')) {
        return;
    }
    
    fetch('/user/clear-view-history', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const section = document.querySelector('.recently-viewed-section');
            if (section) {
                section.remove();
            }
            showToast('Ko\'rish tarixi tozalandi', 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Clear history error:', error);
        showToast('Xatolik yuz berdi', 'error');
    });
}

// ===== BROWSER NAVIGATION =====
window.addEventListener('popstate', function(event) {
    if (event.state && event.state.filters) {
        currentFilters = event.state.filters;
        loadProducts(window.location.href);
    } else {
        // Simple reload for back/forward navigation
        window.location.reload();
    }
});

// ===== KEYBOARD SHORTCUTS =====
document.addEventListener('keydown', function(event) {
    // ESC to close mobile sidebar
    if (event.key === 'Escape') {
        const sidebar = document.querySelector('.products-sidebar');
        if (sidebar && sidebar.classList.contains('show')) {
            toggleSidebar();
        }
    }
    
    // Ctrl/Cmd + F to focus on brand search
    if ((event.ctrlKey || event.metaKey) && event.key === 'f') {
        const brandSearch = document.querySelector('.brand-search');
        if (brandSearch) {
            event.preventDefault();
            brandSearch.focus();
        }
    }
});

// ===== INFINITE SCROLL (OPTIONAL) =====
function initializeInfiniteScroll() {
    let isLoading = false;
    
    window.addEventListener('scroll', debounce(() => {
        if (isLoading) return;
        
        const { scrollTop, scrollHeight, clientHeight } = document.documentElement;
        
        if (scrollTop + clientHeight >= scrollHeight - 1000) {
            const nextPageUrl = getNextPageUrl();
            if (nextPageUrl) {
                isLoading = true;
                loadProducts(nextPageUrl, true).finally(() => {
                    isLoading = false;
                });
            }
        }
    }, 250));
}

function getNextPageUrl() {
    const nextLink = document.querySelector('.pagination .page-link[rel="next"]');
    return nextLink ? nextLink.href : null;
}

// ===== PERFORMANCE OPTIMIZATION =====
// Lazy load images in newly loaded products
function observeNewImages() {
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });
        
        // Observe new lazy images
        document.querySelectorAll('img[data-src]:not(.observed)').forEach(img => {
            img.classList.add('observed');
            imageObserver.observe(img);
        });
    }
}
