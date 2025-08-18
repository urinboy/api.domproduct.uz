@extends('template.mobile.layouts.app')

@section('title', __('Mahsulotlar'))

@section('content')
<div class="products-page">
    <!-- Filter Header -->
    <div class="products-header">
        <div class="container">
            <div class="filter-row">
                <button class="filter-btn" id="filterBtn">
                    <i class="fas fa-filter"></i>
                    <span>{{ __('Filtr') }}</span>
                </button>

                <div class="sort-dropdown">
                    <select class="sort-select" name="sort" onchange="handleSort(this.value)">
                        <option value="default">{{ __('Saralash') }}</option>
                        <option value="price_low">{{ __('Arzon narx') }}</option>
                        <option value="price_high">{{ __('Qimmat narx') }}</option>
                        <option value="name_az">{{ __('Nomi A-Z') }}</option>
                        <option value="name_za">{{ __('Nomi Z-A') }}</option>
                        <option value="newest">{{ __('Yangi mahsulotlar') }}</option>
                        <option value="popular">{{ __('Ommabop') }}</option>
                    </select>
                </div>

                <div class="view-toggle">
                    <button class="view-btn active" data-view="grid">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="view-btn" data-view="list">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Overlay -->
    <div class="filter-overlay" id="filterOverlay">
        <div class="filter-content">
            <div class="filter-header">
                <h3>{{ __('Filtrlar') }}</h3>
                <button class="close-filter" id="closeFilter">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="filter-body">
                <!-- Price Range -->
                <div class="filter-group">
                    <h4>{{ __('Narx oralig\'i') }}</h4>
                    <div class="price-range">
                        <div class="price-inputs">
                            <input type="number" placeholder="{{ __('Dan') }}" id="priceMin" value="0">
                            <span>-</span>
                            <input type="number" placeholder="{{ __('Gacha') }}" id="priceMax" value="1000000">
                        </div>
                        <div class="price-slider">
                            <div class="slider-track"></div>
                            <div class="slider-range"></div>
                            <div class="slider-thumb slider-thumb-left"></div>
                            <div class="slider-thumb slider-thumb-right"></div>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="filter-group">
                    <h4>{{ __('Kategoriyalar') }}</h4>
                    <div class="filter-options">
                        @foreach($categories as $category)
                        <label class="filter-option">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}">
                            <span class="checkmark"></span>
                            <span class="option-text">{{ $category->name }}</span>
                            <span class="option-count">({{ $category->products_count }})</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Brands -->
                <div class="filter-group">
                    <h4>{{ __('Brendlar') }}</h4>
                    <div class="filter-options">
                        @foreach($brands as $brand)
                        <label class="filter-option">
                            <input type="checkbox" name="brands[]" value="{{ $brand->id }}">
                            <span class="checkmark"></span>
                            <span class="option-text">{{ $brand->name }}</span>
                            <span class="option-count">({{ $brand->products_count }})</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Rating -->
                <div class="filter-group">
                    <h4>{{ __('Reyting') }}</h4>
                    <div class="rating-filters">
                        @for($i = 5; $i >= 1; $i--)
                        <label class="rating-option">
                            <input type="radio" name="rating" value="{{ $i }}">
                            <span class="rating-display">
                                @for($j = 1; $j <= 5; $j++)
                                    <i class="fas fa-star {{ $j <= $i ? 'active' : '' }}"></i>
                                @endfor
                                <span>{{ __('va yuqori') }}</span>
                            </span>
                        </label>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="filter-footer">
                <button class="btn btn-outline" onclick="clearFilters()">{{ __('Tozalash') }}</button>
                <button class="btn btn-primary" onclick="applyFilters()">{{ __('Qo\'llash') }}</button>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="products-container">
        <div class="container">
            <div class="products-grid" id="productsGrid">
                @forelse($products as $product)
                    @include('template.mobile.components.product-card-simple', ['product' => $product])
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>{{ __('Mahsulotlar topilmadi') }}</h3>
                        <p>{{ __('Qidiruv mezonlarini o\'zgartirib ko\'ring') }}</p>
                        <button class="btn btn-primary" onclick="clearFilters()">
                            {{ __('Filtrlarni tozalash') }}
                        </button>
                    </div>
                @endforelse
            </div>

            @if($products->hasPages())
            <div class="pagination-wrapper">
                {{ $products->appends(request()->query())->links('mobile.components.pagination') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeProductsPage();
});

