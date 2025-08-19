@extends('mobile.layouts.app')

@section('title', __('Sevimlilar'))

@section('content')
<div class="wishlist-page">
    <div class="container">
        @if($wishlistItems->count() > 0)
        <!-- Wishlist Header -->
        <div class="wishlist-header">
            <h1 class="page-title">{{ __('Sevimlilar') }} ({{ $wishlistItems->count() }})</h1>
            <div class="header-actions">
                <button class="clear-wishlist" onclick="clearWishlist()">
                    <i class="fas fa-trash"></i>
                    <span>{{ __('Tozalash') }}</span>
                </button>
                
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

        <!-- Filter Options -->
        <div class="wishlist-filters">
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all">{{ __('Hammasi') }}</button>
                <button class="filter-tab" data-filter="available">{{ __('Mavjud') }}</button>
                <button class="filter-tab" data-filter="discount">{{ __('Chegirmada') }}</button>
                <button class="filter-tab" data-filter="out-of-stock">{{ __('Tugagan') }}</button>
            </div>

            <div class="sort-options">
                <select class="sort-select" onchange="sortWishlist(this.value)">
                    <option value="recent">{{ __('So\'nggi qo\'shilgan') }}</option>
                    <option value="name_az">{{ __('Nomi A-Z') }}</option>
                    <option value="name_za">{{ __('Nomi Z-A') }}</option>
                    <option value="price_low">{{ __('Arzon narx') }}</option>
                    <option value="price_high">{{ __('Qimmat narx') }}</option>
                </select>
            </div>
        </div>

        <!-- Wishlist Items -->
        <div class="wishlist-items" id="wishlistItems">
            @foreach($wishlistItems as $item)
            <div class="wishlist-item" 
                 data-item-id="{{ $item->id }}" 
                 data-available="{{ $item->product->in_stock ? 'true' : 'false' }}"
                 data-discount="{{ $item->product->discount > 0 ? 'true' : 'false' }}">
                
                <div class="item-image">
                    <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product->name }}">
                    @if($item->product->discount > 0)
                    <div class="discount-badge">-{{ $item->product->discount }}%</div>
                    @endif
                    @if(!$item->product->in_stock)
                    <div class="out-of-stock-overlay">
                        <span>{{ __('Mavjud emas') }}</span>
                    </div>
                    @endif
                </div>

                <div class="item-info">
                    <h3 class="item-name">
                        <a href="{{ route('product', $item->product->slug) }}">{{ $item->product->name }}</a>
                    </h3>
                    
                    <div class="item-rating">
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $item->product->rating ? 'active' : '' }}"></i>
                            @endfor
                        </div>
                        <span class="rating-count">({{ $item->product->reviews_count }})</span>
                    </div>

                    <div class="item-price">
                        @if($item->product->discount > 0)
                        <span class="price-old">{{ number_format($item->product->original_price) }} {{ __('so\'m') }}</span>
                        @endif
                        <span class="price-current">{{ number_format($item->product->price) }} {{ __('so\'m') }}</span>
                    </div>

                    <div class="item-status">
                        @if($item->product->in_stock)
                        <span class="status in-stock">
                            <i class="fas fa-check-circle"></i>
                            {{ __('Mavjud') }}
                        </span>
                        @else
                        <span class="status out-of-stock">
                            <i class="fas fa-times-circle"></i>
                            {{ __('Mavjud emas') }}
                        </span>
                        @endif
                    </div>

                    <div class="added-date">
                        {{ __('Qo\'shilgan') }}: {{ $item->created_at->format('d.m.Y') }}
                    </div>
                </div>

                <div class="item-actions">
                    <button class="action-btn add-to-cart" 
                            onclick="addToCart({{ $item->product->id }})"
                            {{ !$item->product->in_stock ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-cart"></i>
                        <span>{{ __('Savatga') }}</span>
                    </button>
                    
                    <button class="action-btn remove-from-wishlist" onclick="removeFromWishlist({{ $item->id }})">
                        <i class="fas fa-heart"></i>
                        <span>{{ __('O\'chirish') }}</span>
                    </button>
                    
                    <button class="action-btn share-product" onclick="shareProduct({{ $item->product->id }})">
                        <i class="fas fa-share"></i>
                        <span>{{ __('Ulashish') }}</span>
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Bulk Actions -->
        <div class="bulk-actions">
            <label class="select-all">
                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                <span class="checkmark"></span>
                <span>{{ __('Hammasini tanlash') }}</span>
            </label>

            <div class="bulk-buttons">
                <button class="bulk-btn" onclick="addSelectedToCart()" disabled>
                    <i class="fas fa-shopping-cart"></i>
                    <span>{{ __('Savatga qo\'shish') }}</span>
                </button>
                
                <button class="bulk-btn delete" onclick="removeSelectedFromWishlist()" disabled>
                    <i class="fas fa-trash"></i>
                    <span>{{ __('O\'chirish') }}</span>
                </button>
            </div>
        </div>

        @else
        <!-- Empty Wishlist -->
        <div class="empty-wishlist">
            <div class="empty-icon">
                <i class="fas fa-heart"></i>
            </div>
            <h2>{{ __('Sevimlilar ro\'yxati bo\'sh') }}</h2>
            <p>{{ __('Sizga yoqqan mahsulotlarni bu yerda saqlang') }}</p>
            <a href="{{ route('products') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i>
                <span>{{ __('Mahsulotlarni ko\'rish') }}</span>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeWishlist();
});

