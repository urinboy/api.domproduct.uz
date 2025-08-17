@if(!isset($style))
    @php $style = 'default'; @endphp
@endif

<div class="product-card {{ $style === 'sale' ? 'product-sale' : '' }}">
    @if($product->discount > 0)
        <div class="product-badge discount">
            -{{ $product->discount }}%
        </div>
    @endif
    
    @if($product->is_new)
        <div class="product-badge new">{{ __('Yangi') }}</div>
    @endif
    
    @if($product->stock <= 5 && $product->stock > 0)
        <div class="product-badge limited">{{ __('Kam qoldi') }}</div>
    @elseif($product->stock <= 0)
        <div class="product-badge out-of-stock">{{ __('Tugagan') }}</div>
    @endif

    <div class="product-image">
        <a href="{{ route('product', $product->slug) }}" class="product-link">
            <img src="{{ asset('storage/' . $product->image) }}" 
                 alt="{{ $product->name }}" 
                 class="product-img">
            
            @if($product->images->count() > 1)
                <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                     alt="{{ $product->name }}" 
                     class="product-img-hover">
            @endif
        </a>
        
        <div class="product-actions">
            <button class="product-action wishlist-btn {{ $product->isInWishlist() ? 'active' : '' }}" 
                    onclick="toggleWishlist({{ $product->id }})"
                    title="{{ __('Sevimliga qo\'shish') }}">
                <i class="far fa-heart"></i>
                <i class="fas fa-heart"></i>
            </button>
            
            <button class="product-action compare-btn {{ $product->isInCompare() ? 'active' : '' }}" 
                    onclick="toggleCompare({{ $product->id }})"
                    title="{{ __('Solishtirish') }}">
                <i class="fas fa-balance-scale"></i>
            </button>
            
            <button class="product-action quick-view-btn" 
                    onclick="quickView({{ $product->id }})"
                    title="{{ __('Tezkor ko\'rish') }}">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        
        @if($style === 'sale' && isset($product->sale_end_time))
            <div class="sale-timer" data-end-time="{{ $product->sale_end_time }}">
                <div class="timer-item">
                    <span class="timer-value hours">00</span>
                    <span class="timer-label">{{ __('s') }}</span>
                </div>
                <div class="timer-separator">:</div>
                <div class="timer-item">
                    <span class="timer-value minutes">00</span>
                    <span class="timer-label">{{ __('d') }}</span>
                </div>
                <div class="timer-separator">:</div>
                <div class="timer-item">
                    <span class="timer-value seconds">00</span>
                    <span class="timer-label">{{ __('s') }}</span>
                </div>
            </div>
        @endif
    </div>

    <div class="product-content">
        <div class="product-meta">
            @if($product->brand)
                <a href="{{ route('brand', $product->brand->slug) }}" class="product-brand">
                    {{ $product->brand->name }}
                </a>
            @endif
            
            <div class="product-rating">
                @php
                    $rating = $product->rating ?? 0;
                    $fullStars = floor($rating);
                    $hasHalfStar = $rating - $fullStars >= 0.5;
                @endphp
                
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $fullStars)
                            <i class="fas fa-star active"></i>
                        @elseif($i == $fullStars + 1 && $hasHalfStar)
                            <i class="fas fa-star-half-alt active"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                </div>
                
                <span class="rating-count">({{ $product->reviews_count ?? 0 }})</span>
            </div>
        </div>

        <h3 class="product-title">
            <a href="{{ route('product', $product->slug) }}" class="product-name">
                {{ Str::limit($product->name, 50) }}
            </a>
        </h3>

        @if($product->short_description)
            <p class="product-description">
                {{ Str::limit($product->short_description, 60) }}
            </p>
        @endif

        <div class="product-price">
            @if($product->discount > 0)
                <span class="price-old">{{ number_format($product->price) }} {{ __('so\'m') }}</span>
                <span class="price-current">{{ number_format($product->discounted_price) }} {{ __('so\'m') }}</span>
            @else
                <span class="price-current">{{ number_format($product->price) }} {{ __('so\'m') }}</span>
            @endif
        </div>

        @if($product->installment_available)
            <div class="product-installment">
                <i class="fas fa-credit-card"></i>
                {{ __('dan') }} {{ number_format($product->monthly_payment) }} {{ __('so\'m/oy') }}
            </div>
        @endif

        <div class="product-footer">
            @if($product->stock > 0)
                <div class="product-cart">
                    @if($product->has_variants)
                        <a href="{{ route('product', $product->slug) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-cog"></i>
                            {{ __('Tanlash') }}
                        </a>
                    @else
                        <div class="quantity-selector">
                            <button type="button" class="qty-btn minus" onclick="changeQuantity(this, -1)">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" value="1" min="1" max="{{ $product->stock }}" class="qty-input">
                            <button type="button" class="qty-btn plus" onclick="changeQuantity(this, 1)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        
                        <button class="btn btn-primary add-to-cart-btn" 
                                onclick="addToCart({{ $product->id }}, this)"
                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart"></i>
                            <span>{{ __('Savatga') }}</span>
                        </button>
                    @endif
                </div>
                
                <button class="btn btn-secondary buy-now-btn" 
                        onclick="buyNow({{ $product->id }})">
                    <i class="fas fa-bolt"></i>
                    {{ __('Hoziroq xarid') }}
                </button>
            @else
                <div class="out-of-stock-actions">
                    <button class="btn btn-outline btn-block notify-btn" 
                            onclick="notifyWhenAvailable({{ $product->id }})">
                        <i class="fas fa-bell"></i>
                        {{ __('Kelganda xabar berish') }}
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Quantity selector
function changeQuantity(btn, change) {
    const input = btn.parentNode.querySelector('.qty-input');
    const currentValue = parseInt(input.value);
    const newValue = currentValue + change;
    const min = parseInt(input.min);
    const max = parseInt(input.max);
    
    if (newValue >= min && newValue <= max) {
        input.value = newValue;
        input.dispatchEvent(new Event('change'));
    }
}