function initializeProductsPage() {
    // Filter toggle
    const filterBtn = document.getElementById('filterBtn');
    const filterOverlay = document.getElementById('filterOverlay');
    const closeFilter = document.getElementById('closeFilter');

    filterBtn.addEventListener('click', () => {
        filterOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    });

    closeFilter.addEventListener('click', () => {
        filterOverlay.classList.remove('active');
        document.body.style.overflow = '';
    });

    filterOverlay.addEventListener('click', (e) => {
        if (e.target === filterOverlay) {
            filterOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // View toggle
    const viewBtns = document.querySelectorAll('.view-btn');
    const productsGrid = document.getElementById('productsGrid');

    viewBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            viewBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const view = btn.dataset.view;
            productsGrid.className = view === 'list' ? 'products-list' : 'products-grid';
        });
    });

    // Price range slider
    initializePriceSlider();

    // Filter options expand/collapse
    const filterGroups = document.querySelectorAll('.filter-group h4');
    filterGroups.forEach(header => {
        header.addEventListener('click', () => {
            const group = header.parentElement;
            group.classList.toggle('collapsed');
        });
    });
}

function initializePriceSlider() {
    const sliderTrack = document.querySelector('.slider-track');
    const sliderRange = document.querySelector('.slider-range');
    const thumbLeft = document.querySelector('.slider-thumb-left');
    const thumbRight = document.querySelector('.slider-thumb-right');
    const priceMin = document.getElementById('priceMin');
    const priceMax = document.getElementById('priceMax');

    let isDragging = false;
    let currentThumb = null;

    const minPrice = 0;
    const maxPrice = 1000000;

    function updateSlider() {
        const minVal = parseInt(priceMin.value) || minPrice;
        const maxVal = parseInt(priceMax.value) || maxPrice;

        const minPercent = ((minVal - minPrice) / (maxPrice - minPrice)) * 100;
        const maxPercent = ((maxVal - minPrice) / (maxPrice - minPrice)) * 100;

        thumbLeft.style.left = minPercent + '%';
        thumbRight.style.left = maxPercent + '%';

        sliderRange.style.left = minPercent + '%';
        sliderRange.style.width = (maxPercent - minPercent) + '%';
    }

    priceMin.addEventListener('input', updateSlider);
    priceMax.addEventListener('input', updateSlider);

    updateSlider();
}

function handleSort(value) {
    const url = new URL(window.location);
    url.searchParams.set('sort', value);
    url.searchParams.set('page', '1');
    window.location.href = url.toString();
}

function applyFilters() {
    showLoading('{{ __("Filtrlar qo\'llanmoqda...") }}');

    const form = document.createElement('form');
    form.method = 'GET';
    form.action = window.location.pathname;

    // Price range
    const priceMin = document.getElementById('priceMin').value;
    const priceMax = document.getElementById('priceMax').value;

    if (priceMin) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'price_min';
        input.value = priceMin;
        form.appendChild(input);
    }

    if (priceMax) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'price_max';
        input.value = priceMax;
        form.appendChild(input);
    }

    // Categories
    const categoryInputs = document.querySelectorAll('input[name="categories[]"]:checked');
    categoryInputs.forEach(input => {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'categories[]';
        hiddenInput.value = input.value;
        form.appendChild(hiddenInput);
    });

    // Brands
    const brandInputs = document.querySelectorAll('input[name="brands[]"]:checked');
    brandInputs.forEach(input => {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'brands[]';
        hiddenInput.value = input.value;
        form.appendChild(hiddenInput);
    });

    // Rating
    const ratingInput = document.querySelector('input[name="rating"]:checked');
    if (ratingInput) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'rating';
        input.value = ratingInput.value;
        form.appendChild(input);
    }

    // Preserve current sort
    const currentSort = new URLSearchParams(window.location.search).get('sort');
    if (currentSort) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'sort';
        input.value = currentSort;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}

function clearFilters() {
    const url = new URL(window.location);
    url.search = '';
    window.location.href = url.toString();
}

// Infinite scroll for mobile
let isLoading = false;
let currentPage = {{ $products->currentPage() }};
let lastPage = {{ $products->lastPage() }};

function loadMoreProducts() {
    if (isLoading || currentPage >= lastPage) return;

    isLoading = true;
    currentPage++;

    const url = new URL(window.location);
    url.searchParams.set('page', currentPage);

    fetch(url.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newProducts = doc.querySelectorAll('.product-card');

        const productsGrid = document.getElementById('productsGrid');
        newProducts.forEach(product => {
            productsGrid.appendChild(product);
        });

        isLoading = false;
    })
    .catch(error => {
        console.error('Error loading products:', error);
        isLoading = false;
        currentPage--;
    });
}

// Auto-load more products when scrolled to bottom
window.addEventListener('scroll', () => {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 1000) {
        loadMoreProducts();
    }
});
</script>
@endpush

@push('styles')
<style>
.products-page {
    padding-bottom: 80px;
}

.products-header {
    background: var(--surface-color);
    border-bottom: 1px solid var(--border-color);
    position: sticky;
    top: 60px;
    z-index: 100;
}

.filter-row {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-sm) 0;
}

.filter-btn {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    padding: var(--spacing-xs) var(--spacing-sm);
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-size: var(--text-sm);
    font-weight: 500;
}

.sort-dropdown {
    flex: 1;
}

.sort-select {
    width: 100%;
    padding: var(--spacing-xs) var(--spacing-sm);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    background: var(--surface-color);
    font-size: var(--text-sm);
}