function initializeWishlist() {
    // View toggle
    const viewBtns = document.querySelectorAll('.view-btn');
    const wishlistItems = document.getElementById('wishlistItems');

    viewBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            viewBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            const view = btn.dataset.view;
            wishlistItems.className = view === 'list' ? 'wishlist-list' : 'wishlist-items';
        });
    });

    // Filter tabs
    const filterTabs = document.querySelectorAll('.filter-tab');
    filterTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            filterTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            filterWishlist(tab.dataset.filter);
        });
    });

    // Initialize checkboxes
    updateBulkActionButtons();
}

function filterWishlist(filter) {
    const items = document.querySelectorAll('.wishlist-item');
    
    items.forEach(item => {
        let show = true;
        
        switch(filter) {
            case 'available':
                show = item.dataset.available === 'true';
                break;
            case 'discount':
                show = item.dataset.discount === 'true';
                break;
            case 'out-of-stock':
                show = item.dataset.available === 'false';
                break;
            case 'all':
            default:
                show = true;
                break;
        }
        
        item.style.display = show ? 'block' : 'none';
    });
    
    updateEmptyState();
}

function sortWishlist(sortBy) {
    const container = document.getElementById('wishlistItems');
    const items = Array.from(container.querySelectorAll('.wishlist-item'));
    
    items.sort((a, b) => {
        const aName = a.querySelector('.item-name a').textContent;
        const bName = b.querySelector('.item-name a').textContent;
        const aPrice = parseInt(a.querySelector('.price-current').textContent.replace(/[^\d]/g, ''));
        const bPrice = parseInt(b.querySelector('.price-current').textContent.replace(/[^\d]/g, ''));
        
        switch(sortBy) {
            case 'name_az':
                return aName.localeCompare(bName);
            case 'name_za':
                return bName.localeCompare(aName);
            case 'price_low':
                return aPrice - bPrice;
            case 'price_high':
                return bPrice - aPrice;
            case 'recent':
            default:
                return 0; // Keep original order for recent
        }
    });
    
    // Reorder items
    items.forEach(item => container.appendChild(item));
}

function addToCart(productId) {
    showLoading('{{ __("Savatga qo\'shilmoqda...") }}');
    
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            showToast(data.message, 'success');
            updateCartCount(data.cart_count);
            
            // Update button temporarily
            const btn = document.querySelector(`[onclick="addToCart(${productId})"]`);
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> <span>{{ __("Qo\'shildi") }}</span>';
            btn.classList.add('success');
            
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.remove('success');
            }, 2000);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showToast('{{ __("Xatolik yuz berdi") }}', 'error');
        console.error('Error:', error);
    });
}