// Add to cart
function addToCart(productId, btn) {
    const card = btn.closest('.product-card');
    const qtyInput = card.querySelector('.qty-input');
    const quantity = qtyInput ? parseInt(qtyInput.value) : 1;
    
    // Animation
    btn.classList.add('loading');
    btn.disabled = true;
    
    // Add loading spinner
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>{{ __("Qo\'shilmoqda...") }}</span>';
    
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        btn.classList.remove('loading');
        btn.disabled = false;
        btn.innerHTML = originalContent;
        
        if (data.success) {
            showToast(data.message, 'success');
            updateCartCount(data.cart_count);
            
            // Add to cart animation
            animateAddToCart(card, btn);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        btn.classList.remove('loading');
        btn.disabled = false;
        btn.innerHTML = originalContent;
        showToast('{{ __("Xatolik yuz berdi") }}', 'error');
        console.error('Error:', error);
    });
}

// Buy now
function buyNow(productId) {
    const card = event.target.closest('.product-card');
    const qtyInput = card.querySelector('.qty-input');
    const quantity = qtyInput ? parseInt(qtyInput.value) : 1;
    
    showLoading('{{ __("Buyurtma tayyorlanmoqda...") }}');
    
    fetch('{{ route("cart.buy-now") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            window.location.href = data.redirect_url;
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

// Toggle wishlist
function toggleWishlist(productId) {
    const btn = event.target.closest('.wishlist-btn');
    const isActive = btn.classList.contains('active');
    
    btn.classList.add('loading');
    
    fetch('{{ route("wishlist.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        btn.classList.remove('loading');
        
        if (data.success) {
            btn.classList.toggle('active');
            showToast(data.message, 'success');
            updateWishlistCount(data.wishlist_count);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        btn.classList.remove('loading');
        showToast('{{ __("Xatolik yuz berdi") }}', 'error');
        console.error('Error:', error);
    });
}

// Toggle compare
function toggleCompare(productId) {
    const btn = event.target.closest('.compare-btn');
    
    btn.classList.add('loading');
    
    fetch('{{ route("compare.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        btn.classList.remove('loading');
        
        if (data.success) {
            btn.classList.toggle('active');
            showToast(data.message, 'success');
            updateCompareCount(data.compare_count);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        btn.classList.remove('loading');
        showToast('{{ __("Xatolik yuz berdi") }}', 'error');
        console.error('Error:', error);
    });
}

// Quick view
function quickView(productId) {
    showLoading('{{ __("Mahsulot ma\'lumotlari yuklanmoqda...") }}');
    
    fetch(`{{ route("product.quick-view", "") }}/${productId}`)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                showQuickViewModal(data.html);
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

// Notify when available
function notifyWhenAvailable(productId) {
    if (!isAuthenticated()) {
        showAuthModal();
        return;
    }
    
    const email = prompt('{{ __("Email manzilingizni kiriting:") }}');
    if (!email) return;
    
    fetch('{{ route("product.notify") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId,
            email: email
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        showToast('{{ __("Xatolik yuz berdi") }}', 'error');
        console.error('Error:', error);
    });
}

// Add to cart animation
function animateAddToCart(card, btn) {
    const productImg = card.querySelector('.product-img');
    const cartIcon = document.querySelector('.cart-count');
    
    if (!productImg || !cartIcon) return;
    
    // Clone the product image
    const flyingImg = productImg.cloneNode(true);
    flyingImg.classList.add('flying-to-cart');
    
    // Get positions
    const imgRect = productImg.getBoundingClientRect();
    const cartRect = cartIcon.getBoundingClientRect();
    
    // Set initial position
    flyingImg.style.position = 'fixed';
    flyingImg.style.top = imgRect.top + 'px';
    flyingImg.style.left = imgRect.left + 'px';
    flyingImg.style.width = imgRect.width + 'px';
    flyingImg.style.height = imgRect.height + 'px';
    flyingImg.style.zIndex = '9999';
    flyingImg.style.pointerEvents = 'none';
    flyingImg.style.transition = 'all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
    
    document.body.appendChild(flyingImg);
    
    // Animate to cart
    requestAnimationFrame(() => {
        flyingImg.style.top = cartRect.top + 'px';
        flyingImg.style.left = cartRect.left + 'px';
        flyingImg.style.width = '20px';
        flyingImg.style.height = '20px';
        flyingImg.style.opacity = '0';
    });
    
    // Remove after animation
    setTimeout(() => {
        if (flyingImg.parentNode) {
            flyingImg.parentNode.removeChild(flyingImg);
        }
        
        // Cart icon bounce animation
        cartIcon.style.transform = 'scale(1.2)';
        setTimeout(() => {
            cartIcon.style.transform = 'scale(1)';
        }, 200);
    }, 800);
}

// Initialize sale timers
document.addEventListener('DOMContentLoaded', function() {
    const saleTimers = document.querySelectorAll('.sale-timer');
    
    saleTimers.forEach(timer => {
        const endTime = new Date(timer.dataset.endTime).getTime();
        
        const interval = setInterval(() => {
            const now = new Date().getTime();
            const distance = endTime - now;
            
            if (distance < 0) {
                clearInterval(interval);
                timer.innerHTML = '<span class="timer-ended">{{ __("Tugadi") }}</span>';
                return;
            }
            
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            timer.querySelector('.hours').textContent = hours.toString().padStart(2, '0');
            timer.querySelector('.minutes').textContent = minutes.toString().padStart(2, '0');
            timer.querySelector('.seconds').textContent = seconds.toString().padStart(2, '0');
        }, 1000);
    });
});
</script>
@endpush
