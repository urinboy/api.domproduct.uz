@extends('web.layouts.app')

@section('title', __('Mahsulotlar'))

@section('content')
<div class="page-header">
    <div class="container">
        <nav class="breadcrumb">
            <a href="{{ route('home') }}" class="breadcrumb-item">{{ __('Bosh sahifa') }}</a>
            <span class="breadcrumb-separator">/</span>
            @if(request('category'))
                <a href="{{ route('category', request('category')) }}" class="breadcrumb-item">
                    {{ $category->name ?? __('Kategoriya') }}
                </a>
                <span class="breadcrumb-separator">/</span>
            @endif
            <span class="breadcrumb-item active">{{ __('Mahsulotlar') }}</span>
        </nav>
        
        <div class="page-title-section">
            <h1 class="page-title">
                @if(request('category'))
                    {{ $category->name ?? __('Kategoriya') }}
                @elseif(request('search'))
                    "{{ request('search') }}" {{ __('bo\'yicha qidiruv natijalari') }}
                @elseif(request('brand'))
                    {{ $brand->name ?? __('Brend') }} {{ __('mahsulotlari') }}
                @else
                    {{ __('Barcha mahsulotlar') }}
                @endif
            </h1>
            
            @if(request('search'))
                <p class="page-subtitle">{{ $products->total() }} {{ __('ta mahsulot topildi') }}</p>
            @elseif(isset($category) && $category->description)
                <p class="page-subtitle">{{ $category->description }}</p>
            @endif
        </div>
    </div>
</div>