function removeFromWishlist(itemId) {
    if (!confirm('{{ __("Sevimlilardan olib tashlashni xohlaysizmi?") }}')) {
        return;
    }

    showLoading('{{ __("O\'chirilmoqda...") }}');

    fetch('{{ route("wishlist.remove") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            item_id: itemId
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            const item = document.querySelector(`[data-item-id="${itemId}"]`);
            item.style.animation = 'fadeOut 0.3s ease forwards';
            
            setTimeout(() => {
                item.remove();
                updateWishlistCount();
                updateEmptyState();
            }, 300);
            
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showToast('{{ __("Xatolik yuz berdi") }}', 'error');
        console.error('Error:', error);
    });
}

function clearWishlist() {
    if (!confirm('{{ __("Barcha sevimlilarni o\'chirish uchun rostdan ham ishonchingiz komilmi?") }}')) {
        return;
    }

    showLoading('{{ __("Tozalanmoqda...") }}');

    fetch('{{ route("wishlist.clear") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            location.reload();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showToast('{{ __("Xatolik yuz berdi") }}', 'error');
        console.error('Error:', error);
    });
}

function shareProduct(productId) {
    const item = document.querySelector(`[data-item-id] [onclick*="shareProduct(${productId})"]`).closest('.wishlist-item');
    const productName = item.querySelector('.item-name a').textContent;
    const productUrl = item.querySelector('.item-name a').href;
    
    if (navigator.share) {
        navigator.share({
            title: productName,
            url: productUrl
        });
    } else {
        navigator.clipboard.writeText(productUrl).then(() => {
            showToast('{{ __("Havola nusxalandi") }}', 'success');
        });
    }
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('.wishlist-item input[type="checkbox"]');
    
    itemCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkActionButtons();
}

function toggleItemSelect() {
    const itemCheckboxes = document.querySelectorAll('.wishlist-item input[type="checkbox"]');
    const selectAll = document.getElementById('selectAll');
    const checkedItems = document.querySelectorAll('.wishlist-item input[type="checkbox"]:checked');
    
    selectAll.checked = checkedItems.length === itemCheckboxes.length;
    selectAll.indeterminate = checkedItems.length > 0 && checkedItems.length < itemCheckboxes.length;
    
    updateBulkActionButtons();
}

function updateBulkActionButtons() {
    const checkedItems = document.querySelectorAll('.wishlist-item input[type="checkbox"]:checked');
    const bulkButtons = document.querySelectorAll('.bulk-btn');
    
    const hasSelection = checkedItems.length > 0;
    bulkButtons.forEach(btn => {
        btn.disabled = !hasSelection;
    });
}

function addSelectedToCart() {
    const selectedItems = document.querySelectorAll('.wishlist-item input[type="checkbox"]:checked');
    
    if (selectedItems.length === 0) {
        showToast('{{ __("Hech narsa tanlanmagan") }}', 'warning');
        return;
    }

    const productIds = Array.from(selectedItems).map(item => {
        const wishlistItem = item.closest('.wishlist-item');
        const addToCartBtn = wishlistItem.querySelector('.add-to-cart');
        return addToCartBtn.onclick.toString().match(/addToCart\((\d+)\)/)[1];
    });

    showLoading('{{ __("Savatga qo\'shilmoqda...") }}');

    fetch('{{ route("cart.add-multiple") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_ids: productIds
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            showToast(data.message, 'success');
            updateCartCount(data.cart_count);
            
            // Uncheck selected items
            selectedItems.forEach(item => item.checked = false);
            updateBulkActionButtons();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showToast('{{ __("Xatolik yuz berdi") }}', 'error');
        console.error('Error:', error);
    });
}

