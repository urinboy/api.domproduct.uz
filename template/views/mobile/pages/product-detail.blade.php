@extends('mobile.layouts.app')

@section('title', $product->name)

@section('content')
<div class="product-detail-page">
    <!-- Product Images -->
    <div class="product-images">
        <div class="image-gallery">
            <div class="main-image">
                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" id="mainImage">
                @if($product->discount > 0)
                <div class="discount-badge">-{{ $product->discount }}%</div>
                @endif
                <button class="wishlist-btn {{ $product->is_wishlisted ? 'active' : '' }}" onclick="toggleWishlist({{ $product->id }})">
                    <i class="fas fa-heart"></i>
                </button>
            </div>
            
            @if(count($product->images) > 1)
            <div class="image-thumbnails">
                @foreach($product->images as $key => $image)
                <div class="thumbnail {{ $key === 0 ? 'active' : '' }}" onclick="changeMainImage('{{ asset('storage/' . $image) }}', this)">
                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}">
                </div>
                @endforeach
            </div>
            @endif
        </div>
        
        <!-- Image indicators -->
        <div class="image-indicators">
            @foreach($product->images as $key => $image)
            <div class="indicator {{ $key === 0 ? 'active' : '' }}"></div>
            @endforeach
        </div>
    </div>

    <!-- Product Info -->
    <div class="product-info">
        <div class="container">
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="{{ route('home') }}">{{ __('Bosh sahifa') }}</a>
                <i class="fas fa-chevron-right"></i>
                <a href="{{ route('category', $product->category->slug) }}">{{ $product->category->name }}</a>
                <i class="fas fa-chevron-right"></i>
                <span>{{ $product->name }}</span>
            </div>

            <!-- Product Header -->
            <div class="product-header">
                <h1 class="product-title">{{ $product->name }}</h1>
                
                <div class="product-meta">
                    <div class="rating">
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $product->rating ? 'active' : '' }}"></i>
                            @endfor
                        </div>
                        <span class="rating-count">({{ $product->reviews_count }} {{ __('sharh') }})</span>
                    </div>
                    
                    <div class="product-code">
                        {{ __('Mahsulot kodi') }}: <strong>{{ $product->code }}</strong>
                    </div>
                </div>

                <!-- Price -->
                <div class="price-section">
                    @if($product->discount > 0)
                    <div class="price-old">{{ number_format($product->original_price) }} {{ __('so\'m') }}</div>
                    @endif
                    <div class="price-current">{{ number_format($product->price) }} {{ __('so\'m') }}</div>
                    @if($product->discount > 0)
                    <div class="price-save">{{ __('Tejaysiz') }}: {{ number_format($product->original_price - $product->price) }} {{ __('so\'m') }}</div>
                    @endif
                </div>

                <!-- Availability -->
                <div class="availability">
                    @if($product->in_stock)
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
            </div>

            <!-- Product Options -->
            @if($product->variants->count() > 0)
            <div class="product-options">
                @foreach($product->variants->groupBy('type') as $type => $variants)
                <div class="option-group">
                    <h4>{{ __($type) }}</h4>
                    <div class="option-values">
                        @foreach($variants as $variant)
                        <label class="option-value" data-variant="{{ $variant->id }}">
                            <input type="radio" name="{{ $type }}" value="{{ $variant->id }}" {{ $loop->first ? 'checked' : '' }}>
                            <span>{{ $variant->value }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Quantity and Actions -->
            <div class="product-actions">
                <div class="quantity-selector">
                    <button type="button" class="qty-btn" onclick="changeQuantity(-1)">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" readonly>
                    <button type="button" class="qty-btn" onclick="changeQuantity(1)">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <button class="btn btn-primary add-to-cart" onclick="addToCart({{ $product->id }})" {{ !$product->in_stock ? 'disabled' : '' }}>
                    <i class="fas fa-shopping-cart"></i>
                    <span>{{ __('Savatga qo\'shish') }}</span>
                </button>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <button class="quick-btn" onclick="buyNow({{ $product->id }})">
                    <i class="fas fa-bolt"></i>
                    <span>{{ __('Tezkor xarid') }}</span>
                </button>
                
                <button class="quick-btn" onclick="shareProduct()">
                    <i class="fas fa-share"></i>
                    <span>{{ __('Ulashish') }}</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Product Details -->
    <div class="product-details">
        <div class="container">
            <div class="detail-tabs">
                <div class="tab-headers">
                    <button class="tab-header active" data-tab="description">{{ __('Tavsif') }}</button>
                    <button class="tab-header" data-tab="specifications">{{ __('Xususiyatlar') }}</button>
                    <button class="tab-header" data-tab="reviews">{{ __('Sharhlar') }} ({{ $product->reviews_count }})</button>
                </div>

                <div class="tab-contents">
                    <div class="tab-content active" id="description">
                        <div class="description-content">
                            {!! $product->description !!}
                        </div>
                    </div>

                    <div class="tab-content" id="specifications">
                        @if($product->specifications->count() > 0)
                        <div class="specifications-table">
                            @foreach($product->specifications as $spec)
                            <div class="spec-row">
                                <div class="spec-name">{{ $spec->name }}</div>
                                <div class="spec-value">{{ $spec->value }}</div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="no-data">{{ __('Xususiyatlar kiritilmagan') }}</p>
                        @endif
                    </div>

                    <div class="tab-content" id="reviews">
                        <div class="reviews-section">
                            <!-- Reviews Summary -->
                            <div class="reviews-summary">
                                <div class="rating-overview">
                                    <div class="rating-score">
                                        <span class="score">{{ number_format($product->rating, 1) }}</span>
                                        <div class="stars">
                                            @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $product->rating ? 'active' : '' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="count">{{ $product->reviews_count }} {{ __('sharh') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Review -->
                            @auth
                            <div class="add-review">
                                <h4>{{ __('Sharh qoldirish') }}</h4>
                                <form action="{{ route('reviews.store', $product) }}" method="POST" class="review-form">
                                    @csrf
                                    <div class="rating-input">
                                        <span>{{ __('Baho') }}:</span>
                                        <div class="star-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                            <label class="star-label">
                                                <input type="radio" name="rating" value="{{ $i }}" required>
                                                <i class="fas fa-star"></i>
                                            </label>
                                            @endfor
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="comment">{{ __('Sharh') }}</label>
                                        <textarea name="comment" id="comment" rows="4" placeholder="{{ __('Mahsulot haqida fikringizni yozing...') }}" required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Sharh yuborish') }}
                                    </button>
                                </form>
                            </div>
                            @else
                            <div class="login-prompt">
                                <p>{{ __('Sharh qoldirish uchun') }} <a href="{{ route('login') }}">{{ __('kiring') }}</a></p>
                            </div>
                            @endauth

                            <!-- Reviews List -->
                            <div class="reviews-list">
                                @forelse($product->reviews()->latest()->take(10)->get() as $review)
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="reviewer-info">
                                            <div class="reviewer-avatar">
                                                {{ substr($review->user->name, 0, 1) }}
                                            </div>
                                            <div class="reviewer-details">
                                                <span class="reviewer-name">{{ $review->user->name }}</span>
                                                <span class="review-date">{{ $review->created_at->format('d.m.Y') }}</span>
                                            </div>
                                        </div>
                                        <div class="review-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'active' : '' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="review-content">
                                        <p>{{ $review->comment }}</p>
                                    </div>
                                </div>
                                @empty
                                <p class="no-reviews">{{ __('Hozircha sharhlar yo\'q') }}</p>
                                @endforelse

                                @if($product->reviews_count > 10)
                                <button class="btn btn-outline load-more-reviews" onclick="loadMoreReviews()">
                                    {{ __('Yana ko\'rish') }}
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="related-products">
        <div class="container">
            <h3 class="section-title">{{ __('O\'xshash mahsulotlar') }}</h3>
            <div class="products-grid">
                @foreach($relatedProducts as $relatedProduct)
                    @include('mobile.components.product-card', ['product' => $relatedProduct])
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeProductDetail();
});

function initializeProductDetail() {
    // Tab switching
    const tabHeaders = document.querySelectorAll('.tab-header');
    const tabContents = document.querySelectorAll('.tab-content');

    tabHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const tabName = header.dataset.tab;
            
            tabHeaders.forEach(h => h.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            header.classList.add('active');
            document.getElementById(tabName).classList.add('active');
        });
    });

    // Image gallery
    initializeImageGallery();
    
    // Star rating
    initializeStarRating();
}

