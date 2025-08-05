@extends('web.layouts.app')

@section('title', $category->getName())

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-8">
            <a href="{{ route('web.home') }}" class="hover:text-blue-600">{{ __('web.nav.home') }}</a>
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <a href="{{ route('web.categories.index') }}" class="hover:text-blue-600">{{ __('web.nav.categories') }}</a>

            @if($breadcrumbs && count($breadcrumbs) > 1)
                @foreach($breadcrumbs as $breadcrumb)
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    @if($loop->last)
                        <span class="font-medium text-gray-900">{{ $breadcrumb->getName() }}</span>
                    @else
                        <a href="{{ route('web.categories.show', $breadcrumb->id) }}" class="hover:text-blue-600">{{ $breadcrumb->getName() }}</a>
                    @endif
                @endforeach
            @else
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium text-gray-900">{{ $category->getName() }}</span>
            @endif
        </nav>

        <!-- Category Header -->
        <div class="bg-gradient-to-r from-white to-blue-50 rounded-lg shadow-sm p-8 mb-8 border border-blue-100">
            <div class="flex items-start space-x-6">
                @if($category->image)
                    <div class="flex-shrink-0">
                        <div class="relative">
                            <img src="{{ asset('storage/' . $category->image) }}"
                                 alt="{{ $category->getName() }}"
                                 class="w-32 h-32 object-cover rounded-xl shadow-lg">
                            <div class="absolute inset-0 bg-black bg-opacity-20 rounded-xl"></div>
                        </div>
                    </div>
                @endif

                <div class="flex-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900 mb-3">{{ $category->getName() }}</h1>
                            @if($category->getDescription())
                                <p class="text-gray-600 leading-relaxed text-lg mb-4">{{ $category->getDescription() }}</p>
                            @endif
                        </div>

                        @if($category->is_featured)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Tavsiya etilgan
                            </span>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-6 text-sm">
                        <div class="flex items-center text-blue-600 font-medium">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"/>
                            </svg>
                            {{ $products->total() }} {{ __('web.category.products_found') }}
                        </div>

                        @if($priceRange && $priceRange->min && $priceRange->max)
                            <div class="flex items-center text-green-600 font-medium">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                </svg>
                                {{ number_format($priceRange->min) }} - {{ number_format($priceRange->max) }} {{ __('web.common.currency') }}
                            </div>
                        @endif

                        @if($childCategories && $childCategories->count() > 0)
                            <div class="flex items-center text-purple-600 font-medium">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"/>
                                </svg>
                                {{ $childCategories->count() }} {{ __('web.category.subcategories') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>        <!-- Child Categories -->
        @if($childCategories && $childCategories->count() > 0)
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">{{ __('web.category.subcategories') }}</h2>
                    <span class="text-sm text-gray-500">{{ $childCategories->count() }} ta kategoriya</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($childCategories as $childCategory)
                        <a href="{{ route('web.categories.show', $childCategory->id) }}"
                           class="group text-center p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 hover:shadow-md transition-all duration-200">
                            @if($childCategory->image)
                                <img src="{{ asset('storage/' . $childCategory->image) }}"
                                     alt="{{ $childCategory->getName() }}"
                                     class="w-12 h-12 mx-auto mb-3 object-cover rounded-lg group-hover:scale-110 transition-transform duration-200">
                            @else
                                <div class="w-12 h-12 mx-auto mb-3 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                            <h3 class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors duration-200">{{ $childCategory->getName() }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $childCategory->products_count ?? 0 }} {{ __('web.product.products') }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif        <!-- Filters and Sort -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <!-- Left Side - Filters -->
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
                        </svg>
                        {{ __('web.category.filter_by_price') }}:
                    </div>

                    <!-- Price Range Filter -->
                    <div class="flex items-center gap-2 bg-gray-50 rounded-lg p-2">
                        <input type="number" id="minPrice" placeholder="{{ __('web.common.from') }}"
                               class="border border-gray-300 rounded-md px-3 py-1.5 w-20 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               value="{{ request('price_min') }}">
                        <span class="text-gray-400 text-sm">—</span>
                        <input type="number" id="maxPrice" placeholder="{{ __('web.common.to') }}"
                               class="border border-gray-300 rounded-md px-3 py-1.5 w-20 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               value="{{ request('price_max') }}">
                        <button id="applyPriceFilter" class="bg-blue-600 text-white px-4 py-1.5 rounded-md hover:bg-blue-700 transition-colors duration-200 text-sm font-medium">
                            {{ __('web.product.apply_filter') }}
                        </button>
                    </div>
                </div>

                <!-- Right Side - Sort and View Options -->
                <div class="flex items-center gap-4">
                    <!-- Results Count -->
                    <div class="text-sm text-gray-600 hidden sm:block">
                        {{ $products->total() }} {{ __('web.category.products_found') }}
                    </div>

                    <!-- Sort Options -->
                    <div class="flex items-center gap-2">
                        <label for="sortBy" class="text-sm font-medium text-gray-700">{{ __('web.product.sort_by') }}:</label>
                        <select id="sortBy" class="border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                            <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>{{ __('web.product.sort_newest') }}</option>
                            <option value="price_low" {{ $sort == 'price_low' ? 'selected' : '' }}>{{ __('web.product.sort_price_low') }}</option>
                            <option value="price_high" {{ $sort == 'price_high' ? 'selected' : '' }}>{{ __('web.product.sort_price_high') }}</option>
                            <option value="popular" {{ $sort == 'popular' ? 'selected' : '' }}>{{ __('web.product.sort_popular') }}</option>
                            <option value="name_asc" {{ $sort == 'name_asc' ? 'selected' : '' }}>{{ __('web.product.sort_name_asc') }}</option>
                        </select>
                    </div>

                    <!-- View Toggle -->
                    <div class="flex border border-gray-300 rounded-lg overflow-hidden">
                        <button id="gridView" class="px-3 py-1.5 bg-blue-600 text-white hover:bg-blue-700 transition-colors duration-200" title="Grid View">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </button>
                        <button id="listView" class="px-3 py-1.5 bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors duration-200" title="List View">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 8a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 12a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div id="productsContainer">
            @if($products->count() > 0)
                <div id="gridContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                            <!-- Product Image -->
                            <div class="relative overflow-hidden">
                                @if($product->images && $product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                         alt="{{ $product->getName() }}"
                                         class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <svg class="w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Badges -->
                                <div class="absolute top-3 left-3 flex flex-col gap-2">
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        @php
                                            $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                                        @endphp
                                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full font-medium shadow-lg">
                                            -{{ $discount }}%
                                        </span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs px-2 py-1 rounded-full font-medium shadow-lg">
                                            {{ __('web.product.featured_badge') }}
                                        </span>
                                    @endif
                                    @if($product->stock_quantity <= 5 && $product->stock_quantity > 0)
                                        <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full font-medium shadow-lg">
                                            {{ $product->stock_quantity }} qoldi
                                        </span>
                                    @endif
                                </div>

                                <!-- Quick Actions -->
                                <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <button class="bg-white rounded-full p-2 shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-200"
                                            title="{{ __('web.category.quick_view') }}"
                                            onclick="quickView({{ $product->id }})">
                                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                    <button class="bg-white rounded-full p-2 shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-200"
                                            title="{{ __('web.category.add_to_wishlist') }}"
                                            onclick="addToWishlist({{ $product->id }})">
                                        <svg class="w-4 h-4 text-gray-600 hover:text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                    <button class="bg-white rounded-full p-2 shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-200"
                                            title="{{ __('web.category.compare') }}"
                                            onclick="addToCompare({{ $product->id }})">
                                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 11-2 0V4zm9 1a1 1 0 110-2h4a1 1 0 011 1v4a1 1 0 11-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 112 0v1.586l2.293-2.293a1 1 0 111.414 1.414L6.414 15H8a1 1 0 110 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 110-2h1.586l-2.293-2.293a1 1 0 111.414-1.414L15 13.586V12a1 1 0 011-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Stock overlay for out of stock -->
                                @if($product->stock_quantity <= 0)
                                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                        <span class="bg-red-600 text-white px-4 py-2 rounded-lg font-medium text-sm">
                                            {{ __('web.product.out_of_stock') }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="p-5">
                                <!-- Category Badge -->
                                @if($product->category)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mb-2 font-medium">
                                        {{ $product->category->getName() }}
                                    </span>
                                @endif

                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 text-lg leading-tight">
                                    <a href="{{ route('web.products.show', $product->id) }}"
                                       class="hover:text-blue-600 transition-colors duration-200">
                                        {{ $product->getName() }}
                                    </a>
                                </h3>

                                @if($product->getDescription())
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2 leading-relaxed">
                                        {{ Str::limit($product->getDescription(), 80) }}
                                    </p>
                                @endif

                                <!-- Rating and Reviews -->
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        @php $rating = $product->average_rating ?? 4; @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                        <span class="text-sm text-gray-500 ml-1">({{ $product->review_count ?? 0 }})</span>
                                    </div>

                                    @if($product->sku)
                                        <span class="text-xs text-gray-400 font-mono">{{ $product->sku }}</span>
                                    @endif
                                </div>

                                <!-- Price -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-baseline gap-2">
                                        @if($product->sale_price && $product->sale_price < $product->price)
                                            <span class="text-xl font-bold text-red-600">
                                                {{ number_format($product->sale_price) }} {{ __('web.common.currency') }}
                                            </span>
                                            <span class="text-sm text-gray-500 line-through">
                                                {{ number_format($product->price) }} {{ __('web.common.currency') }}
                                            </span>
                                        @else
                                            <span class="text-xl font-bold text-gray-900">
                                                {{ number_format($product->price) }} {{ __('web.common.currency') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Stock Status -->
                                <div class="flex items-center justify-between mb-4">
                                    @if($product->stock_quantity > 10)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                            {{ __('web.product.in_stock') }}
                                        </span>
                                    @elseif($product->stock_quantity > 0)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <div class="w-2 h-2 bg-orange-500 rounded-full mr-2"></div>
                                            {{ $product->stock_quantity }} qoldi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                            {{ __('web.product.out_of_stock') }}
                                        </span>
                                    @endif

                                    @if($product->shipping_info)
                                        <span class="text-xs text-gray-500">
                                            <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707L15 6.586A1 1 0 0014.414 6H14v1z"/>
                                            </svg>
                                            Bepul yetkazib berish
                                        </span>
                                    @endif
                                </div>

                                <!-- Add to Cart Button -->
                                @if($product->stock_quantity > 0)
                                    <button class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-4 rounded-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 font-medium shadow-lg hover:shadow-xl"
                                            onclick="addToCart({{ $product->id }})">
                                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                        </svg>
                                        {{ __('web.home.add_to_cart') }}
                                    </button>
                                @else
                                    <button class="w-full bg-gray-300 text-gray-500 py-3 px-4 rounded-lg cursor-not-allowed font-medium" disabled>
                                        {{ __('web.product.out_of_stock') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="mt-8">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">{{ __('web.product.no_products_found') }}</h3>
                    <p class="mt-2 text-gray-500">{{ __('web.product.try_different_filters') }}</p>
                    <div class="mt-6">
                        <a href="{{ route('web.categories.show', $category->id) }}"
                           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('web.product.reset_filters') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// View toggle functionality
const gridView = document.getElementById('gridView');
const listView = document.getElementById('listView');
const gridContainer = document.getElementById('gridContainer');

if (gridView && listView) {
    gridView.addEventListener('click', function() {
        gridContainer.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6';
        gridView.className = 'px-3 py-2 bg-blue-600 text-white hover:bg-blue-700';
        listView.className = 'px-3 py-2 bg-gray-100 text-gray-600 hover:bg-gray-200';
    });

    listView.addEventListener('click', function() {
        gridContainer.className = 'space-y-4';
        listView.className = 'px-3 py-2 bg-blue-600 text-white hover:bg-blue-700';
        gridView.className = 'px-3 py-2 bg-gray-100 text-gray-600 hover:bg-gray-200';
    });
}

// Sort functionality
const sortBy = document.getElementById('sortBy');
if (sortBy) {
    sortBy.addEventListener('change', function() {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', this.value);
        window.location.href = url.toString();
    });
}

// Price filter functionality
const applyPriceFilter = document.getElementById('applyPriceFilter');
if (applyPriceFilter) {
    applyPriceFilter.addEventListener('click', function() {
        const minPrice = document.getElementById('minPrice').value;
        const maxPrice = document.getElementById('maxPrice').value;

        const url = new URL(window.location.href);
        if (minPrice) {
            url.searchParams.set('price_min', minPrice);
        } else {
            url.searchParams.delete('price_min');
        }
        if (maxPrice) {
            url.searchParams.set('price_max', maxPrice);
        } else {
            url.searchParams.delete('price_max');
        }

        window.location.href = url.toString();
    });
}

// Add to cart functionality
function addToCart(productId) {
    showLoadingButton();
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
    })
    .finally(() => {
        hideLoadingButton();
    });
}

// Quick view functionality
function quickView(productId) {
    // Implement quick view modal
    window.open(`/products/${productId}`, '_blank', 'width=800,height=600');
}

// Add to wishlist functionality
function addToWishlist(productId) {
    fetch('/wishlist/add', {
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
            showMessage('success', 'Mahsulot sevimlilar ro\'yxatiga qo\'shildi');
        } else {
            showMessage('error', data.message || '{{ __("web.common.error") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('error', '{{ __("web.common.error") }}');
    });
}

// Add to compare functionality
function addToCompare(productId) {
    fetch('/compare/add', {
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
            showMessage('success', 'Mahsulot solishtirish ro\'yxatiga qo\'shildi');
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
    // Create and show enhanced notification
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white min-w-80`;
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                ${type === 'success' ?
                    '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>' :
                    '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>'
                }
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">${text}</p>
            </div>
            <div class="ml-4 flex-shrink-0">
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="rounded-md inline-flex text-white hover:text-gray-200 focus:outline-none">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => notification.classList.add('translate-x-0'), 100);

    // Auto remove after 4 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            if (notification.parentElement) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

function updateCartCount() {
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            const cartCountElements = document.querySelectorAll('.cart-count');
            cartCountElements.forEach(element => {
                element.textContent = data.count;
                // Add bounce animation
                element.classList.add('animate-bounce');
                setTimeout(() => element.classList.remove('animate-bounce'), 1000);
            });
        })
        .catch(error => console.error('Error updating cart count:', error));
}

// Price filter with Enter key support
document.addEventListener('DOMContentLoaded', function() {
    const priceInputs = document.querySelectorAll('#minPrice, #maxPrice');
    priceInputs.forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('applyPriceFilter').click();
            }
        });
    });

    // Auto-save filters in localStorage
    const minPrice = localStorage.getItem('categoryMinPrice');
    const maxPrice = localStorage.getItem('categoryMaxPrice');
    if (minPrice) document.getElementById('minPrice').value = minPrice;
    if (maxPrice) document.getElementById('maxPrice').value = maxPrice;
});
</script>
@endpush
@endsection
