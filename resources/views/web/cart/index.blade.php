@extends('web.layouts.app')

@section('title', 'Savat - Dom Product')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('web.home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L9 5.414V17a1 1 0 102 0V5.414l5.293 5.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Bosh sahifa
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Savat</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">Savatcha</h1>
                        @if(isset($cartItems) && count($cartItems) > 0)
                            <button onclick="clearCart()" class="text-red-600 hover:text-red-800 text-sm">
                                Barcha mahsulotlarni o'chirish
                            </button>
                        @endif
                    </div>

                    @if(isset($cartItems) && count($cartItems) > 0)
                        <!-- Cart Items List -->
                        <div class="space-y-4" id="cart-items">
                            @foreach($cartItems as $item)
                                <div class="cart-item flex items-center justify-between p-4 border border-gray-200 rounded-lg" data-product-id="{{ $item['product']['id'] }}">
                                    <div class="flex items-center space-x-4">
                                        <!-- Product Image -->
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden">
                                            @if($item['product']['image'])
                                                <img src="{{ $item['product']['image'] }}" alt="{{ $item['product']['name'] }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Product Info -->
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-900">{{ $item['product']['name'] }}</h3>
                                            <p class="text-sm text-gray-500">SKU: {{ $item['product']['sku'] ?? 'N/A' }}</p>
                                            <div class="flex items-center space-x-2 mt-1">
                                                @if($item['product']['sale_price'])
                                                    <span class="text-lg font-bold text-green-600">{{ number_format($item['product']['sale_price']) }} so'm</span>
                                                    <span class="text-sm text-gray-500 line-through">{{ number_format($item['product']['price']) }} so'm</span>
                                                @else
                                                    <span class="text-lg font-bold text-gray-900">{{ number_format($item['product']['price']) }} so'm</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quantity and Actions -->
                                    <div class="flex items-center space-x-4">
                                        <!-- Quantity Controls -->
                                        <div class="flex items-center border border-gray-300 rounded-lg">
                                            <button onclick="updateQuantity({{ $item['product']['id'] }}, {{ $item['quantity'] - 1 }})"
                                                    class="px-3 py-1 text-gray-600 hover:text-gray-800 {{ $item['quantity'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                    {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <span class="px-4 py-1 text-gray-900 font-medium min-w-[40px] text-center">{{ $item['quantity'] }}</span>
                                            <button onclick="updateQuantity({{ $item['product']['id'] }}, {{ $item['quantity'] + 1 }})"
                                                    class="px-3 py-1 text-gray-600 hover:text-gray-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Subtotal -->
                                        <div class="text-right min-w-[100px]">
                                            <div class="font-bold text-gray-900">
                                                {{ number_format($item['subtotal']) }} so'm
                                            </div>
                                        </div>

                                        <!-- Remove Button -->
                                        <button onclick="removeFromCart({{ $item['product']['id'] }})"
                                                class="text-red-600 hover:text-red-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty Cart -->
                        <div class="text-center py-16">
                            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Savatcha bo'sh</h3>
                            <p class="text-gray-600 mb-6">Hozircha savatchangizda hech qanday mahsulot yo'q</p>
                            <a href="{{ route('web.products.index') }}"
                               class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"></path>
                                </svg>
                                Xarid qilishni boshlash
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Buyurtma ma'lumotlari</h2>

                    @if(isset($totals))
                        <div class="space-y-4">
                            <!-- Subtotal -->
                            <div class="flex justify-between text-gray-600">
                                <span>Mahsulotlar narxi:</span>
                                <span>{{ number_format($totals['subtotal']) }} so'm</span>
                            </div>

                            <!-- Shipping -->
                            <div class="flex justify-between text-gray-600">
                                <span>Yetkazib berish:</span>
                                <span class="text-green-600">
                                    @if($totals['subtotal'] >= 100000)
                                        Bepul
                                    @else
                                        25,000 so'm
                                    @endif
                                </span>
                            </div>

                            @if($totals['subtotal'] > 0 && $totals['subtotal'] < 100000)
                                <div class="text-sm text-gray-500">
                                    Bepul yetkazib berish uchun yana {{ number_format(100000 - $totals['subtotal']) }} so'm xarid qiling
                                </div>
                            @endif

                            <!-- Discount -->
                            @if($totals['discount'] > 0)
                                <div class="flex justify-between text-green-600">
                                    <span>Chegirma:</span>
                                    <span>-{{ number_format($totals['discount']) }} so'm</span>
                                </div>
                            @endif

                            <hr class="border-gray-200">

                            <!-- Total -->
                            <div class="flex justify-between text-lg font-bold text-gray-900">
                                <span>Jami:</span>
                                <span>{{ number_format($totals['total']) }} so'm</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-8 space-y-3">
                            @if($totals['total'] > 0)
                                <button onclick="proceedToCheckout()"
                                        class="w-full bg-primary text-white py-3 px-4 rounded-lg hover:bg-primary-dark transition-colors font-semibold">
                                    Buyurtma berish
                                </button>
                                <a href="{{ route('web.products.index') }}"
                                   class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-200 transition-colors text-center block">
                                    Xaridni davom ettirish
                                </a>
                            @endif
                        </div>

                        <!-- Trust Badges -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div class="text-xs text-gray-500">
                                    <svg class="w-6 h-6 mx-auto mb-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    Xavfsiz to'lov
                                </div>
                                <div class="text-xs text-gray-500">
                                    <svg class="w-6 h-6 mx-auto mb-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Tez yetkazib berish
                                </div>
                                <div class="text-xs text-gray-500">
                                    <svg class="w-6 h-6 mx-auto mb-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    Kafolat
                                </div>
                                <div class="text-xs text-gray-500">
                                    <svg class="w-6 h-6 mx-auto mb-1 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    24/7 qo'llab-quvvatlash
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Cart functionality
function updateQuantity(productId, newQuantity) {
    if (newQuantity < 1) return;

    showLoading();

    fetch('{{ route("web.cart.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            location.reload(); // Reload to update the cart display
        } else {
            alert(data.message || 'Xatolik yuz berdi');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        alert('Xatolik yuz berdi');
    });
}

function removeFromCart(productId) {
    if (!confirm('Ushbu mahsulotni savatdan o\'chirmoqchimisiz?')) return;

    showLoading();

    fetch('{{ route("web.cart.remove") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            location.reload(); // Reload to update the cart display
            updateCartBadge(); // Update cart badge in header
        } else {
            alert(data.message || 'Xatolik yuz berdi');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        alert('Xatolik yuz berdi');
    });
}

function clearCart() {
    if (!confirm('Barcha mahsulotlarni savatdan o\'chirmoqchimisiz?')) return;

    showLoading();

    fetch('{{ route("web.cart.clear") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            location.reload(); // Reload to update the cart display
            updateCartBadge(); // Update cart badge in header
        } else {
            alert(data.message || 'Xatolik yuz berdi');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        alert('Xatolik yuz berdi');
    });
}

function proceedToCheckout() {
    // Check if user is authenticated
    @auth
        // TODO: Redirect to checkout page when implemented
        alert('Checkout sahifasi hozircha ishlab chiqilmoqda. Iltimos, kuting...');
    @else
        // Save current URL as intended URL and redirect to login
        sessionStorage.setItem('intended_url', window.location.href);
        window.location.href = '{{ route("web.auth.login") }}?intended=' + encodeURIComponent(window.location.href);
    @endauth
}
</script>
@endpush
@endsection