function removeSelectedFromWishlist() {
    const selectedItems = document.querySelectorAll('.wishlist-item input[type="checkbox"]:checked');
    
    if (selectedItems.length === 0) {
        showToast('{{ __("Hech narsa tanlanmagan") }}', 'warning');
        return;
    }

    if (!confirm(`{{ __("Tanlangan") }} ${selectedItems.length} {{ __("ta mahsulotni o\'chirish uchun rostdan ham ishonchingiz komilmi?") }}`)) {
        return;
    }

    const itemIds = Array.from(selectedItems).map(item => 
        item.closest('.wishlist-item').dataset.itemId
    );

    showLoading('{{ __("O\'chirilmoqda...") }}');

    fetch('{{ route("wishlist.remove-multiple") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            item_ids: itemIds
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            selectedItems.forEach(item => {
                const wishlistItem = item.closest('.wishlist-item');
                wishlistItem.style.animation = 'fadeOut 0.3s ease forwards';
                setTimeout(() => wishlistItem.remove(), 300);
            });
            
            showToast(data.message, 'success');
            
            setTimeout(() => {
                updateWishlistCount();
                updateEmptyState();
                updateBulkActionButtons();
            }, 350);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showToast('{{ __("Xatolik yuz berdi") }}', 'error');
        console.error('Error:', error);
    });
}

function updateWishlistCount() {
    const wishlistCount = document.querySelector('.wishlist-count');
    const remainingItems = document.querySelectorAll('.wishlist-item').length;
    
    if (wishlistCount) {
        wishlistCount.textContent = remainingItems;
        wishlistCount.style.display = remainingItems > 0 ? 'block' : 'none';
    }
    
    // Update page title
    const pageTitle = document.querySelector('.page-title');
    if (pageTitle) {
        pageTitle.textContent = `{{ __('Sevimlilar') }} (${remainingItems})`;
    }
}

function updateEmptyState() {
    const visibleItems = document.querySelectorAll('.wishlist-item[style*="display: block"], .wishlist-item:not([style*="display: none"])');
    const container = document.getElementById('wishlistItems');
    
    if (visibleItems.length === 0) {
        if (!container.querySelector('.no-results')) {
            const noResults = document.createElement('div');
            noResults.className = 'no-results';
            noResults.innerHTML = `
                <div class="no-results-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>{{ __('Hech narsa topilmadi') }}</h3>
                <p>{{ __('Filtrni o\'zgartirib ko\'ring') }}</p>
            `;
            container.appendChild(noResults);
        }
    } else {
        const noResults = container.querySelector('.no-results');
        if (noResults) {
            noResults.remove();
        }
    }
}

function updateCartCount(count) {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = count;
        cartCount.style.display = count > 0 ? 'block' : 'none';
    }
}
</script>
@endpush

@push('styles')
<style>
.wishlist-page {
    padding: var(--spacing-md) 0 100px;
    min-height: 100vh;
}

.wishlist-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-sm);
    border-bottom: 1px solid var(--border-color);
}