function initializeImageGallery() {
    const thumbnails = document.querySelectorAll('.thumbnail');
    const indicators = document.querySelectorAll('.indicator');
    
    // Touch/swipe support for main image
    let startX = 0;
    let currentIndex = 0;
    const mainImage = document.getElementById('mainImage');
    
    mainImage.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
    });
    
    mainImage.addEventListener('touchend', (e) => {
        const endX = e.changedTouches[0].clientX;
        const diff = startX - endX;
        
        if (Math.abs(diff) > 50) {
            if (diff > 0 && currentIndex < thumbnails.length - 1) {
                currentIndex++;
            } else if (diff < 0 && currentIndex > 0) {
                currentIndex--;
            }
            
            if (thumbnails[currentIndex]) {
                thumbnails[currentIndex].click();
            }
        }
    });
}

function changeMainImage(imageSrc, thumbnail) {
    const mainImage = document.getElementById('mainImage');
    const thumbnails = document.querySelectorAll('.thumbnail');
    const indicators = document.querySelectorAll('.indicator');
    
    mainImage.src = imageSrc;
    
    thumbnails.forEach(t => t.classList.remove('active'));
    indicators.forEach(i => i.classList.remove('active'));
    
    thumbnail.classList.add('active');
    
    const index = Array.from(thumbnails).indexOf(thumbnail);
    if (indicators[index]) {
        indicators[index].classList.add('active');
    }
    
    currentIndex = index;
}

