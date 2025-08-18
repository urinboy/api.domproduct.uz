<!-- Product Card Component -->
<div class="product-card" data-product-id="{{ $product->id }}">
    <!-- Product Image -->
    <div class="product-image">
        <a href="{{ route('template.mobile.product.detail', $product->id) }}">
            <img src="{{ $product->image ?? asset('images/placeholder.png') }}"
                 alt="{{ app()->getLocale() == 'uz' ? $product->name_uz : $product->name }}"
                 loading="lazy"
                 onerror="this.src='{{ asset('images/placeholder.png') }}'">
        </a>

        <!-- Product Badges -->
        @if($product->discount > 0)
        <div class="product-badge discount">
            -{{ $product->discount }}%
        </div>
        @endif

        @if($product->is_new)
        <div class="product-badge new">
            {{ __('Yangi') }}
        </div>
        @endif

        @if(!$product->in_stock)
        <div class="product-badge out-of-stock">
            {{ __('Tugadi') }}
        </div>
        @endif

        <!-- Wishlist Button -->
        <button class="wishlist-btn {{ in_array($product->id, session('wishlist', [])) ? 'active' : '' }}"
                onclick="toggleWishlist({{ $product->id }})"
                title="{{ __('Sevimlilarga qo\'shish') }}">
            <i class="fas fa-heart"></i>
        </button>

        <!-- Quick View Button -->
        <button class="quick-view-btn" onclick="quickView({{ $product->id }})" title="{{ __('Tez ko\'rish') }}">
            <i class="fas fa-eye"></i>
        </button>
    </div>

    <!-- Product Info -->
    <div class="product-info">
        <div class="product-category">
            {{ app()->getLocale() == 'uz' ? $product->category->name_uz : $product->category->name }}
        </div>

        <h3 class="product-title">
            <a href="{{ route('template.mobile.product.detail', $product->id) }}">
                {{ app()->getLocale() == 'uz' ? $product->name_uz : $product->name }}
            </a>
        </h3>

        <!-- Product Rating -->
        <div class="product-rating">
            <div class="stars">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star {{ $i <= $product->rating ? 'active' : '' }}"></i>
                @endfor
            </div>
            <span class="rating-text">({{ $product->reviews_count ?? 0 }})</span>
        </div>

        <!-- Product Description -->
        <p class="product-description">
            {{ Str::limit(app()->getLocale() == 'uz' ? ($product->description_uz ?? '') : ($product->description ?? ''), 80) }}
        </p>

        <!-- Product Price -->
        <div class="product-price">
            @if($product->original_price && $product->original_price > $product->price)
                <span class="original-price">{{ number_format($product->original_price) }} {{ __('so\'m') }}</span>
            @endif
            <span class="current-price">{{ number_format($product->price) }} {{ __('so\'m') }}</span>
            @if($product->discount > 0)
                <span class="discount-percent">-{{ $product->discount }}%</span>
            @endif
        </div>

        <!-- Product Features -->
        @if($product->features)
        <div class="product-features">
            @foreach(array_slice($product->features, 0, 3) as $feature)
            <span class="feature-tag">{{ $feature }}</span>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Product Actions -->
    <div class="product-actions">
        <button class="btn btn-primary add-to-cart-btn"
                onclick="addToCart({{ $product->id }})"
                {{ !$product->in_stock ? 'disabled' : '' }}
                data-product-id="{{ $product->id }}">
            <i class="fas fa-shopping-cart"></i>
            {{ $product->in_stock ? __('Savatga qo\'shish') : __('Tugagan') }}
        </button>

        <div class="secondary-actions">
            <button class="btn btn-outline compare-btn" onclick="addToCompare({{ $product->id }})" title="{{ __('Taqqoslash') }}">
                <i class="fas fa-balance-scale"></i>
            </button>

            <a href="{{ route('template.mobile.product.detail', $product->id) }}" class="btn btn-outline view-btn" title="{{ __('Batafsil') }}">
                <i class="fas fa-eye"></i>
            </a>
        </div>
    </div>

    <!-- Stock Status -->
    @if($product->in_stock && $product->stock_quantity)
    <div class="stock-status">
        @if($product->stock_quantity <= 5)
            <span class="stock-low">{{ __('Faqat :count ta qoldi!', ['count' => $product->stock_quantity]) }}</span>
        @else
            <span class="stock-available">{{ __('Mavjud') }}</span>
        @endif
    </div>
    @endif
</div>
