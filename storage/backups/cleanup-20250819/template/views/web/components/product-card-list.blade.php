<div class="product-card-list">
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

    <div class="product-image-container">
        <div class="product-image">
            <a href="{{ route('product', $product->slug) }}" class="product-link">
                <img src="{{ asset('storage/' . $product->image) }}" 
                     alt="{{ $product->name }}" 
                     class="product-img">
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
        </div>
    </div>

    <div class="product-content">
        <div class="product-header">
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
                    {{ $product->name }}
                </a>
            </h3>

            @if($product->short_description)
                <p class="product-description">
                    {{ Str::limit($product->short_description, 120) }}
                </p>
            @endif
        </div>

        <div class="product-details">
            @if($product->key_features && count($product->key_features) > 0)
                <div class="product-features">
                    <h4 class="features-title">{{ __('Asosiy xususiyatlar:') }}</h4>
                    <ul class="features-list">
                        @foreach(array_slice($product->key_features, 0, 4) as $feature)
                            <li class="feature-item">{{ $feature }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="product-info-grid">
                @if($product->category)
                    <div class="info-item">
                        <span class="info-label">{{ __('Kategoriya') }}:</span>
                        <span class="info-value">{{ $product->category->name }}</span>
                    </div>
                @endif
                
                @if($product->model)
                    <div class="info-item">
                        <span class="info-label">{{ __('Model') }}:</span>
                        <span class="info-value">{{ $product->model }}</span>
                    </div>
                @endif
                
                @if($product->warranty_period)
                    <div class="info-item">
                        <span class="info-label">{{ __('Kafolat') }}:</span>
                        <span class="info-value">{{ $product->warranty_period }} {{ __('oy') }}</span>
                    </div>
                @endif
                
                <div class="info-item">
                    <span class="info-label">{{ __('Mavjudlik') }}:</span>
                    <span class="info-value availability {{ $product->stock > 0 ? 'in-stock' : 'out-of-stock' }}">
                        @if($product->stock > 0)
                            <i class="fas fa-check-circle"></i>
                            {{ __('Mavjud') }} ({{ $product->stock }} {{ __('dona') }})
                        @else
                            <i class="fas fa-times-circle"></i>
                            {{ __('Tugagan') }}
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="product-footer">
            <div class="product-pricing">
                <div class="product-price">
                    @if($product->discount > 0)
                        <span class="price-old">{{ number_format($product->price) }} {{ __('so\'m') }}</span>
                        <span class="price-current">{{ number_format($product->discounted_price) }} {{ __('so\'m') }}</span>
                        <span class="price-save">{{ number_format($product->price - $product->discounted_price) }} {{ __('so\'m tejash') }}</span>
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
            </div>

            <div class="product-actions-footer">
                @if($product->stock > 0)
                    <div class="product-cart-actions">
                        @if($product->has_variants)
                            <a href="{{ route('product', $product->slug) }}" class="btn btn-primary">
                                <i class="fas fa-cog"></i>
                                {{ __('Variantlarni tanlash') }}
                            </a>
                        @else
                            <div class="quantity-cart-group">
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
                                        onclick="addToCart({{ $product->id }}, this)">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span>{{ __('Savatga qo\'shish') }}</span>
                                </button>
                            </div>
                        @endif
                        
                        <button class="btn btn-secondary buy-now-btn" 
                                onclick="buyNow({{ $product->id }})">
                            <i class="fas fa-bolt"></i>
                            {{ __('Hoziroq xarid qilish') }}
                        </button>
                    </div>
                @else
                    <div class="out-of-stock-actions">
                        <button class="btn btn-outline btn-lg notify-btn" 
                                onclick="notifyWhenAvailable({{ $product->id }})">
                            <i class="fas fa-bell"></i>
                            {{ __('Kelganda xabar berish') }}
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