function initializeStarRating() {
    const starInputs = document.querySelectorAll('.star-rating input[type="radio"]');
    const starLabels = document.querySelectorAll('.star-rating .star-label');
    
    starLabels.forEach((label, index) => {
        label.addEventListener('click', () => {
            starLabels.forEach((l, i) => {
                if (i <= index) {
                    l.classList.add('active');
                } else {
                    l.classList.remove('active');
                }
            });
        });
    });
}

function changeQuantity(change) {
    const quantityInput = document.getElementById('quantity');
    const currentQty = parseInt(quantityInput.value);
    const newQty = currentQty + change;
    const maxQty = parseInt(quantityInput.max);
    
    if (newQty >= 1 && newQty <= maxQty) {
        quantityInput.value = newQty;
    }
}

function addToCart(productId) {
    const quantity = document.getElementById('quantity').value;
    const variants = getSelectedVariants();
    
    showLoading('{{ __("Savatga qo\'shilmoqda...") }}');
    
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: parseInt(quantity),
            variants: variants
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            showToast(data.message, 'success');
            updateCartCount(data.cart_count);
            
            // Update button text temporarily
            const btn = document.querySelector('.add-to-cart');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> <span>{{ __("Qo\'shildi") }}</span>';
            btn.classList.add('success');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
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

function buyNow(productId) {
    const quantity = document.getElementById('quantity').value;
    const variants = getSelectedVariants();
    
    showLoading('{{ __("Buyurtma tayyorlanmoqda...") }}');
    
    // Add to cart first, then redirect to checkout
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: parseInt(quantity),
            variants: variants,
            buy_now: true
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            window.location.href = '{{ route("checkout") }}';
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

function getSelectedVariants() {
    const variants = {};
    const variantInputs = document.querySelectorAll('.option-group input[type="radio"]:checked');
    
    variantInputs.forEach(input => {
        variants[input.name] = input.value;
    });
    
    return variants;
}

function toggleWishlist(productId) {
    const btn = document.querySelector('.wishlist-btn');
    const isActive = btn.classList.contains('active');
    
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
        if (data.success) {
            btn.classList.toggle('active');
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

function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $product->name }}',
            text: '{{ $product->short_description }}',
            url: window.location.href
        });
    } else {
        // Fallback - copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            showToast('{{ __("Havola nusxalandi") }}', 'success');
        });
    }
}

