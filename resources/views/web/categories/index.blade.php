@extends('web.layouts.app')

@section('title', 'Kategoriyalar - DOM PRODUCT')

@section('meta_description', 'Barcha mahsulot kategoriyalari. Oziq-ovqat, mevalar, sabzavotlar, sut mahsulotlari va boshqalar.')

@section('content')
    <!-- Header -->
    <div class="bg-gradient-to-r from-primary to-green-600 py-12">
        <div class="container mx-auto px-4">
            <div class="text-center text-white">
                <h1 class="text-3xl md:text-4xl font-bold mb-4">Kategoriyalar</h1>
                <p class="text-lg opacity-90">Kerakli mahsulotni toping</p>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <nav class="bg-gray-50 py-4">
        <div class="container mx-auto px-4">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('web.home') }}" class="text-gray-600 hover:text-primary">Bosh sahifa</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-800 font-medium">Kategoriyalar</li>
            </ol>
        </div>
    </nav>

    <!-- Categories Grid -->
    <main class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($categories as $category)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                    <!-- Category Image/Icon -->
                    <div class="bg-gradient-to-br from-primary/10 to-green-100 p-8 text-center">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}"
                                 alt="{{ $category->getName() }}"
                                 class="w-20 h-20 mx-auto rounded-full object-cover mb-4">
                        @else
                            <div class="w-20 h-20 mx-auto bg-primary/20 rounded-full flex items-center justify-center mb-4">
                                <span class="text-3xl">{{ $category->icon ?? '📦' }}</span>
                            </div>
                        @endif
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $category->getName() }}</h3>
                        <p class="text-gray-600 text-sm">{{ $category->products_count ?? 0 }} mahsulot</p>
                    </div>

                    <!-- Category Info -->
                    <div class="p-6">
                        @if($category->getDescription())
                            <p class="text-gray-600 mb-4 line-clamp-2">{{ $category->getDescription() }}</p>
                        @endif

                        <!-- Subcategories -->
                        @if($category->children && $category->children->count() > 0)
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Ichki kategoriyalar:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($category->children->take(3) as $child)
                                        <a href="{{ route('web.categories.show', $child->id) }}"
                                           class="inline-block bg-gray-100 hover:bg-primary hover:text-white text-gray-700 text-xs px-3 py-1 rounded-full transition-colors">
                                            {{ $child->getName() }}
                                        </a>
                                    @endforeach
                                    @if($category->children->count() > 3)
                                        <span class="text-xs text-gray-500 px-3 py-1">+{{ $category->children->count() - 3 }} ta</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- View Button -->
                        <a href="{{ route('web.categories.show', $category->id) }}"
                           class="block w-full bg-primary hover:bg-primary-dark text-white text-center py-3 rounded-lg font-semibold transition-colors">
                            Mahsulotlarni ko'rish
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State -->
        @if($categories->isEmpty())
            <div class="text-center py-12">
                <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Kategoriyalar topilmadi</h3>
                <p class="text-gray-600 mb-6">Hozircha kategoriyalar mavjud emas</p>
                <a href="{{ route('web.home') }}" class="btn btn-primary">
                    Bosh sahifaga qaytish
                </a>
            </div>
        @endif
    </main>
@endsection

@push('scripts')
<script>
    // Add any category-specific JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        // Category cards hover effects
        const categoryCards = document.querySelectorAll('.category-card');
        categoryCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endpush