.page-title {
    font-size: var(--text-xl);
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.clear-wishlist {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    padding: var(--spacing-xs) var(--spacing-sm);
    background: none;
    border: 1px solid var(--error-color);
    border-radius: var(--radius-md);
    color: var(--error-color);
    font-size: var(--text-sm);
    cursor: pointer;
    transition: all 0.3s ease;
}

.clear-wishlist:hover {
    background: var(--error-color);
    color: white;
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
    cursor: pointer;
    transition: all 0.3s ease;
}

.view-btn.active {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.wishlist-filters {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-lg);
    gap: var(--spacing-md);
}

.filter-tabs {
    display: flex;
    gap: var(--spacing-xs);
    flex-wrap: wrap;
}

.filter-tab {
    padding: var(--spacing-xs) var(--spacing-sm);
    border: 1px solid var(--border-color);
    background: var(--surface-color);
    border-radius: var(--radius-md);
    color: var(--text-secondary);
    font-size: var(--text-sm);
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.filter-tab.active {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.sort-select {
    padding: var(--spacing-xs) var(--spacing-sm);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    background: var(--surface-color);
    font-size: var(--text-sm);
    min-width: 150px;
}

.wishlist-items {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
}

.wishlist-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-lg);
}

.wishlist-list .wishlist-item {
    display: flex;
    align-items: center;
    padding: var(--spacing-sm);
}

.wishlist-list .item-image {
    width: 80px;
    height: 80px;
    margin-right: var(--spacing-sm);
}

.wishlist-list .item-info {
    flex: 1;
    padding-right: var(--spacing-sm);
}

.wishlist-list .item-actions {
    flex-direction: column;
    align-items: flex-end;
    gap: var(--spacing-xs);
}

.wishlist-item {
    background: var(--surface-color);
    border-radius: var(--radius-lg);
    padding: var(--spacing-md);
    transition: all 0.3s ease;
    position: relative;
}

.wishlist-item:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.wishlist-item input[type="checkbox"] {
    position: absolute;
    top: var(--spacing-sm);
    left: var(--spacing-sm);
    z-index: 2;
}

.item-image {
    position: relative;
    width: 100%;
    height: 200px;
    border-radius: var(--radius-md);
    overflow: hidden;
    margin-bottom: var(--spacing-sm);
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.wishlist-item:hover .item-image img {
    transform: scale(1.05);
}

.discount-badge {
    position: absolute;
    top: var(--spacing-xs);
    right: var(--spacing-xs);
    background: var(--error-color);
    color: white;
    padding: 4px 8px;
    border-radius: var(--radius-sm);
    font-size: var(--text-xs);
    font-weight: 600;
    z-index: 2;
}

.out-of-stock-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: var(--text-sm);
    z-index: 2;
}

.item-info {
    margin-bottom: var(--spacing-sm);
}

.item-name {
    margin: 0 0 var(--spacing-xs) 0;
    font-size: var(--text-base);
    font-weight: 500;
    line-height: 1.3;
}

.item-name a {
    color: var(--text-primary);
    text-decoration: none;
}

.item-name a:hover {
    color: var(--primary-color);
}

.item-rating {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    margin-bottom: var(--spacing-xs);
}

.item-rating .stars {
    display: flex;
    gap: 2px;
}

.item-rating .fas.fa-star {
    color: var(--border-color);
    font-size: 12px;
}

.item-rating .fas.fa-star.active {
    color: var(--warning-color);
}

.rating-count {
    font-size: var(--text-xs);
    color: var(--text-secondary);
}

.item-price {
    margin-bottom: var(--spacing-sm);
}

.price-old {
    font-size: var(--text-sm);
    color: var(--text-secondary);
    text-decoration: line-through;
    margin-right: var(--spacing-xs);
}

.price-current {
    font-size: var(--text-base);
    font-weight: 600;
    color: var(--primary-color);
}

.item-status {
    margin-bottom: var(--spacing-xs);
}

.status {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    font-size: var(--text-sm);
    font-weight: 500;
}

.status.in-stock {
    color: var(--success-color);
}

.status.out-of-stock {
    color: var(--error-color);
}

.added-date {
    font-size: var(--text-xs);
    color: var(--text-secondary);
}

.item-actions {
    display: flex;
    gap: var(--spacing-xs);
    margin-top: var(--spacing-sm);
}

.action-btn {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    padding: var(--spacing-xs);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    background: var(--surface-color);
    color: var(--text-secondary);
    font-size: var(--text-xs);
    cursor: pointer;
    transition: all 0.3s ease;
}

.action-btn:hover:not(:disabled) {
    background: var(--hover-color);
    border-color: var(--primary-color);
    color: var(--primary-color);
}

.action-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.action-btn.success {
    background: var(--success-color);
    color: white;
    border-color: var(--success-color);
}

.remove-from-wishlist {
    color: var(--error-color);
    border-color: var(--error-color);
}

.remove-from-wishlist:hover {
    background: var(--error-color);
    color: white;
}

.bulk-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-md);
    background: var(--surface-color);
    border-radius: var(--radius-lg);
    margin-bottom: var(--spacing-lg);
}