.view-toggle {
    display: flex;
    gap: 4px;
}

.view-btn {
    padding: var(--spacing-xs);
    border: 1px solid var(--border-color);
    background: var(--surface-color);
    border-radius: var(--radius-sm);
    color: var(--text-secondary);
    font-size: var(--text-sm);
}

.view-btn.active {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.filter-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.filter-overlay.active {
    opacity: 1;
    visibility: visible;
}

.filter-content {
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: 320px;
    max-width: 85vw;
    background: var(--surface-color);
    display: flex;
    flex-direction: column;
    transform: translateX(100%);
    transition: transform 0.3s ease;
}

.filter-overlay.active .filter-content {
    transform: translateX(0);
}

.filter-header {
    padding: var(--spacing-md);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.filter-header h3 {
    margin: 0;
    font-size: var(--text-lg);
    font-weight: 600;
}

.close-filter {
    padding: var(--spacing-xs);
    background: none;
    border: none;
    color: var(--text-secondary);
    font-size: var(--text-lg);
}

.filter-body {
    flex: 1;
    overflow-y: auto;
    padding: var(--spacing-md);
}

.filter-group {
    margin-bottom: var(--spacing-lg);
}

.filter-group h4 {
    margin: 0 0 var(--spacing-sm) 0;
    font-size: var(--text-base);
    font-weight: 600;
    color: var(--text-primary);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.filter-group h4::after {
    content: '\f107';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    transition: transform 0.3s ease;
}

.filter-group.collapsed h4::after {
    transform: rotate(-90deg);
}

.filter-group.collapsed .filter-options,
.filter-group.collapsed .price-range,
.filter-group.collapsed .rating-filters {
    display: none;
}

.price-inputs {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-md);
}

.price-inputs input {
    flex: 1;
    padding: var(--spacing-xs) var(--spacing-sm);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
    text-align: center;
    font-size: var(--text-sm);
}

.price-slider {
    position: relative;
    height: 6px;
    margin: var(--spacing-md) 0;
}

.slider-track {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: var(--border-color);
    border-radius: 3px;
}

.slider-range {
    position: absolute;
    top: 0;
    height: 6px;
    background: var(--primary-color);
    border-radius: 3px;
}

.slider-thumb {
    position: absolute;
    top: -5px;
    width: 16px;
    height: 16px;
    background: var(--primary-color);
    border: 2px solid white;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
}

.filter-options {
    max-height: 200px;
    overflow-y: auto;
}

.filter-option {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-xs) 0;
    cursor: pointer;
    font-size: var(--text-sm);
}

.filter-option input {
    display: none;
}

.checkmark {
    width: 18px;
    height: 18px;
    border: 2px solid var(--border-color);
    border-radius: var(--radius-sm);
    position: relative;
    flex-shrink: 0;
}

.filter-option input:checked + .checkmark {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

.filter-option input:checked + .checkmark::after {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 10px;
}

.option-text {
    flex: 1;
    color: var(--text-primary);
}

.option-count {
    color: var(--text-secondary);
    font-size: var(--text-xs);
}

.rating-filters {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.rating-option {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    cursor: pointer;
    font-size: var(--text-sm);
}

.rating-option input {
    display: none;
}

.rating-display {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
}

.rating-display .fas.fa-star {
    color: var(--border-color);
    font-size: 12px;
}

.rating-display .fas.fa-star.active {
    color: var(--warning-color);
}

.rating-option input:checked + .rating-display .fas.fa-star {
    color: var(--warning-color);
}

.filter-footer {
    padding: var(--spacing-md);
    border-top: 1px solid var(--border-color);
    display: flex;
    gap: var(--spacing-sm);
}

.filter-footer .btn {
    flex: 1;
    padding: var(--spacing-sm);
    font-size: var(--text-sm);
}

.products-container {
    padding: var(--spacing-md) 0;
}

.products-list .product-card {
    width: 100%;
    margin-bottom: var(--spacing-md);
}

.empty-state {
    text-align: center;
    padding: var(--spacing-xl) var(--spacing-md);
    color: var(--text-secondary);
}

.empty-icon {
    font-size: 48px;
    color: var(--border-color);
    margin-bottom: var(--spacing-md);
}

.empty-state h3 {
    margin: 0 0 var(--spacing-sm) 0;
    font-size: var(--text-lg);
    color: var(--text-primary);
}

.empty-state p {
    margin: 0 0 var(--spacing-md) 0;
    font-size: var(--text-sm);
}

@media (max-width: 768px) {
    .filter-content {
        width: 100vw;
        max-width: none;
    }

    .products-list .product-card {
        display: flex;
        align-items: center;
        padding: var(--spacing-sm);
    }

    .products-list .product-card .product-image {
        width: 80px;
        height: 80px;
        flex-shrink: 0;
        margin-right: var(--spacing-sm);
    }

    .products-list .product-card .product-info {
        flex: 1;
    }
}
</style>
@endpush
