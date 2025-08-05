@extends('web.layouts.app')

@section('title', __('web.common.wishlist'))

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ __('web.common.wishlist') }}</h1>
                    <p class="mt-2 text-gray-600">{{ __('web.profile.wishlist_description') }}</p>
                </div>
                <div class="text-sm text-gray-500">
                    {{ $wishlistItems->count() }} {{ __('web.common.items') }}
                </div>
            </div>
        </div>

        @if($wishlistItems->count() > 0)
            <!-- Wishlist Items -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="divide-y divide-gray-200">
                    @foreach($wishlistItems as $item)
                        <div class="p-6 flex items-center space-x-6" data-wishlist-item="{{ $item->id }}">
                            <!-- Product Image -->
                            <div class="flex-shrink-0">
                                <img src="{{ $item->product->image_url ?? '/images/no-image.png' }}"
                                     alt="{{ $item->product->title }}"
                                     class="w-24 h-24 object-cover rounded-lg">
                            </div>

                            <!-- Product Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 hover:text-blue-600">
                                            <a href="{{ route('web.products.show', $item->product->id) }}">
                                                {{ $item->product->title }}
                                            </a>
                                        </h3>

                                        @if($item->product->category)
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $item->product->category->name }}
                                            </p>
                                        @endif

                                        <div class="mt-2 flex items-center space-x-4">
                                            <!-- Price -->
                                            <div class="flex items-center space-x-2">
                                                @if($item->product->discount_price)
                                                    <span class="text-xl font-bold text-red-600">
                                                        {{ number_format($item->product->discount_price) }} {{ __('web.common.currency') }}
                                                    </span>
                                                    <span class="text-lg text-gray-500 line-through">
                                                        {{ number_format($item->product->price) }} {{ __('web.common.currency') }}
                                                    </span>
                                                @else
                                                    <span class="text-xl font-bold text-gray-900">
                                                        {{ number_format($item->product->price) }} {{ __('web.common.currency') }}
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Stock Status -->
                                            @if($item->product->stock > 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ __('web.product.in_stock') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    {{ __('web.product.out_of_stock') }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Added Date -->
                                        <p class="text-sm text-gray-500 mt-2">
                                            {{ __('web.profile.added_to_wishlist') }}: {{ $item->created_at->format('d.m.Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col space-y-3">
                                @if($item->product->stock > 0)
                                    <!-- Add to Cart -->
                                    <button type="button"
                                            onclick="addToCart({{ $item->product->id }})"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m1.6 8L5 3H3m4 10v6a1 1 0 001 1h10a1 1 0 001-1v-6M9 19v2m6-2v2"></path>
                                        </svg>
                                        {{ __('web.home.add_to_cart') }}
                                    </button>
                                @endif

                                <!-- Remove from Wishlist -->
                                <button type="button"
                                        onclick="removeFromWishlist({{ $item->id }})"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    {{ __('web.common.remove') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            @if($wishlistItems->hasPages())
                <div class="mt-8">
                    {{ $wishlistItems->links() }}
                </div>
            @endif

        @else
            <!-- Empty Wishlist -->
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <h3 class="mt-4 text-xl font-semibold text-gray-900">{{ __('web.profile.wishlist_empty') }}</h3>
                <p class="mt-2 text-gray-500">{{ __('web.profile.wishlist_empty_message') }}</p>
                <div class="mt-6">
                    <a href="{{ route('web.products.index') }}"
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('web.common.continue_shopping') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Success Message -->
<div id="success-message" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span id="success-text"></span>
    </div>
</div>

<!-- Error Message -->
<div id="error-message" class="hidden fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <span id="error-text"></span>
    </div>
</div>

@endsection

@push('scripts')
<script>
function addToCart(productId) {
    fetch('/cart/add', {
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
        if (data.success) {
            showMessage('success', '{{ __("web.cart.item_added") }}');
            updateCartCount();
        } else {
            showMessage('error', data.message || '{{ __("web.common.error") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('error', '{{ __("web.common.error") }}');
    });
}

function removeFromWishlist(itemId) {
    if (!confirm('{{ __("web.profile.confirm_remove_wishlist") }}')) {
        return;
    }

    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            wishlist_item_id: itemId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove item from DOM
            const itemElement = document.querySelector(`[data-wishlist-item="${itemId}"]`);
            if (itemElement) {
                itemElement.remove();
            }

            showMessage('success', '{{ __("web.profile.removed_from_wishlist") }}');

            // Check if no items left
            const remainingItems = document.querySelectorAll('[data-wishlist-item]');
            if (remainingItems.length === 0) {
                location.reload();
            }
        } else {
            showMessage('error', data.message || '{{ __("web.common.error") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('error', '{{ __("web.common.error") }}');
    });
}

function showMessage(type, text) {
    const messageElement = document.getElementById(type + '-message');
    const textElement = document.getElementById(type + '-text');

    textElement.textContent = text;
    messageElement.classList.remove('hidden');

    setTimeout(() => {
        messageElement.classList.add('hidden');
    }, 3000);
}

function updateCartCount() {
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            const cartCountElements = document.querySelectorAll('.cart-count');
            cartCountElements.forEach(element => {
                element.textContent = data.count;
            });
        })
        .catch(error => console.error('Error updating cart count:', error));
}
</script>
@endpush