.select-all {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    cursor: pointer;
    font-size: var(--text-sm);
}

.select-all input[type="checkbox"] {
    display: none;
}

.select-all .checkmark {
    width: 18px;
    height: 18px;
    border: 2px solid var(--border-color);
    border-radius: var(--radius-sm);
    position: relative;
    flex-shrink: 0;
}

.select-all input:checked + .checkmark {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

.select-all input:checked + .checkmark::after {
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

.bulk-buttons {
    display: flex;
    gap: var(--spacing-sm);
}

.bulk-btn {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    padding: var(--spacing-xs) var(--spacing-sm);
    border: 1px solid var(--primary-color);
    border-radius: var(--radius-md);
    background: var(--surface-color);
    color: var(--primary-color);
    font-size: var(--text-sm);
    cursor: pointer;
    transition: all 0.3s ease;
}

.bulk-btn:hover:not(:disabled) {
    background: var(--primary-color);
    color: white;
}

.bulk-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.bulk-btn.delete {
    border-color: var(--error-color);
    color: var(--error-color);
}

.bulk-btn.delete:hover:not(:disabled) {
    background: var(--error-color);
    color: white;
}

.empty-wishlist {
    text-align: center;
    padding: var(--spacing-xl) var(--spacing-md);
    margin-top: 10vh;
}

.empty-icon {
    font-size: 64px;
    color: var(--border-color);
    margin-bottom: var(--spacing-lg);
}

.empty-wishlist h2 {
    font-size: var(--text-xl);
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 var(--spacing-sm) 0;
}

.empty-wishlist p {
    color: var(--text-secondary);
    margin: 0 0 var(--spacing-lg) 0;
    font-size: var(--text-base);
}

.empty-wishlist .btn {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    padding: var(--spacing-sm) var(--spacing-lg);
    font-size: var(--text-base);
    font-weight: 600;
}

.no-results {
    text-align: center;
    padding: var(--spacing-xl) var(--spacing-md);
    color: var(--text-secondary);
}

.no-results-icon {
    font-size: 48px;
    color: var(--border-color);
    margin-bottom: var(--spacing-md);
}

.no-results h3 {
    margin: 0 0 var(--spacing-sm) 0;
    font-size: var(--text-lg);
    color: var(--text-primary);
}

.no-results p {
    margin: 0;
    font-size: var(--text-sm);
}

@keyframes fadeOut {
    from {
        opacity: 1;
        transform: scale(1);
    }
    to {
        opacity: 0;
        transform: scale(0.95);
    }
}

@media (max-width: 768px) {
    .wishlist-header {
        flex-direction: column;
        align-items: stretch;
        gap: var(--spacing-sm);
    }
    
    .header-actions {
        justify-content: space-between;
    }
    
    .wishlist-filters {
        flex-direction: column;
        gap: var(--spacing-sm);
    }
    
    .filter-tabs {
        justify-content: center;
    }
    
    .sort-options {
        align-self: stretch;
    }
    
    .sort-select {
        width: 100%;
    }
    
    .wishlist-items {
        grid-template-columns: 1fr;
    }
    
    .bulk-actions {
        flex-direction: column;
        gap: var(--spacing-sm);
    }
    
    .select-all {
        align-self: flex-start;
    }
    
    .bulk-buttons {
        align-self: stretch;
    }
    
    .bulk-btn {
        flex: 1;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .filter-tabs {
        gap: 4px;
    }
    
    .filter-tab {
        padding: 6px 8px;
        font-size: var(--text-xs);
    }
    
    .item-actions {
        flex-direction: column;
    }
    
    .action-btn {
        flex-direction: row;
        justify-content: center;
        padding: var(--spacing-xs) var(--spacing-sm);
    }
}
</style>
@endpush
