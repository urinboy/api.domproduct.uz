<!-- Simple Product Card Component -->
<div class="product-card" data-product-id="{{ $product->id }}">
    <!-- Product Image -->
    <div class="product-image">
        <a href="{{ route('template.mobile.product.detail', $product->id) }}">
            <img src="{{ asset('images/placeholder.png') }}"
                 alt="{{ $product->name }}"
                 loading="lazy">
        </a>
    </div>

    <!-- Product Info -->
    <div class="product-info">
        <h3 class="product-title">
            <a href="{{ route('template.mobile.product.detail', $product->id) }}">
                {{ $product->name }}
            </a>
        </h3>

        <!-- Product Price -->
        <div class="product-pricing">
            <span class="current-price">{{ number_format($product->price) }} so'm</span>
        </div>

        <!-- Add to Cart Button -->
        <div class="product-actions">
            <button class="btn btn-primary add-to-cart-btn" onclick="addToCart({{ $product->id }})">
                <i class="fas fa-cart-plus"></i>
                Savatga
            </button>
        </div>
    </div>
</div>
