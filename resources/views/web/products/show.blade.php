@extends('web.layouts.app')

@section('title', $product->getName())

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-600 mb-8">
        <a href="{{ route('web.home') }}" class="hover:text-blue-600">Главная</a>
        <span class="mx-2">/</span>
        <a href="{{ route('web.products.index') }}" class="hover:text-blue-600">Товары</a>
        @if($product->category)
            <span class="mx-2">/</span>
            <a href="{{ route('web.categories.show', $product->category->id) }}" class="hover:text-blue-600">
                {{ $product->category->getName() }}
            </a>
        @endif
        <span class="mx-2">/</span>
        <span>{{ $product->getName() }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Product Images -->
        <div class="space-y-4">
            <!-- Main Image -->
            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                @if($product->images->count() > 0)
                    <img id="mainImage"
                         src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                         alt="{{ $product->getName() }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Thumbnail Images -->
            @if($product->images->count() > 1)
                <div class="flex gap-2 overflow-x-auto">
                    @foreach($product->images as $image)
                        <button class="thumbnail-btn flex-shrink-0 w-20 h-20 bg-gray-100 rounded-lg overflow-hidden border-2 border-transparent hover:border-blue-500 focus:border-blue-500">
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                 alt="{{ $product->getName() }}"
                                 class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="space-y-6">
            <!-- Title and Rating -->
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->getName() }}</h1>

                <!-- Rating -->
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                        <span class="ml-2 text-sm text-gray-600">(4.0 из 5)</span>
                    </div>
                    <span class="text-sm text-gray-500">24 отзыва</span>
                </div>

                <!-- Badges -->
                <div class="flex gap-2 mb-4">
                    @if($product->is_sale)
                        <span class="bg-red-500 text-white text-sm px-3 py-1 rounded-full">СКИДКА</span>
                    @endif
                    @if($product->is_new)
                        <span class="bg-green-500 text-white text-sm px-3 py-1 rounded-full">НОВИНКА</span>
                    @endif
                    @if($product->quantity > 0)
                        <span class="bg-blue-500 text-white text-sm px-3 py-1 rounded-full">В НАЛИЧИИ</span>
                    @else
                        <span class="bg-gray-500 text-white text-sm px-3 py-1 rounded-full">НЕТ В НАЛИЧИИ</span>
                    @endif
                </div>
            </div>

            <!-- Price -->
            <div class="border-t border-b border-gray-200 py-6">
                <div class="flex items-center gap-4">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="text-3xl font-bold text-red-600">${{ number_format($product->sale_price, 2) }}</span>
                        <span class="text-xl text-gray-500 line-through">${{ number_format($product->price, 2) }}</span>
                        <span class="bg-red-100 text-red-600 text-sm px-2 py-1 rounded">
                            -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                        </span>
                    @else
                        <span class="text-3xl font-bold text-gray-800">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>
            </div>

            <!-- Description -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Описание</h3>
                <p class="text-gray-600 leading-relaxed">{{ $product->getDescription() }}</p>
            </div>

            <!-- Specifications -->
            @if($product->specifications)
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Характеристики</h3>
                    <div class="space-y-2">
                        @foreach(json_decode($product->specifications, true) ?? [] as $key => $value)
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">{{ $key }}:</span>
                                <span class="font-medium">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Quantity and Add to Cart -->
            <div class="space-y-4">
                @if($product->quantity > 0)
                    <div class="flex items-center gap-4">
                        <label for="quantity" class="text-sm font-medium text-gray-700">Количество:</label>
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button type="button" id="decreaseQty" class="px-3 py-2 text-gray-600 hover:text-gray-800">-</button>
                            <input type="number" id="quantity" value="1" min="1" max="{{ $product->quantity }}"
                                   class="w-16 text-center border-0 focus:ring-0 focus:outline-none">
                            <button type="button" id="increaseQty" class="px-3 py-2 text-gray-600 hover:text-gray-800">+</button>
                        </div>
                        <span class="text-sm text-gray-500">Доступно: {{ $product->quantity }} шт.</span>
                    </div>

                    <div class="flex gap-4">
                        <button id="addToCart" class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors duration-300 font-medium">
                            Добавить в корзину
                        </button>
                        <button class="bg-gray-100 text-gray-600 py-3 px-6 rounded-lg hover:bg-gray-200 transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                @else
                    <div class="bg-gray-100 text-gray-600 py-3 px-6 rounded-lg text-center">
                        Товар временно отсутствует
                    </div>
                @endif
            </div>

            <!-- Share -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Поделиться:</h3>
                <div class="flex gap-3">
                    <button class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </button>
                    <button class="bg-blue-800 text-white p-2 rounded-lg hover:bg-blue-900">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </button>
                    <button class="bg-pink-600 text-white p-2 rounded-lg hover:bg-pink-700">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.219-5.175 1.219-5.175s-.312-.625-.312-1.548c0-1.453.844-2.538 1.895-2.538.894 0 1.325.67 1.325 1.471 0 .896-.569 2.237-.863 3.48-.246 1.038.52 1.883 1.543 1.883 1.852 0 3.278-1.954 3.278-4.775 0-2.5-1.797-4.25-4.364-4.25-2.974 0-4.721 2.23-4.721 4.537 0 .897.347 1.862.78 2.386.086.103.098.194.072.3-.08.331-.256 1.037-.292 1.184-.047.194-.154.234-.355.142-1.313-.611-2.134-2.529-2.134-4.07 0-3.298 2.397-6.327 6.918-6.327 3.634 0 6.458 2.589 6.458 6.043 0 3.606-2.274 6.504-5.42 6.504-1.058 0-2.056-.549-2.396-1.272 0 0-.523 1.992-.65 2.482-.236.908-.874 2.043-1.301 2.734C9.543 23.746 10.755 24 12.017 24c6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001 12.017.001z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts && $relatedProducts->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-800 mb-8">Похожие товары</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Product Image -->
                        <div class="relative">
                            @if($relatedProduct->images->count() > 0)
                                <img src="{{ asset('storage/' . $relatedProduct->images->first()->image_path) }}"
                                     alt="{{ $relatedProduct->getName() }}"
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif

                            <!-- Badges -->
                            <div class="absolute top-2 left-2 flex flex-col gap-1">
                                @if($relatedProduct->is_sale)
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">СКИДКА</span>
                                @endif
                                @if($relatedProduct->is_new)
                                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded">НОВИНКА</span>
                                @endif
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">
                                <a href="{{ route('web.products.show', $relatedProduct->id) }}" class="hover:text-blue-600">
                                    {{ $relatedProduct->getName() }}
                                </a>
                            </h3>

                            <!-- Price -->
                            <div class="flex items-center justify-between mb-3">
                                @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                    <div>
                                        <span class="text-lg font-bold text-red-600">${{ number_format($relatedProduct->sale_price, 2) }}</span>
                                        <span class="text-sm text-gray-500 line-through block">${{ number_format($relatedProduct->price, 2) }}</span>
                                    </div>
                                @else
                                    <span class="text-lg font-bold text-gray-800">${{ number_format($relatedProduct->price, 2) }}</span>
                                @endif
                            </div>

                            <!-- Add to Cart Button -->
                            <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-300">
                                В корзину
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
    // Thumbnail image switching
    const mainImage = document.getElementById('mainImage');
    const thumbnailBtns = document.querySelectorAll('.thumbnail-btn');

    thumbnailBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const img = this.querySelector('img');
            if (img && mainImage) {
                mainImage.src = img.src;

                // Update active state
                thumbnailBtns.forEach(b => b.classList.remove('border-blue-500'));
                this.classList.add('border-blue-500');
            }
        });
    });

    // Quantity controls
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');

    if (decreaseBtn && increaseBtn && quantityInput) {
        decreaseBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });

        increaseBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            const maxValue = parseInt(quantityInput.getAttribute('max'));
            if (currentValue < maxValue) {
                quantityInput.value = currentValue + 1;
            }
        });
    }

    // Add to cart functionality
    const addToCartBtn = document.getElementById('addToCart');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const quantity = quantityInput ? quantityInput.value : 1;
            // Here you would typically make an AJAX request to add the product to cart
            alert(`Товар добавлен в корзину! Количество: ${quantity}`);
        });
    }
</script>
@endsection