<div class="products-page">
    <div class="container">
        <div class="products-layout">
            <!-- Sidebar -->
            <aside class="products-sidebar">
                <div class="sidebar-header">
                    <h3 class="sidebar-title">{{ __('Filtrlash') }}</h3>
                    <button class="sidebar-toggle mobile-only" onclick="toggleSidebar()">
                        <i class="fas fa-filter"></i>
                        {{ __('Filtr') }}
                    </button>
                    <button class="clear-filters" onclick="clearAllFilters()">
                        {{ __('Tozalash') }}
                    </button>
                </div>
                
                <div class="sidebar-content">
                    <!-- Price Range -->
                    <div class="filter-group">
                        <h4 class="filter-title">
                            {{ __('Narx oralig\'i') }}
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </h4>
                        <div class="filter-content">
                            <div class="price-range-slider">
                                <input type="range" id="priceMin" min="0" max="50000000" value="{{ request('price_min', 0) }}" class="range-input">
                                <input type="range" id="priceMax" min="0" max="50000000" value="{{ request('price_max', 50000000) }}" class="range-input">
                                <div class="range-track"></div>
                            </div>
                            
                            <div class="price-inputs">
                                <div class="price-input-group">
                                    <label>{{ __('Dan') }}</label>
                                    <input type="number" id="priceMinInput" value="{{ request('price_min', 0) }}" placeholder="0">
                                    <span>{{ __('so\'m') }}</span>
                                </div>
                                <div class="price-input-group">
                                    <label>{{ __('Gacha') }}</label>
                                    <input type="number" id="priceMaxInput" value="{{ request('price_max', 50000000) }}" placeholder="50000000">
                                    <span>{{ __('so\'m') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Brands -->
                    @if($brands->count() > 0)
                    <div class="filter-group">
                        <h4 class="filter-title">
                            {{ __('Brendlar') }}
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </h4>
                        <div class="filter-content">
                            <div class="filter-search">
                                <input type="text" placeholder="{{ __('Brend qidirish...') }}" class="brand-search" onkeyup="searchBrands(this)">
                                <i class="fas fa-search"></i>
                            </div>
                            
                            <div class="filter-options brand-list">
                                @foreach($brands as $brand)
                                <label class="filter-option brand-option">
                                    <input type="checkbox" 
                                           name="brands[]" 
                                           value="{{ $brand->slug }}"
                                           {{ in_array($brand->slug, request('brands', [])) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <span class="option-label">{{ $brand->name }}</span>
                                    <span class="option-count">({{ $brand->products_count }})</span>
                                </label>
                                @endforeach
                            </div>
                            
                            @if($brands->count() > 10)
                            <button class="show-more-brands" onclick="toggleBrandsList()">
                                <span class="show-text">{{ __('Ko\'proq ko\'rsatish') }}</span>
                                <span class="hide-text">{{ __('Yashirish') }}</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <!-- Categories -->
                    @if(!request('category') && $categories->count() > 0)
                    <div class="filter-group">
                        <h4 class="filter-title">
                            {{ __('Kategoriyalar') }}
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </h4>
                        <div class="filter-content">
                            <div class="filter-options category-list">
                                @foreach($categories as $category)
                                <label class="filter-option">
                                    <input type="checkbox" 
                                           name="categories[]" 
                                           value="{{ $category->slug }}"
                                           {{ in_array($category->slug, request('categories', [])) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <span class="option-label">{{ $category->name }}</span>
                                    <span class="option-count">({{ $category->products_count }})</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Attributes -->
                    @foreach($attributes as $attribute)
                    <div class="filter-group">
                        <h4 class="filter-title">
                            {{ $attribute->name }}
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </h4>
                        <div class="filter-content">
                            <div class="filter-options">
                                @foreach($attribute->values as $value)
                                <label class="filter-option">
                                    <input type="checkbox" 
                                           name="attributes[{{ $attribute->slug }}][]" 
                                           value="{{ $value->slug }}"
                                           {{ in_array($value->slug, request('attributes.' . $attribute->slug, [])) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <span class="option-label">{{ $value->name }}</span>
                                    <span class="option-count">({{ $value->products_count }})</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    <!-- Rating -->
                    <div class="filter-group">
                        <h4 class="filter-title">
                            {{ __('Baho') }}
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </h4>
                        <div class="filter-content">
                            <div class="filter-options rating-options">
                                @for($i = 5; $i >= 1; $i--)
                                <label class="filter-option rating-option">
                                    <input type="radio" 
                                           name="rating" 
                                           value="{{ $i }}"
                                           {{ request('rating') == $i ? 'checked' : '' }}>
                                    <span class="checkmark radio"></span>
                                    <div class="rating-stars">
                                        @for($j = 1; $j <= 5; $j++)
                                            <i class="fas fa-star {{ $j <= $i ? 'active' : '' }}"></i>
                                        @endfor
                                        <span class="rating-text">{{ $i }} {{ __('yulduz va yuqori') }}</span>
                                    </div>
                                </label>
                                @endfor
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Options -->
                    <div class="filter-group">
                        <h4 class="filter-title">
                            {{ __('Qo\'shimcha') }}
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </h4>
                        <div class="filter-content">
                            <div class="filter-options">
                                <label class="filter-option">
                                    <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <span class="option-label">{{ __('Omborda mavjud') }}</span>
                                </label>
                                
                                <label class="filter-option">
                                    <input type="checkbox" name="on_sale" value="1" {{ request('on_sale') ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <span class="option-label">{{ __('Chegirmada') }}</span>
                                </label>
                                
                                <label class="filter-option">
                                    <input type="checkbox" name="free_shipping" value="1" {{ request('free_shipping') ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <span class="option-label">{{ __('Bepul yetkazib berish') }}</span>
                                </label>
                                
                                <label class="filter-option">
                                    <input type="checkbox" name="installment" value="1" {{ request('installment') ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <span class="option-label">{{ __('Muddatli to\'lov') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Apply Filters Button -->
                <div class="sidebar-footer">
                    <button class="btn btn-primary btn-block apply-filters" onclick="applyFilters()">
                        <i class="fas fa-filter"></i>
                        {{ __('Filtrlarni qo\'llash') }}
                    </button>
                </div>
            </aside>
            
            <!-- Main Content -->
            <main class="products-main">
                <!-- Toolbar -->
                <div class="products-toolbar">
                    <div class="toolbar-left">
                        <div class="results-info">
                            {{ $products->total() }} {{ __('mahsulotdan') }}
                            {{ $products->firstItem() }}-{{ $products->lastItem() }} {{ __('ko\'rsatilmoqda') }}
                        </div>
                        
                        <div class="active-filters" id="activeFilters">
                            <!-- Active filters will be populated by JavaScript -->
                        </div>
                    </div>
                    
                    <div class="toolbar-right">
                        <div class="view-modes">
                            <button class="view-mode {{ request('view', 'grid') === 'grid' ? 'active' : '' }}" 
                                    data-view="grid" 
                                    onclick="changeView('grid')">
                                <i class="fas fa-th"></i>
                            </button>
                            <button class="view-mode {{ request('view') === 'list' ? 'active' : '' }}" 
                                    data-view="list" 
                                    onclick="changeView('list')">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                        
                        <div class="per-page-selector">
                            <select onchange="changePerPage(this.value)" class="per-page-select">
                                <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12 ta</option>
                                <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24 ta</option>
                                <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48 ta</option>
                                <option value="96" {{ request('per_page') == 96 ? 'selected' : '' }}>96 ta</option>
                            </select>
                        </div>
                        
                        <div class="sort-selector">
                            <select onchange="changeSort(this.value)" class="sort-select">
                                <option value="featured" {{ request('sort', 'featured') === 'featured' ? 'selected' : '' }}>
                                    {{ __('Tavsiya etilgan') }}
                                </option>
                                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>
                                    {{ __('Eng yangi') }}
                                </option>
                                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>
                                    {{ __('Arzon narxdan') }}
                                </option>
                                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>
                                    {{ __('Qimmat narxdan') }}
                                </option>
                                <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>
                                    {{ __('Eng baholangan') }}
                                </option>
                                <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>
                                    {{ __('Ommabop') }}
                                </option>
                                <option value="discount" {{ request('sort') === 'discount' ? 'selected' : '' }}>
                                    {{ __('Eng katta chegirma') }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Products Grid/List -->
                <div class="products-container {{ request('view', 'grid') === 'list' ? 'list-view' : 'grid-view' }}" id="productsContainer">
                    @if($products->count() > 0)
                        @foreach($products as $product)
                            @if(request('view') === 'list')
                                @include('web.components.product-card-list', ['product' => $product])
                            @else
                                @include('web.components.product-card', ['product' => $product])
                            @endif
                        @endforeach
                    @else
                        <div class="no-products">
                            <div class="no-products-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h3 class="no-products-title">{{ __('Hech qanday mahsulot topilmadi') }}</h3>
                            <p class="no-products-text">
                                {{ __('Qidiruv shartlaringizga mos mahsulot yo\'q. Filtrlarni o\'zgartirib ko\'ring.') }}
                            </p>
                            <div class="no-products-actions">
                                <button class="btn btn-primary" onclick="clearAllFilters()">
                                    {{ __('Filtrlarni tozalash') }}
                                </button>
                                <a href="{{ route('products') }}" class="btn btn-outline">
                                    {{ __('Barcha mahsulotlar') }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Loading Spinner -->
                <div class="products-loading" id="productsLoading" style="display: none;">
                    <div class="spinner"></div>
                    <p>{{ __('Mahsulotlar yuklanmoqda...') }}</p>
                </div>
                
                <!-- Pagination -->
                @if($products->hasPages())
                <div class="products-pagination">
                    {{ $products->appends(request()->query())->links('web.components.pagination') }}
                </div>
                @endif
                
                <!-- Load More Button (for AJAX loading) -->
                @if($products->hasMorePages() && request('ajax_load'))
                <div class="load-more-section">
                    <button class="btn btn-outline btn-lg load-more-btn" onclick="loadMoreProducts()">
                        <i class="fas fa-plus"></i>
                        {{ __('Yana ko\'rsatish') }}
                    </button>
                </div>
                @endif
            </main>
        </div>
    </div>
</div>

<!-- Recently Viewed Products -->
@if(isset($recentlyViewed) && $recentlyViewed->count() > 0)
<section class="recently-viewed-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">{{ __('So\'nggi ko\'rilgan mahsulotlar') }}</h2>
            <button class="clear-history-btn" onclick="clearViewHistory()">
                <i class="fas fa-trash"></i>
                {{ __('Tarikhni tozalash') }}
            </button>
        </div>
        
        <div class="products-slider">
            <div class="products-track">
                @foreach($recentlyViewed as $product)
                    @include('web.components.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeProductsPage();
});

let filtersData = @json(request()->all());

function initializeProductsPage() {
    initializeFilters();
    initializePriceRange();
    updateActiveFilters();
    
    // Handle filter changes
    document.addEventListener('change', handleFilterChange);
    
    // Initialize view mode
    const currentView = '{{ request("view", "grid") }}';
    setViewMode(currentView);
}

// Filter functionality
function initializeFilters() {
    const filterGroups = document.querySelectorAll('.filter-group');
    
    filterGroups.forEach(group => {
        const title = group.querySelector('.filter-title');
        const content = group.querySelector('.filter-content');
        
        if (title && content) {
            title.addEventListener('click', function() {
                group.classList.toggle('collapsed');
                
                const icon = title.querySelector('.toggle-icon');
                if (icon) {
                    icon.style.transform = group.classList.contains('collapsed') ? 
                        'rotate(-90deg)' : 'rotate(0deg)';
                }
            });
        }
    });
}

function initializePriceRange() {
    const minSlider = document.getElementById('priceMin');
    const maxSlider = document.getElementById('priceMax');
    const minInput = document.getElementById('priceMinInput');
    const maxInput = document.getElementById('priceMaxInput');
    
    if (!minSlider || !maxSlider) return;
    
    function updatePriceRange() {
        let minVal = parseInt(minSlider.value);
        let maxVal = parseInt(maxSlider.value);
        
        if (minVal >= maxVal) {
            minVal = maxVal - 100000;
            minSlider.value = minVal;
        }
        
        minInput.value = minVal;
        maxInput.value = maxVal;
        
        // Update visual range
        const rangeTrack = document.querySelector('.range-track');
        if (rangeTrack) {
            const minPercent = (minVal / maxSlider.max) * 100;
            const maxPercent = (maxVal / maxSlider.max) * 100;
            
            rangeTrack.style.left = minPercent + '%';
            rangeTrack.style.width = (maxPercent - minPercent) + '%';
        }
    }
    
    minSlider.addEventListener('input', updatePriceRange);
    maxSlider.addEventListener('input', updatePriceRange);
    
    minInput.addEventListener('change', function() {
        minSlider.value = this.value;
        updatePriceRange();
    });
    
    maxInput.addEventListener('change', function() {
        maxSlider.value = this.value;
        updatePriceRange();
    });
    
    // Initialize
    updatePriceRange();
}

function handleFilterChange(event) {
    const input = event.target;
    
    if (input.type === 'checkbox' || input.type === 'radio') {
        // Don't auto-apply on desktop, wait for apply button
        if (window.innerWidth > 768) {
            updateFilterButton();
        } else {
            // Auto-apply on mobile
            setTimeout(applyFilters, 100);
        }
    }
}

function updateFilterButton() {
    const applyBtn = document.querySelector('.apply-filters');
    if (applyBtn) {
        applyBtn.classList.add('has-changes');
        applyBtn.innerHTML = '<i class="fas fa-sync"></i> {{ __("Filtrlarni yangilash") }}';
    }
}

function applyFilters() {
    const formData = new FormData();
    const inputs = document.querySelectorAll('.sidebar-content input:checked, .sidebar-content input[type="number"]');
    
    // Collect filter values
    inputs.forEach(input => {
        if (input.type === 'checkbox' && input.checked) {
            if (input.name.includes('[')) {
                // Handle array inputs like attributes[color][]
                const existingValues = formData.getAll(input.name) || [];
                existingValues.push(input.value);
                formData.delete(input.name);
                existingValues.forEach(value => formData.append(input.name, value));
            } else {
                formData.append(input.name, input.value);
            }
        } else if (input.type === 'radio' && input.checked) {
            formData.append(input.name, input.value);
        } else if (input.type === 'number' && input.value) {
            formData.append(input.name, input.value);
        }
    });
    
    // Add current sort and view parameters
    if (filtersData.sort) formData.append('sort', filtersData.sort);
    if (filtersData.view) formData.append('view', filtersData.view);
    if (filtersData.per_page) formData.append('per_page', filtersData.per_page);
    
    // Build URL
    const params = new URLSearchParams(formData);
    const url = window.location.pathname + '?' + params.toString();
    
    // Update page
    window.history.pushState({}, '', url);
    loadProducts(url);
}

function loadProducts(url) {
    const container = document.getElementById('productsContainer');
    const loading = document.getElementById('productsLoading');
    
    container.style.opacity = '0.5';
    loading.style.display = 'block';
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            container.innerHTML = data.html;
            
            // Update pagination
            const pagination = document.querySelector('.products-pagination');
            if (pagination && data.pagination) {
                pagination.innerHTML = data.pagination;
            }
            
            // Update results info
            const resultsInfo = document.querySelector('.results-info');
            if (resultsInfo && data.results_info) {
                resultsInfo.textContent = data.results_info;
            }
            
            updateActiveFilters();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Filter error:', error);
        showToast('{{ __("Xatolik yuz berdi") }}', 'error');
    })
    .finally(() => {
        container.style.opacity = '1';
        loading.style.display = 'none';
        
        // Reset apply button
        const applyBtn = document.querySelector('.apply-filters');
        if (applyBtn) {
            applyBtn.classList.remove('has-changes');
            applyBtn.innerHTML = '<i class="fas fa-filter"></i> {{ __("Filtrlarni qo\'llash") }}';
        }
    });
}

function updateActiveFilters() {
    const container = document.getElementById('activeFilters');
    if (!container) return;
    
    const params = new URLSearchParams(window.location.search);
    let filtersHtml = '';
    
    // Price range
    const priceMin = params.get('price_min');
    const priceMax = params.get('price_max');
    if (priceMin || priceMax) {
        filtersHtml += `
            <div class="active-filter">
                <span class="filter-label">{{ __('Narx') }}: ${formatPrice(priceMin || 0)} - ${formatPrice(priceMax || '∞')} {{ __('so\'m') }}</span>
                <button onclick="removeFilter(['price_min', 'price_max'])" class="remove-filter">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
    }
    
    // Other filters
    params.forEach((value, key) => {
        if (key !== 'price_min' && key !== 'price_max' && key !== 'sort' && key !== 'view' && key !== 'per_page' && key !== 'page') {
            filtersHtml += `
                <div class="active-filter">
                    <span class="filter-label">${getFilterLabel(key, value)}</span>
                    <button onclick="removeFilter('${key}')" class="remove-filter">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        }
    });
    
    container.innerHTML = filtersHtml;
}

function getFilterLabel(key, value) {
    // This would normally come from server-side data
    const labels = {
        'brands': '{{ __("Brend") }}',
        'categories': '{{ __("Kategoriya") }}',
        'in_stock': '{{ __("Omborda mavjud") }}',
        'on_sale': '{{ __("Chegirmada") }}',
        'free_shipping': '{{ __("Bepul yetkazib berish") }}',
        'installment': '{{ __("Muddatli to\'lov") }}',
        'rating': '{{ __("Baho") }}'
    };
    
    return `${labels[key] || key}: ${value}`;
}

function removeFilter(filterKey) {
    const params = new URLSearchParams(window.location.search);
    
    if (Array.isArray(filterKey)) {
        filterKey.forEach(key => params.delete(key));
    } else {
        params.delete(filterKey);
    }
    
    const url = window.location.pathname + '?' + params.toString();
    window.history.pushState({}, '', url);
    loadProducts(url);
}

function clearAllFilters() {
    const url = window.location.pathname;
    window.history.pushState({}, '', url);
    window.location.href = url;
}

// View mode functions
function changeView(mode) {
    filtersData.view = mode;
    
    const params = new URLSearchParams(window.location.search);
    params.set('view', mode);
    
    const url = window.location.pathname + '?' + params.toString();
    window.history.pushState({}, '', url);
    
    setViewMode(mode);
    loadProducts(url);
}

function setViewMode(mode) {
    const container = document.getElementById('productsContainer');
    const viewModes = document.querySelectorAll('.view-mode');
    
    container.className = container.className.replace(/\b(grid|list)-view\b/g, '');
    container.classList.add(mode + '-view');
    
    viewModes.forEach(btn => {
        btn.classList.toggle('active', btn.dataset.view === mode);
    });
}

// Sorting and per page
function changeSort(sort) {
    filtersData.sort = sort;
    
    const params = new URLSearchParams(window.location.search);
    params.set('sort', sort);
    params.delete('page'); // Reset to first page
    
    const url = window.location.pathname + '?' + params.toString();
    window.history.pushState({}, '', url);
    loadProducts(url);
}

function changePerPage(perPage) {
    filtersData.per_page = perPage;
    
    const params = new URLSearchParams(window.location.search);
    params.set('per_page', perPage);
    params.delete('page'); // Reset to first page
    
    const url = window.location.pathname + '?' + params.toString();
    window.history.pushState({}, '', url);
    loadProducts(url);
}

// Sidebar toggle for mobile
function toggleSidebar() {
    const sidebar = document.querySelector('.products-sidebar');
    sidebar.classList.toggle('show');
    
    if (sidebar.classList.contains('show')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}

// Brand search
function searchBrands(input) {
    const query = input.value.toLowerCase();
    const brandOptions = document.querySelectorAll('.brand-option');
    
    brandOptions.forEach(option => {
        const label = option.querySelector('.option-label').textContent.toLowerCase();
        option.style.display = label.includes(query) ? 'flex' : 'none';
    });
}

function toggleBrandsList() {
    const brandList = document.querySelector('.brand-list');
    const toggleBtn = document.querySelector('.show-more-brands');
    
    brandList.classList.toggle('expanded');
    
    const showText = toggleBtn.querySelector('.show-text');
    const hideText = toggleBtn.querySelector('.hide-text');
    const icon = toggleBtn.querySelector('i');
    
    if (brandList.classList.contains('expanded')) {
        showText.style.display = 'none';
        hideText.style.display = 'inline';
        icon.style.transform = 'rotate(180deg)';
    } else {
        showText.style.display = 'inline';
        hideText.style.display = 'none';
        icon.style.transform = 'rotate(0deg)';
    }
}

// Recently viewed functions
function clearViewHistory() {
    if (!confirm('{{ __("Ko\'rish tarixi tozalansinmi?") }}')) {
        return;
    }
    
    fetch('{{ route("user.clear-view-history") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector('.recently-viewed-section').remove();
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Clear history error:', error);
        showToast('{{ __("Xatolik yuz berdi") }}', 'error');
    });
}

// Handle browser back/forward
window.addEventListener('popstate', function(e) {
    location.reload();
});

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(e) {
    const sidebar = document.querySelector('.products-sidebar');
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    
    if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
        if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
            toggleSidebar();
        }
    }
});
</script>
@endpush
