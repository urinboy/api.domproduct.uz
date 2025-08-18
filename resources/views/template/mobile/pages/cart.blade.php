@extends('template.mobile.layouts.app')

@section('title', __('Savat'))

@section('content')
<div class="cart-page">
    <div class="container">
        @if($cartItems->count() > 0)
        <!-- Cart Header -->
        <div class="cart-header">
            <h1 class="page-title">{{ __('Savat') }} ({{ $cartItems->count() }})</h1>
            <button class="clear-cart" onclick="clearCart()">
                <i class="fas fa-trash"></i>
                <span>{{ __('Tozalash') }}</span>
            </button>
        </div>

        <!-- Cart Items -->
        <div class="cart-items">
            @foreach($cartItems as $item)
            <div class="cart-item" data-item-id="{{ $item->id }}">
                <div class="item-image">
                    <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product->name }}">
                    @if($item->product->discount > 0)
                    <div class="discount-badge">-{{ $item->product->discount }}%</div>
                    @endif
                </div>

                <div class="item-details">
                    <h3 class="item-name">
                        <a href="{{ route('product', $item->product->slug) }}">{{ $item->product->name }}</a>
                    </h3>

                    @if($item->variants)
                    <div class="item-variants">
                        @foreach($item->variants as $type => $value)
                        <span class="variant">{{ __($type) }}: {{ $value }}</span>
                        @endforeach
                    </div>
                    @endif

                    <div class="item-price">
                        @if($item->product->discount > 0)
                        <span class="price-old">{{ number_format($item->product->original_price) }} {{ __('so\'m') }}</span>
                        @endif
                        <span class="price-current">{{ number_format($item->product->price) }} {{ __('so\'m') }}</span>
                    </div>

                    <div class="item-actions">
                        <div class="quantity-control">
                            <button class="qty-btn" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" value="{{ $item->quantity }}" min="1" class="qty-input"
                                   onchange="updateQuantity({{ $item->id }}, this.value)" readonly>
                            <button class="qty-btn" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>

                        <div class="item-total">
                            <span class="total-price">{{ number_format($item->total_price) }} {{ __('so\'m') }}</span>
                        </div>

                        <button class="remove-btn" onclick="removeItem({{ $item->id }})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Promo Code -->
        <div class="promo-section">
            <div class="promo-input">
                <input type="text" placeholder="{{ __('Promokod') }}" id="promoCode">
                <button class="btn btn-outline" onclick="applyPromoCode()">{{ __('Qo\'llash') }}</button>
            </div>

            @if(session('applied_promo'))
            <div class="applied-promo">
                <span class="promo-code">{{ session('applied_promo.code') }}</span>
                <span class="promo-discount">-{{ number_format(session('applied_promo.discount')) }} {{ __('so\'m') }}</span>
                <button class="remove-promo" onclick="removePromoCode()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif
        </div>

        <!-- Cart Summary -->
        <div class="cart-summary">
            <div class="summary-row">
                <span>{{ __('Mahsulotlar narxi') }}</span>
                <span class="subtotal">{{ number_format($subtotal) }} {{ __('so\'m') }}</span>
            </div>

            @if($discount > 0)
            <div class="summary-row discount">
                <span>{{ __('Chegirma') }}</span>
                <span>-{{ number_format($discount) }} {{ __('so\'m') }}</span>
            </div>
            @endif

            <div class="summary-row delivery">
                <span>{{ __('Yetkazib berish') }}</span>
                <span class="delivery-cost">
                    @if($deliveryCost > 0)
                        {{ number_format($deliveryCost) }} {{ __('so\'m') }}
                    @else
                        {{ __('Bepul') }}
                    @endif
                </span>
            </div>

            <div class="summary-row total">
                <span>{{ __('Jami') }}</span>
                <span class="total-amount">{{ number_format($total) }} {{ __('so\'m') }}</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="cart-actions">
            <a href="{{ route('products') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                <span>{{ __('Xaridni davom ettirish') }}</span>
            </a>

            <a href="{{ route('checkout') }}" class="btn btn-primary">
                <i class="fas fa-credit-card"></i>
                <span>{{ __('Rasmiylashtirish') }}</span>
            </a>
        </div>

        @else
        <!-- Empty Cart -->
        <div class="empty-cart">
            <div class="empty-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h2>{{ __('Savat bo\'sh') }}</h2>
            <p>{{ __('Hozircha hech narsa qo\'shilmagan') }}</p>
            <a href="{{ route('products') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i>
                <span>{{ __('Xarid qilish') }}</span>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateQuantity(itemId, newQuantity) {
    if (newQuantity < 1) {
        removeItem(itemId);
        return;
    }

    showLoading('{{ __("Yangilanmoqda...") }}');

    fetch('{{ route("cart.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            item_id: itemId,
            quantity: parseInt(newQuantity)
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();

        if (data.success) {
            // Update item total
            const item = document.querySelector(`[data-item-id="${itemId}"]`);
            const totalPrice = item.querySelector('.total-price');
            totalPrice.textContent = data.item_total + ' {{ __("so\'m") }}';

            // Update cart summary
            updateCartSummary(data.cart_summary);
            updateCartCount(data.cart_count);

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

function removeItem(itemId) {
    if (!confirm('{{ __("Mahsulotni savatdan olib tashlashni xohlaysizmi?") }}')) {
        return;
    }

    showLoading('{{ __("O\'chirilmoqda...") }}');

    fetch('{{ route("cart.remove") }}', {
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
            // Remove item from DOM
            const item = document.querySelector(`[data-item-id="${itemId}"]`);
            item.style.animation = 'slideOut 0.3s ease forwards';

            setTimeout(() => {
                item.remove();

                // Check if cart is empty
                if (data.cart_count === 0) {
                    location.reload();
                } else {
                    updateCartSummary(data.cart_summary);
                    updateCartCount(data.cart_count);
                }
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

function clearCart() {
    if (!confirm('{{ __("Savatni butunlay tozalashni xohlaysizmi?") }}')) {
        return;
    }

    showLoading('{{ __("Tozalanmoqda...") }}');

    fetch('{{ route("cart.clear") }}', {
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

function applyPromoCode() {
    const promoCode = document.getElementById('promoCode').value.trim();

    if (!promoCode) {
        showToast('{{ __("Promokodni kiriting") }}', 'warning');
        return;
    }

    showLoading('{{ __("Promokod tekshirilmoqda...") }}');

    fetch('{{ route("cart.promo.apply") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            promo_code: promoCode
        })
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

function removePromoCode() {
    showLoading('{{ __("Promokod olib tashlanmoqda...") }}');

    fetch('{{ route("cart.promo.remove") }}', {
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

function updateCartSummary(summary) {
    document.querySelector('.subtotal').textContent = summary.subtotal + ' {{ __("so\'m") }}';
    document.querySelector('.total-amount').textContent = summary.total + ' {{ __("so\'m") }}';

    const discountRow = document.querySelector('.summary-row.discount span:last-child');
    if (discountRow && summary.discount > 0) {
        discountRow.textContent = '-' + summary.discount + ' {{ __("so\'m") }}';
    }

    const deliveryRow = document.querySelector('.delivery-cost');
    if (deliveryRow) {
        deliveryRow.textContent = summary.delivery > 0 ?
            summary.delivery + ' {{ __("so\'m") }}' : '{{ __("Bepul") }}';
    }
}

function updateCartCount(count) {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = count;
        cartCount.style.display = count > 0 ? 'block' : 'none';
    }

    // Update page title
    const pageTitle = document.querySelector('.page-title');
    if (pageTitle) {
        pageTitle.textContent = `{{ __('Savat') }} (${count})`;
    }
}

// Auto-save cart state
let cartSaveTimeout;
function autoSaveCart() {
    clearTimeout(cartSaveTimeout);
    cartSaveTimeout = setTimeout(() => {
        fetch('{{ route("cart.save") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
    }, 2000);
}
</script>
@endpush

@push('styles')
<style>
.cart-page {
    padding: var(--spacing-md) 0 100px;
    min-height: 100vh;
}

.cart-header {
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

.clear-cart {
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

.clear-cart:hover {
    background: var(--error-color);
    color: white;
}

.cart-items {
    margin-bottom: var(--spacing-lg);
}

.cart-item {
    display: flex;
    gap: var(--spacing-sm);
    padding: var(--spacing-md);
    background: var(--surface-color);
    border-radius: var(--radius-lg);
    margin-bottom: var(--spacing-sm);
    transition: all 0.3s ease;
}

.cart-item:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.item-image {
    position: relative;
    width: 80px;
    height: 80px;
    border-radius: var(--radius-md);
    overflow: hidden;
    flex-shrink: 0;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.discount-badge {
    position: absolute;
    top: 4px;
    left: 4px;
    background: var(--error-color);
    color: white;
    padding: 2px 4px;
    border-radius: var(--radius-sm);
    font-size: 10px;
    font-weight: 600;
}

.item-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
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

.item-variants {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-xs);
    margin-bottom: var(--spacing-xs);
}

.variant {
    padding: 2px 6px;
    background: var(--hover-color);
    border-radius: var(--radius-sm);
    font-size: var(--text-xs);
    color: var(--text-secondary);
}

.item-price {
    margin-bottom: var(--spacing-sm);
}

.price-old {
    font-size: var(--text-xs);
    color: var(--text-secondary);
    text-decoration: line-through;
    margin-right: var(--spacing-xs);
}

.price-current {
    font-size: var(--text-sm);
    font-weight: 600;
    color: var(--primary-color);
}

.item-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--spacing-sm);
}

.quantity-control {
    display: flex;
    align-items: center;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    overflow: hidden;
}

.qty-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: var(--surface-color);
    color: var(--text-primary);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--text-sm);
    transition: background-color 0.3s ease;
}

.qty-btn:hover {
    background: var(--hover-color);
}

.qty-input {
    width: 40px;
    height: 32px;
    border: none;
    text-align: center;
    font-size: var(--text-sm);
    background: var(--surface-color);
}

.item-total {
    text-align: center;
}

.total-price {
    font-size: var(--text-sm);
    font-weight: 600;
    color: var(--text-primary);
}

.remove-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: none;
    color: var(--text-secondary);
    cursor: pointer;
    border-radius: var(--radius-md);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.remove-btn:hover {
    background: var(--error-color);
    color: white;
}

.promo-section {
    margin-bottom: var(--spacing-lg);
    padding: var(--spacing-md);
    background: var(--surface-color);
    border-radius: var(--radius-lg);
}

.promo-input {
    display: flex;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-sm);
}

.promo-input input {
    flex: 1;
    padding: var(--spacing-sm);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: var(--text-sm);
}

.applied-promo {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--spacing-sm);
    background: var(--success-color-light);
    border: 1px solid var(--success-color);
    border-radius: var(--radius-md);
    color: var(--success-color);
}

.promo-code {
    font-weight: 600;
    font-size: var(--text-sm);
}

.promo-discount {
    font-weight: 600;
    font-size: var(--text-sm);
}

.remove-promo {
    border: none;
    background: none;
    color: var(--success-color);
    cursor: pointer;
    padding: 4px;
    border-radius: var(--radius-sm);
    transition: background-color 0.3s ease;
}

.remove-promo:hover {
    background: rgba(34, 197, 94, 0.2);
}

.cart-summary {
    background: var(--surface-color);
    border-radius: var(--radius-lg);
    padding: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-xs) 0;
    font-size: var(--text-sm);
}

.summary-row.discount {
    color: var(--success-color);
}

.summary-row.total {
    border-top: 1px solid var(--border-color);
    margin-top: var(--spacing-sm);
    padding-top: var(--spacing-sm);
    font-size: var(--text-base);
    font-weight: 600;
}

.total-amount {
    font-size: var(--text-lg);
    font-weight: 700;
    color: var(--primary-color);
}

.cart-actions {
    display: flex;
    gap: var(--spacing-sm);
    position: sticky;
    bottom: 80px;
    background: var(--bg-color);
    padding: var(--spacing-sm) 0;
    z-index: 10;
}

.cart-actions .btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-xs);
    padding: var(--spacing-sm);
    font-size: var(--text-sm);
    font-weight: 600;
}

.empty-cart {
    text-align: center;
    padding: var(--spacing-xl) var(--spacing-md);
    margin-top: 10vh;
}

.empty-icon {
    font-size: 64px;
    color: var(--border-color);
    margin-bottom: var(--spacing-lg);
}

.empty-cart h2 {
    font-size: var(--text-xl);
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 var(--spacing-sm) 0;
}

.empty-cart p {
    color: var(--text-secondary);
    margin: 0 0 var(--spacing-lg) 0;
    font-size: var(--text-base);
}

.empty-cart .btn {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    padding: var(--spacing-sm) var(--spacing-lg);
    font-size: var(--text-base);
    font-weight: 600;
}

@keyframes slideOut {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(-100%);
    }
}

@media (max-width: 768px) {
    .cart-item {
        padding: var(--spacing-sm);
    }

    .item-image {
        width: 70px;
        height: 70px;
    }

    .item-name {
        font-size: var(--text-sm);
    }

    .quantity-control {
        order: 1;
    }

    .item-total {
        order: 2;
        margin-left: auto;
    }

    .remove-btn {
        order: 3;
    }

    .promo-input {
        flex-direction: column;
    }

    .cart-actions {
        flex-direction: column;
        bottom: 60px;
        padding: var(--spacing-md);
        background: linear-gradient(to top, var(--bg-color) 70%, transparent);
    }
}
</style>
@endpush
