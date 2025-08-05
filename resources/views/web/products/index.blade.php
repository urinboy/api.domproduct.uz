@extends('web.layouts.app')

@section('title', __('web.nav.products'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ __('web.nav.products') }}</h1>
        <nav class="text-sm text-gray-600">
            <a href="{{ route('web.home') }}" class="hover:text-blue-600">{{ __('web.nav.home') }}</a>
            <span class="mx-2">/</span>
            <span>{{ __('web.nav.products') }}</span>
        </nav>
    </div>

    <!-- Filters and Sort -->
    <div class="mb-6 flex flex-col lg:flex-row justify-between items-center gap-4">
        <div class="flex flex-wrap items-center gap-4">
            <!-- Category Filter -->
            <div class="relative">
                <select id="categoryFilter" class="border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ __('web.product.all_categories') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->getName() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Price Range -->
            <div class="flex items-center gap-2">
                <input type="number" id="minPrice" placeholder="{{ __('web.common.from') }}"
                       class="border border-gray-300 rounded-lg px-3 py-2 w-20 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       value="{{ request('min_price') }}">
                <span class="text-gray-500">-</span>
                <input type="number" id="maxPrice" placeholder="{{ __('web.common.to') }}"
                       class="border border-gray-300 rounded-lg px-3 py-2 w-20 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       value="{{ request('max_price') }}">
                <button id="applyPriceFilter" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    {{ __('web.product.apply_filter') }}
                </button>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <!-- Sort Options -->
            <select id="sortBy" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('web.product.sort_newest') }}</option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>{{ __('web.product.sort_price_low') }}</option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ __('web.product.sort_price_high') }}</option>
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>{{ __('web.product.sort_popular') }}</option>
            </select>

            <!-- View Toggle -->
            <div class="flex border border-gray-300 rounded-lg overflow-hidden">
                <button id="gridView" class="px-3 py-2 bg-blue-600 text-white hover:bg-blue-700">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>
                <button id="listView" class="px-3 py-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 8a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 12a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div id="productsContainer">
        @if($products->count() > 0)
            <div id="gridContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Product Image -->
                        <div class="relative group">
                            @if($product->images->count() > 0)
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                     alt="{{ $product->getName() }}"
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
                                @if($product->is_sale)
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded">{{ __('web.product.sale_badge') }}</span>
                                @endif
                                @if($product->is_new)
                                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded">{{ __('web.product.new_badge') }}</span>
                                @endif
                            </div>

                            <!-- Quick Actions -->
                            <div class="absolute top-2 right-2 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="bg-white rounded-full p-2 shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <button class="bg-white rounded-full p-2 shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">
                                <a href="{{ route('web.products.show', $product->id) }}" class="hover:text-blue-600">
                                    {{ $product->getName() }}
                                </a>
                            </h3>

                            <p class="text-sm text-gray-600 mb-2 line-clamp-2">{{ $product->getDescription() }}</p>

                            <!-- Rating -->
                            <div class="flex items-center mb-2">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-500 ml-1">(24)</span>
                            </div>

                            <!-- Price -->
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <span class="text-lg font-bold text-red-600">${{ number_format($product->sale_price, 2) }}</span>
                                        <span class="text-sm text-gray-500 line-through">${{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="text-lg font-bold text-gray-800">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Add to Cart Button -->
                            <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-300">
                                {{ __('web.home.add_to_cart') }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- List View Container (hidden by default) -->
            <div id="listContainer" class="hidden space-y-4">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="flex">
                            <!-- Product Image -->
                            <div class="w-48 h-32 flex-shrink-0">
                                @if($product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                         alt="{{ $product->getName() }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="flex-1 p-4">
                                <div class="flex justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-lg text-gray-800 mb-2">
                                            <a href="{{ route('web.products.show', $product->id) }}" class="hover:text-blue-600">
                                                {{ $product->getName() }}
                                            </a>
                                        </h3>

                                        <p class="text-gray-600 mb-2">{{ $product->getDescription() }}</p>

                                        <!-- Rating -->
                                        <div class="flex items-center mb-2">
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="text-sm text-gray-500 ml-1">(24)</span>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-end justify-between ml-4">
                                        <!-- Price -->
                                        <div class="text-right mb-4">
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <div class="text-xl font-bold text-red-600">${{ number_format($product->sale_price, 2) }}</div>
                                                <div class="text-sm text-gray-500 line-through">${{ number_format($product->price, 2) }}</div>
                                            @else
                                                <div class="text-xl font-bold text-gray-800">${{ number_format($product->price, 2) }}</div>
                                            @endif
                                        </div>

                                        <!-- Add to Cart Button -->
                                        <button class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition-colors duration-300">
                                            {{ __('web.home.add_to_cart') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2L3 7v11a2 2 0 002 2h10a2 2 0 002-2V7l-7-5zM10 18l-7-5V7l7-5 7 5v6l-7 5z" clip-rule="evenodd"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">{{ __('web.product.no_products_found') }}</h3>
                <p class="text-gray-500 mb-4">{{ __('web.product.try_different_filters') }}</p>
                <a href="{{ route('web.products.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    {{ __('web.product.reset_filters') }}
                </a>
            </div>
        @endif
    </div>
</div>

<script>
    // View toggle functionality
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const gridContainer = document.getElementById('gridContainer');
    const listContainer = document.getElementById('listContainer');

    gridView.addEventListener('click', function() {
        gridContainer.classList.remove('hidden');
        listContainer.classList.add('hidden');
        gridView.classList.remove('bg-gray-100', 'text-gray-600');
        gridView.classList.add('bg-blue-600', 'text-white');
        listView.classList.remove('bg-blue-600', 'text-white');
        listView.classList.add('bg-gray-100', 'text-gray-600');
    });

    listView.addEventListener('click', function() {
        listContainer.classList.remove('hidden');
        gridContainer.classList.add('hidden');
        listView.classList.remove('bg-gray-100', 'text-gray-600');
        listView.classList.add('bg-blue-600', 'text-white');
        gridView.classList.remove('bg-blue-600', 'text-white');
        gridView.classList.add('bg-gray-100', 'text-gray-600');
    });

    // Filter functionality
    const categoryFilter = document.getElementById('categoryFilter');
    const sortBy = document.getElementById('sortBy');
    const minPrice = document.getElementById('minPrice');
    const maxPrice = document.getElementById('maxPrice');
    const applyPriceFilter = document.getElementById('applyPriceFilter');

    function applyFilters() {
        const params = new URLSearchParams(window.location.search);

        if (categoryFilter.value) {
            params.set('category', categoryFilter.value);
        } else {
            params.delete('category');
        }

        if (sortBy.value) {
            params.set('sort', sortBy.value);
        } else {
            params.delete('sort');
        }

        window.location.search = params.toString();
    }

    function applyPriceFilters() {
        const params = new URLSearchParams(window.location.search);

        if (minPrice.value) {
            params.set('min_price', minPrice.value);
        } else {
            params.delete('min_price');
        }

        if (maxPrice.value) {
            params.set('max_price', maxPrice.value);
        } else {
            params.delete('max_price');
        }

        window.location.search = params.toString();
    }

    categoryFilter.addEventListener('change', applyFilters);
    sortBy.addEventListener('change', applyFilters);
    applyPriceFilter.addEventListener('click', applyPriceFilters);
</script>
@endsection