function loadMoreReviews() {
    // Implementation for loading more reviews via AJAX
    showLoading('{{ __("Sharhlar yuklanmoqda...") }}');
    
    // This would be implemented based on your pagination needs
    hideLoading();
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
.product-detail-page {
    padding-bottom: 80px;
}

.product-images {
    position: relative;
    background: var(--surface-color);
}

.image-gallery {
    position: relative;
}

.main-image {
    position: relative;
    width: 100%;
    height: 300px;
    overflow: hidden;
}

.main-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.discount-badge {
    position: absolute;
    top: var(--spacing-sm);
    left: var(--spacing-sm);
    background: var(--error-color);
    color: white;
    padding: 4px 8px;
    border-radius: var(--radius-sm);
    font-size: var(--text-xs);
    font-weight: 600;
}

.wishlist-btn {
    position: absolute;
    top: var(--spacing-sm);
    right: var(--spacing-sm);
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--text-lg);
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.wishlist-btn.active {
    color: var(--error-color);
    background: rgba(255, 255, 255, 1);
}

.image-thumbnails {
    display: flex;
    gap: var(--spacing-xs);
    padding: var(--spacing-sm);
    overflow-x: auto;
}

.thumbnail {
    flex-shrink: 0;
    width: 60px;
    height: 60px;
    border: 2px solid transparent;
    border-radius: var(--radius-sm);
    overflow: hidden;
    cursor: pointer;
    opacity: 0.6;
    transition: all 0.3s ease;
}

.thumbnail.active {
    border-color: var(--primary-color);
    opacity: 1;
}

.thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-indicators {
    display: flex;
    justify-content: center;
    gap: 8px;
    padding: var(--spacing-sm);
}

.indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--border-color);
    transition: background-color 0.3s ease;
}

.indicator.active {
    background: var(--primary-color);
}

.product-info {
    padding: var(--spacing-md) 0;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    font-size: var(--text-xs);
    color: var(--text-secondary);
    margin-bottom: var(--spacing-md);
    overflow-x: auto;
    white-space: nowrap;
}

.breadcrumb a {
    color: var(--text-secondary);
    text-decoration: none;
}

.breadcrumb a:hover {
    color: var(--primary-color);
}

.breadcrumb i {
    font-size: 10px;
}

.product-title {
    font-size: var(--text-xl);
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 var(--spacing-sm) 0;
    line-height: 1.3;
}

.product-meta {
    margin-bottom: var(--spacing-md);
}

.rating {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    margin-bottom: var(--spacing-xs);
}

.rating .stars {
    display: flex;
    gap: 2px;
}

.rating .fas.fa-star {
    color: var(--border-color);
    font-size: 12px;
}

.rating .fas.fa-star.active {
    color: var(--warning-color);
}

.rating-count {
    font-size: var(--text-xs);
    color: var(--text-secondary);
}

.product-code {
    font-size: var(--text-sm);
    color: var(--text-secondary);
}

.price-section {
    margin: var(--spacing-md) 0;
}

.price-old {
    font-size: var(--text-sm);
    color: var(--text-secondary);
    text-decoration: line-through;
    margin-bottom: 4px;
}

.price-current {
    font-size: var(--text-xl);
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 4px;
}

.price-save {
    font-size: var(--text-sm);
    color: var(--success-color);
    font-weight: 500;
}

.availability {
    margin-bottom: var(--spacing-md);
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

.product-options {
    margin-bottom: var(--spacing-md);
}

.option-group {
    margin-bottom: var(--spacing-md);
}

.option-group h4 {
    font-size: var(--text-sm);
    font-weight: 600;
    margin: 0 0 var(--spacing-xs) 0;
    color: var(--text-primary);
}

.option-values {
    display: flex;
    gap: var(--spacing-xs);
    flex-wrap: wrap;
}

.option-value {
    cursor: pointer;
}

.option-value input {
    display: none;
}

.option-value span {
    display: block;
    padding: var(--spacing-xs) var(--spacing-sm);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: var(--text-sm);
    transition: all 0.3s ease;
}

.option-value input:checked + span {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.product-actions {
    display: flex;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-md);
}

.quantity-selector {
    display: flex;
    align-items: center;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    overflow: hidden;
}

.qty-btn {
    padding: var(--spacing-sm);
    border: none;
    background: var(--surface-color);
    color: var(--text-primary);
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.qty-btn:hover {
    background: var(--hover-color);
}

.quantity-selector input {
    width: 60px;
    padding: var(--spacing-sm);
    border: none;
    text-align: center;
    font-size: var(--text-sm);
    background: var(--surface-color);
}

.add-to-cart {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-xs);
    padding: var(--spacing-sm) var(--spacing-md);
    font-size: var(--text-sm);
    font-weight: 600;
    transition: all 0.3s ease;
}

.add-to-cart.success {
    background: var(--success-color);
    border-color: var(--success-color);
}

.add-to-cart:disabled {
    background: var(--border-color);
    border-color: var(--border-color);
    color: var(--text-secondary);
    cursor: not-allowed;
}

.quick-actions {
    display: flex;
    gap: var(--spacing-xs);
}

.quick-btn {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    padding: var(--spacing-sm);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    background: var(--surface-color);
    color: var(--text-secondary);
    font-size: var(--text-xs);
    cursor: pointer;
    transition: all 0.3s ease;
}

.quick-btn:hover {
    background: var(--hover-color);
    border-color: var(--primary-color);
    color: var(--primary-color);
}

.product-details {
    background: var(--surface-color);
    margin-top: var(--spacing-md);
}

.detail-tabs {
    padding: var(--spacing-md) 0;
}

.tab-headers {
    display: flex;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: var(--spacing-md);
}

.tab-header {
    flex: 1;
    padding: var(--spacing-sm) var(--spacing-xs);
    border: none;
    background: none;
    color: var(--text-secondary);
    font-size: var(--text-sm);
    cursor: pointer;
    position: relative;
    transition: color 0.3s ease;
}

.tab-header.active {
    color: var(--primary-color);
}

.tab-header.active::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--primary-color);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.description-content {
    line-height: 1.6;
    color: var(--text-primary);
}

