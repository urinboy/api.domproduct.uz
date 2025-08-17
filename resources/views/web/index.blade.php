@extends('web.layouts.app')

@section('title', __('web.home.title'))

@section('meta_description', __('web.home.meta_description'))

@section('meta_keywords', __('web.home.meta_keywords'))

@section('content')
    <!-- Categories Bar -->
    <nav class="bg-white shadow-sm border-b sticky top-16 z-40">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-6 py-3 overflow-x-auto scrollbar-hide">
                <a href="{{ route('web.categories.index') }}" class="flex items-center space-x-2 px-4 py-2 bg-primary text-white rounded-lg whitespace-nowrap hover:bg-primary-dark transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <span>{{ __('web.nav.categories') }}</span>
                </a>
                @foreach($categories->take(6) as $category)
                    <a href="{{ route('web.products.index', ['category' => $category->slug]) }}"
                       class="text-gray-700 hover:text-primary whitespace-nowrap transition-colors">
                        {{ $category->icon ?? '📦' }} {{ $category->getName() }}
                    </a>
                @endforeach
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6">
        <!-- Hero Section -->
        <section class="mb-8">
            <div class="bg-gradient-to-r from-primary to-green-600 rounded-2xl p-8 text-white relative overflow-hidden">
                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold mb-4">{{ __('web.home.hero_title') }}</h2>
                        <p class="text-lg mb-6 opacity-90">{{ __('web.home.hero_subtitle') }}</p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('web.products.index') }}" class="btn bg-white text-primary hover:bg-gray-100 font-semibold">
                                {{ __('web.home.hero_cta') }}
                            </a>
                            <a href="{{ route('web.categories.index') }}" class="btn bg-transparent border-2 border-white text-white hover:bg-white hover:text-primary font-semibold">
                                {{ __('web.nav.categories') }}
                            </a>
                        </div>
                    </div>
                    <div class="hidden lg:block">
                        <div class="relative">
                            <div class="floating-cart text-8xl opacity-80">🛒</div>
                            <div class="absolute top-4 right-4 text-4xl animate-bounce">🍎</div>
                            <div class="absolute bottom-8 left-8 text-3xl animate-pulse">🥛</div>
                            <div class="absolute top-12 left-12 text-3xl animate-bounce" style="animation-delay: 0.5s;">🍞</div>
                        </div>
                    </div>
                </div>

                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-white/20 to-transparent"></div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">{{ __('web.home.services.fast_delivery.title') }}</h3>
                    <p class="text-sm text-gray-600">{{ __('web.home.services.fast_delivery.description') }}</p>
                </div>

                <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">{{ __('web.home.services.quality.title') }}</h3>
                    <p class="text-sm text-gray-600">{{ __('web.home.services.quality.description') }}</p>
                </div>

                <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">{{ __('web.home.services.price.title') }}</h3>
                    <p class="text-sm text-gray-600">{{ __('web.home.services.price.description') }}</p>
                </div>
            </div>
        </section>


        <!-- Featured Products -->
        <section class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">{{ __('web.home.featured_products') }}</h3>
                    <p class="text-gray-600">{{ __('web.home.featured_description') }}</p>
                </div>
                <a href="{{ route('web.products.index', ['featured' => 'true']) }}" class="text-primary hover:text-primary-dark font-semibold">
                    {{ __('web.home.view_all') }} →
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($bestSellers as $product)
                    <div class="product-card bg-white rounded-xl p-4 card-hover">
                        <div class="relative mb-3">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-32 object-cover rounded-lg">
                            @else
                                <div class="w-full h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-400">Rasm yo'q</span>
                                </div>
                            @endif
                            @if($product->sale_price)
                                <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                    -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                </span>
                            @endif
                        </div>
                        <h4 class="font-semibold text-gray-800 text-sm mb-2 line-clamp-2">{{ $product->getName() }}</h4>
                        <div class="flex items-center justify-between">
                            <div>
                                @if($product->sale_price)
                                    <span class="text-primary font-bold text-sm">{{ number_format($product->sale_price) }} so'm</span>
                                    <span class="text-gray-400 line-through text-xs block">{{ number_format($product->price) }} so'm</span>
                                @else
                                    <span class="text-primary font-bold text-sm">{{ number_format($product->price) }} so'm</span>
                                @endif
                            </div>
                            <button onclick="addToCart({{ $product->id }})" class="bg-primary text-white rounded-lg p-2 hover:bg-primary-dark transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Special Offers -->
        @if($saleProducts->count() > 0)
            <section class="mb-8">
                <div class="bg-gradient-to-r from-red-500 to-pink-500 rounded-2xl p-6 text-white">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                        <div>
                            <h3 class="text-2xl font-bold mb-2">{{ __('web.home.special_offers.title') }}</h3>
                            <p class="opacity-90 mb-4">{{ __('web.home.special_offers.description') }}</p>
                            <a href="{{ route('web.products.index', ['on_sale' => 'true']) }}" class="btn bg-white text-red-500 hover:bg-gray-100 font-semibold">
                                {{ __('web.home.special_offers.view_deals') }}
                            </a>
                        </div>
                        <div class="text-right">
                            <div class="text-6xl mb-2">⏰</div>
                            <div id="countdown" class="text-xl font-semibold">
                                23:59:59
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- Categories Grid -->
        <section class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">{{ __('web.home.categories.title') }}</h3>
                    <p class="text-gray-600">{{ __('web.home.categories.description') }}</p>
                </div>
                <a href="{{ route('web.categories.index') }}" class="text-primary hover:text-primary-dark font-semibold">
                    {{ __('web.home.categories.view_all') }} →
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('web.products.index', ['category' => $category->getSlug()]) }}" class="product-card text-center p-6 card-hover">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">{{ $category->icon ?? '📦' }}</span>
                        </div>
                        <h4 class="font-semibold text-gray-800">{{ $category->getName() }}</h4>
                        <p class="text-sm text-gray-500">{{ $category->products_count ?? 0 }}+ {{ __('web.home.categories.products_count') }}</p>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- Popular Products -->
        @if($newProducts->count() > 0)
            <section class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">{{ __('web.home.new_products.title') }}</h3>
                        <p class="text-gray-600">{{ __('web.home.new_products.description') }}</p>
                    </div>
                    <a href="{{ route('web.products.index', ['sort' => 'newest']) }}" class="text-primary hover:text-primary-dark font-semibold">
                        {{ __('web.home.new_products.view_all') }} →
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($newProducts->take(6) as $product)
                        <div class="product-card bg-white rounded-xl p-4 card-hover">
                            <div class="relative mb-4">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-48 object-cover rounded-lg">
                                @else
                                    <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <span class="text-gray-400">Rasm yo'q</span>
                                    </div>
                                @endif
                                <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                                    Yangi
                                </span>
                                @if($product->sale_price)
                                    <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                        -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                    </span>
                                @endif
                            </div>
                            <h4 class="font-semibold text-gray-800 mb-2">{{ $product->getName() }}</h4>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->description ?? 'Mahsulot haqida ma\'lumot' }}</p>
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($product->sale_price)
                                        <span class="text-primary font-bold">{{ number_format($product->sale_price) }} so'm</span>
                                        <span class="text-gray-400 line-through text-sm block">{{ number_format($product->price) }} so'm</span>
                                    @else
                                        <span class="text-primary font-bold">{{ number_format($product->price) }} so'm</span>
                                    @endif
                                </div>
                                <button onclick="addToCart({{ $product->id }})" class="bg-primary text-white rounded-lg px-4 py-2 hover:bg-primary-dark transition-colors">
                                    {{ __('web.home.add_to_cart') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Newsletter Section -->
        <section class="mb-8">
            <div class="bg-gray-800 rounded-2xl p-8 text-white text-center">
                <h3 class="text-2xl font-bold mb-2">{{ __('web.home.newsletter.title') }}</h3>
                <p class="text-gray-300 mb-6">{{ __('web.home.newsletter.description') }}</p>
                <form action="{{ route('web.newsletter.subscribe') }}" method="POST" class="max-w-md mx-auto flex gap-3">
                    @csrf
                    <input type="email" name="email" placeholder="{{ __('web.home.newsletter.email_placeholder') }}" required
                           class="flex-1 px-4 py-3 rounded-lg bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary">
                    <button type="submit" class="btn bg-primary text-white hover:bg-primary-dark px-6">
                        {{ __('web.home.newsletter.subscribe_button') }}
                    </button>
                </form>
            </div>
        </section>
    </main>

    <!-- Bottom Navigation (Mobile) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
        <div class="flex items-center justify-around py-2">
            <a href="{{ route('web.home') }}" class="flex flex-col items-center py-2 px-3 text-primary">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
                <span class="text-xs mt-1">{{ __('web.home.mobile_nav.home') }}</span>
            </a>
            <a href="{{ route('web.categories.index') }}" class="flex flex-col items-center py-2 px-3 text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                <span class="text-xs mt-1">{{ __('web.home.mobile_nav.category') }}</span>
            </a>
            <a href="{{ route('web.cart') }}" class="flex flex-col items-center py-2 px-3 text-gray-500">
                <div class="relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9"/>
                    </svg>
                    <span class="absolute -top-2 -right-2 bg-accent text-xs text-white rounded-full w-4 h-4 flex items-center justify-center" id="mobile-cart-count">
                        {{ session('cart') ? count(session('cart')) : 0 }}
                    </span>
                </div>
                <span class="text-xs mt-1">{{ __('web.home.mobile_nav.cart') }}</span>
            </a>
            <a href="{{ route('web.products.search') }}" class="flex flex-col items-center py-2 px-3 text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span class="text-xs mt-1">{{ __('web.home.mobile_nav.search') }}</span>
            </a>
            @auth
                <a href="{{ route('web.profile') }}" class="flex flex-col items-center py-2 px-3 text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-xs mt-1">{{ __('web.home.mobile_nav.profile') }}</span>
                </a>
            @else
                <a href="{{ route('web.login') }}" class="flex flex-col items-center py-2 px-3 text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-xs mt-1">{{ __('auth.login') }}</span>
                </a>
            @endauth
        </div>
    </nav>


<!-- Best Sellers -->
@if($bestSellers->count() > 0)
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl md:text-3xl font-bold">Eng ko'p sotilgan</h2>
            <a href="{{ route('web.products.index', ['sort' => 'popular']) }}" class="text-primary hover:text-primary-dark font-semibold">
                Barchasini ko'rish →
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($bestSellers as $product)
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow group">
                <div class="relative">
                    <a href="{{ route('web.products.show', $product->id) }}">
                        @if($product->images && count($product->images) > 0)
                        <img src="{{ asset('storage/' . $product->images[0]) }}"
                             alt="{{ $product->getName() }}"
                             class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif
                    </a>
                    @if($product->sale_price)
                    <div class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                        -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                    </div>
                    @endif
                    <button onclick="addToFavorites({{ $product->id }})"
                            class="absolute top-2 right-2 w-8 h-8 bg-white/80 rounded-full flex items-center justify-center hover:bg-white transition-colors">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold mb-2 line-clamp-2">
                        <a href="{{ route('web.products.show', $product->id) }}" class="hover:text-primary transition-colors">
                            {{ $product->getName() }}
                        </a>
                    </h3>
                    <div class="flex items-center justify-between">
                        <div>
                            @if($product->sale_price)
                            <span class="text-lg font-bold text-primary">{{ number_format($product->sale_price, 0, '.', ' ') }} so'm</span>
                            <span class="text-sm text-gray-500 line-through ml-2">{{ number_format($product->price, 0, '.', ' ') }} so'm</span>
                            @else
                            <span class="text-lg font-bold text-primary">{{ number_format($product->price, 0, '.', ' ') }} so'm</span>
                            @endif
                        </div>
                        <button onclick="addToCart({{ $product->id }})"
                                class="btn btn-primary px-3 py-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 2.5M7 13v6a2 2 0 002 2h10a2 2 0 002-2v-6M7 13H5.4"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- New Products -->
@if($newProducts->count() > 0)
<section class="py-16 bg-gray-100">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl md:text-3xl font-bold">Yangi mahsulotlar</h2>
            <a href="{{ route('web.products.index', ['sort' => 'newest']) }}" class="text-primary hover:text-primary-dark font-semibold">
                Barchasini ko'rish →
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($newProducts as $product)
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow group">
                <div class="relative">
                    <a href="{{ route('web.products.show', $product->id) }}">
                        @if($product->images && count($product->images) > 0)
                        <img src="{{ asset('storage/' . $product->images[0]) }}"
                             alt="{{ $product->getName() }}"
                             class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif
                    </a>
                    <div class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                        Yangi
                    </div>
                    <button onclick="addToFavorites({{ $product->id }})"
                            class="absolute top-2 right-2 w-8 h-8 bg-white/80 rounded-full flex items-center justify-center hover:bg-white transition-colors">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold mb-2 line-clamp-2">
                        <a href="{{ route('web.products.show', $product->id) }}" class="hover:text-primary transition-colors">
                            {{ $product->getName() }}
                        </a>
                    </h3>
                    <div class="flex items-center justify-between">
                        <div>
                            @if($product->sale_price)
                            <span class="text-lg font-bold text-primary">{{ number_format($product->sale_price, 0, '.', ' ') }} so'm</span>
                            <span class="text-sm text-gray-500 line-through ml-2">{{ number_format($product->price, 0, '.', ' ') }} so'm</span>
                            @else
                            <span class="text-lg font-bold text-primary">{{ number_format($product->price, 0, '.', ' ') }} so'm</span>
                            @endif
                        </div>
                        <button onclick="addToCart({{ $product->id }})"
                                class="btn btn-primary px-3 py-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 2.5M7 13v6a2 2 0 002 2h10a2 2 0 002-2v-6M7 13H5.4"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Sale Products -->
@if($saleProducts->count() > 0)
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl md:text-3xl font-bold">Chegirmadagi mahsulotlar</h2>
            <a href="{{ route('web.products.index') }}" class="text-primary hover:text-primary-dark font-semibold">
                Barchasini ko'rish →
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($saleProducts as $product)
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow group">
                <div class="relative">
                    <a href="{{ route('web.products.show', $product->id) }}">
                        @if($product->images && count($product->images) > 0)
                        <img src="{{ asset('storage/' . $product->images[0]) }}"
                             alt="{{ $product->getName() }}"
                             class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif
                    </a>
                    <div class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                        -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                    </div>
                    <button onclick="addToFavorites({{ $product->id }})"
                            class="absolute top-2 right-2 w-8 h-8 bg-white/80 rounded-full flex items-center justify-center hover:bg-white transition-colors">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold mb-2 line-clamp-2">
                        <a href="{{ route('web.products.show', $product->id) }}" class="hover:text-primary transition-colors">
                            {{ $product->getName() }}
                        </a>
                    </h3>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-lg font-bold text-primary">{{ number_format($product->sale_price, 0, '.', ' ') }} so'm</span>
                            <span class="text-sm text-gray-500 line-through ml-2">{{ number_format($product->price, 0, '.', ' ') }} so'm</span>
                        </div>
                        <button onclick="addToCart({{ $product->id }})"
                                class="btn btn-primary px-3 py-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 2.5M7 13v6a2 2 0 002 2h10a2 2 0 002-2v-6M7 13H5.4"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Features -->
<section class="py-16 bg-secondary text-white">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Tez yetkazib berish</h3>
                <p class="text-gray-300">24 soat ichida eshigingizgacha yetkazib beramiz</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Sifat kafolati</h3>
                <p class="text-gray-300">Faqat yuqori sifatli mahsulotlar</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Xavfsiz to'lov</h3>
                <p class="text-gray-300">Barcha to'lov turlari qo'llab-quvvatlanadi</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Add to cart function
    function addToCart(productId, quantity = 1) {
        showLoading();

        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
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
                updateCartBadge();
                // Show success message
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            hideLoading();
            handleAjaxError(error);
        });
    }

    // Add to favorites function
    function addToFavorites(productId) {
        if (!window.authUser) {
            window.location.href = '/login';
            return;
        }

        fetch(`/products/${productId}/favorites`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || data.status) {
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            handleAjaxError(error);
        });
    }

    // Show notification function
    function showNotification(message, type = 'info') {
        // Simple alert for now, can be enhanced with toast notifications
        alert(message);
    }
</script>
@endpush
