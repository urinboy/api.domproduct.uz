<div class="product-card">
    <div class="product-image">
        @if($product->productImages->count() > 0)
            <img src="{{ asset($product->productImages->first()->image_url) }}" alt="{{ $product->name }}" class="img-fluid">
        @else
            <div class="no-image">Rasm yo'q</div>
        @endif
    </div>
    <div class="product-info">
        <h5 class="product-title">{{ $product->name }}</h5>
        <p class="product-price">${{ $product->price }}</p>
        <a href="/w/product/{{ $product->id }}" class="btn btn-primary btn-sm">Batafsil</a>
    </div>
</div>
