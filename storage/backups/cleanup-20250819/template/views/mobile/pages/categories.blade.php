@extends('mobile.layouts.app')

@section('title', __('Kategoriyalar'))

@section('content')
<div class="categories-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1>{{ __('Kategoriyalar') }}</h1>
            <nav class="breadcrumb">
                <a href="{{ route('home') }}">{{ __('Bosh sahifa') }}</a>
                <span>/</span>
                <span>{{ __('Kategoriyalar') }}</span>
            </nav>
        </div>
    </div>

    <div class="container">
        <!-- Search Section -->
        <div class="search-section">
            <form method="GET" action="{{ route('categories') }}" class="search-form">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ __('Kategoriya nomi bo\'yicha qidiring...') }}" class="search-input">
                    @if(request('search'))
                    <a href="{{ route('categories') }}" class="clear-search">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Statistics -->
        <div class="stats-section">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $totalCategories }}</div>
                        <div class="stat-label">{{ __('Kategoriya') }}</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-cube"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($totalProducts) }}</div>
                        <div class="stat-label">{{ __('Mahsulot') }}</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $totalSubcategories }}</div>
                        <div class="stat-label">{{ __('Subkategoriya') }}</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $categories->count() }}</div>
                        <div class="stat-label">{{ __('Topildi') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Grid -->
        <div class="categories-container">
            @if($categories->count() > 0)
                <div class="categories-grid">
                    @foreach($categories as $category)
                    <div class="category-item" data-category-id="{{ $category->id }}">
                        <div class="category-header">
                            <div class="category-icon">
                                <i class="fas {{ $category->icon }}"></i>
                            </div>
                            <div class="category-count">
                                {{ $category->products_count }}
                            </div>
                        </div>
                        
                        <div class="category-content">
                            <h3 class="category-name">
                                {{ app()->getLocale() == 'uz' ? $category->name_uz : $category->name }}
                            </h3>
                            
                            <!-- Subcategories -->
                            @if($category->subcategories && $category->subcategories->count() > 0)
                            <div class="subcategories-wrapper">
                                <div class="subcategories-toggle" onclick="toggleSubcategories({{ $category->id }})">
                                    <span>{{ __('Subkategoriyalar') }}</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                
                                <div class="subcategories-list" id="subcategories-{{ $category->id }}">
                                    @foreach($category->subcategories as $subcategory)
                                    <a href="{{ route('products', ['category' => $category->slug, 'subcategory' => $subcategory->slug]) }}" 
                                       class="subcategory-link">
                                        <span class="subcategory-name">
                                            {{ app()->getLocale() == 'uz' ? $subcategory->name_uz : $subcategory->name }}
                                        </span>
                                        <span class="subcategory-count">
                                            ({{ $subcategory->products_count }})
                                        </span>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="category-footer">
                            <a href="{{ route('products', ['category' => $category->slug]) }}" class="view-all-btn">
                                <i class="fas fa-arrow-right"></i>
                                {{ __('Barchasini ko\'rish') }}
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($categories->hasPages())
                <div class="pagination-wrapper">
                    {{ $categories->appends(request()->query())->links() }}
                </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>{{ __('Kategoriya topilmadi') }}</h3>
                    <p>{{ __('Qidiruv so\'rovingizni o\'zgartirib, qayta urinib ko\'ring') }}</p>
                    <a href="{{ route('categories') }}" class="btn btn-primary">
                        {{ __('Barcha kategoriyalar') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('assets/css/categories.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit search form
    const searchInput = document.querySelector('.search-input');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            this.form.submit();
        }, 500);
    });
    
    // Category hover effects
    document.querySelectorAll('.category-item').forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
});

function toggleSubcategories(categoryId) {
    const subcategoriesList = document.getElementById(`subcategories-${categoryId}`);
    const toggle = subcategoriesList.previousElementSibling;
    const icon = toggle.querySelector('.fas');
    
    subcategoriesList.classList.toggle('active');
    toggle.classList.toggle('active');
    
    if (subcategoriesList.classList.contains('active')) {
        icon.style.transform = 'rotate(180deg)';
    } else {
        icon.style.transform = '';
    }
    
    // Show toast notification
    showToast('success', '{{ __("Subkategoriyalar ko\'rsatildi") }}');
}
</script>
@endpush