.specifications-table {
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    overflow: hidden;
}

.spec-row {
    display: flex;
    border-bottom: 1px solid var(--border-color);
}

.spec-row:last-child {
    border-bottom: none;
}

.spec-name {
    flex: 1;
    padding: var(--spacing-sm);
    background: var(--hover-color);
    font-weight: 500;
    border-right: 1px solid var(--border-color);
}

.spec-value {
    flex: 2;
    padding: var(--spacing-sm);
}

.reviews-section {
    margin-top: var(--spacing-md);
}

.reviews-summary {
    text-align: center;
    padding: var(--spacing-md);
    background: var(--hover-color);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-md);
}

.rating-score {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--spacing-xs);
}

.score {
    font-size: var(--text-xxl);
    font-weight: 700;
    color: var(--primary-color);
}

.add-review,
.login-prompt {
    margin-bottom: var(--spacing-md);
    padding: var(--spacing-md);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
}

.add-review h4 {
    margin: 0 0 var(--spacing-sm) 0;
    font-size: var(--text-base);
    color: var(--text-primary);
}

.rating-input {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-sm);
}

.star-rating {
    display: flex;
    gap: 4px;
}

.star-label {
    cursor: pointer;
}

.star-label input {
    display: none;
}

.star-label i {
    color: var(--border-color);
    font-size: var(--text-base);
    transition: color 0.3s ease;
}

.star-label.active i,
.star-label:hover i {
    color: var(--warning-color);
}

.review-form .form-group {
    margin-bottom: var(--spacing-sm);
}

.review-form label {
    display: block;
    margin-bottom: var(--spacing-xs);
    font-size: var(--text-sm);
    font-weight: 500;
}

.review-form textarea {
    width: 100%;
    padding: var(--spacing-sm);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    resize: vertical;
    font-family: inherit;
    font-size: var(--text-sm);
}

.reviews-list {
    margin-top: var(--spacing-md);
}

.review-item {
    padding: var(--spacing-md);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-sm);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: var(--spacing-sm);
}

.reviewer-info {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.reviewer-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: var(--text-sm);
    text-transform: uppercase;
}

.reviewer-name {
    font-weight: 500;
    color: var(--text-primary);
    display: block;
    font-size: var(--text-sm);
}

.review-date {
    color: var(--text-secondary);
    font-size: var(--text-xs);
}

.review-rating {
    display: flex;
    gap: 2px;
}

.review-content p {
    margin: 0;
    line-height: 1.5;
    color: var(--text-primary);
}

.related-products {
    padding: var(--spacing-lg) 0;
}

.section-title {
    font-size: var(--text-lg);
    font-weight: 600;
    margin: 0 0 var(--spacing-md) 0;
    color: var(--text-primary);
}

.no-data,
.no-reviews {
    text-align: center;
    color: var(--text-secondary);
    font-style: italic;
    padding: var(--spacing-md);
}

@media (max-width: 768px) {
    .main-image {
        height: 250px;
    }
    
    .product-title {
        font-size: var(--text-lg);
    }
    
    .price-current {
        font-size: var(--text-lg);
    }
    
    .tab-header {
        padding: var(--spacing-sm) var(--spacing-xs);
        font-size: var(--text-xs);
    }
    
    .quick-actions {
        flex-direction: column;
    }
    
    .quick-btn {
        flex-direction: row;
        justify-content: center;
    }
}
</style>
@endpush
