@extends('mobile.layouts.app')

@section('title', __('Bosh sahifa'))

@section('content')
<div class="homepage">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    {{ __('DomProduct - Onlayn Do\'kon') }}
                </h1>
                <p class="hero-subtitle">
                    {{ __('Eng sifatli mahsulotlarni eng qulay narxlarda topishingiz mumkin') }}
                </p>
                <div class="hero-actions">
                    <a href="{{ route('products') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i>
                        {{ __('Xarid qilish') }}
                    </a>
                    <a href="{{ route('categories') }}" class="btn btn-outline">
                        <i class="fas fa-th-large"></i>
                        {{ __('Kategoriyalar') }}
                    </a>
                </div>
            </div>
            
            <!-- Hero Image/Slider -->
            <div class="hero-slider">
                <div class="slide active">
                    <img src="{{ asset('images/hero-1.jpg') }}" alt="Hero 1" loading="lazy">
                </div>
                <div class="slide">
                    <img src="{{ asset('images/hero-2.jpg') }}" alt="Hero 2" loading="lazy">
                </div>
                <div class="slide">
                    <img src="{{ asset('images/hero-3.jpg') }}" alt="Hero 3" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section">
        <div class="container">
            <div class="section-header">
                <h2>{{ __('Kategoriyalar') }}</h2>
                <a href="{{ route('categories') }}" class="view-all-link">
                    {{ __('Barchasini ko\'rish') }}
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="categories-grid">
                @foreach($featuredCategories as $category)
                <a href="{{ route('products', ['category' => $category->slug]) }}" class="category-card">
                    <div class="category-icon">
                        <i class="fas {{ $category->icon }}"></i>
                    </div>
                    <h3 class="category-name">
                        {{ app()->getLocale() == 'uz' ? $category->name_uz : $category->name }}
                    </h3>
                    <p class="category-count">{{ $category->products_count }} {{ __('mahsulot') }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products">
        <div class="container">
            <div class="section-header">
                <h2>{{ __('Mashhur mahsulotlar') }}</h2>
                <a href="{{ route('products') }}" class="view-all-link">
                    {{ __('Barchasini ko\'rish') }}
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="products-grid">
                @foreach($featuredProducts as $product)
                    @include('mobile.components.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>

    <!-- New Products Section -->
    <section class="new-products">
        <div class="container">
            <div class="section-header">
                <h2>{{ __('Yangi mahsulotlar') }}</h2>
                <a href="{{ route('products', ['sort' => 'newest']) }}" class="view-all-link">
                    {{ __('Barchasini ko\'rish') }}
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="products-grid">
                @foreach($newProducts as $product)
                    @include('mobile.components.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ $totalProducts }}</h3>
                        <p class="stat-label">{{ __('Mahsulotlar') }}</p>
                    </div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ $totalUsers }}</h3>
                        <p class="stat-label">{{ __('Foydalanuvchilar') }}</p>
                    </div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ $totalOrders }}</h3>
                        <p class="stat-label">{{ __('Buyurtmalar') }}</p>
                    </div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number">4.8</h3>
                        <p class="stat-label">{{ __('Reyting') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title">{{ __('Bizning afzalliklarimiz') }}</h2>
            
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>{{ __('Tez yetkazib berish') }}</h3>
                    <p>{{ __('Buyurtmangizni 1-2 kun ichida yetkazib beramiz') }}</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>{{ __('Kafolat') }}</h3>
                    <p>{{ __('Barcha mahsulotlarga 1 yilgacha kafolat beramiz') }}</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>{{ __('24/7 qo\'llab-quvvatlash') }}</h3>
                    <p>{{ __('Har qanday savolingizga javob berishga tayyormiz') }}</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3>{{ __('Xavfsiz to\'lov') }}</h3>
                    <p>{{ __('Barcha to\'lov usullari xavfsiz va ishonchli') }}</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero slider functionality
    const slides = document.querySelectorAll('.hero-slider .slide');
    let currentSlide = 0;
    
    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }
    
    // Auto-advance slides every 5 seconds
    if (slides.length > 1) {
        setInterval(nextSlide, 5000);
    }
    
    // Add to cart functionality for featured products
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            addToCart(productId);
        });
    });
    
    // Add to wishlist functionality
    document.querySelectorAll('.add-to-wishlist-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            toggleWishlist(productId);
        });
    });
    
    // Stats counter animation
    const statsNumbers = document.querySelectorAll('.stat-number');
    const animateStats = () => {
        statsNumbers.forEach(stat => {
            const target = parseInt(stat.textContent.replace(/,/g, ''));
            const duration = 2000;
            const start = Date.now();
            
            const animate = () => {
                const now = Date.now();
                const progress = Math.min((now - start) / duration, 1);
                const current = Math.floor(progress * target);
                
                if (current === target && target > 1000) {
                    stat.textContent = current.toLocaleString();
                } else {
                    stat.textContent = current;
                }
                
                if (progress < 1) {
                    requestAnimationFrame(animate);
                }
            };
            
            animate();
        });
    };
    
    // Intersection Observer for stats animation
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateStats();
                observer.unobserve(entry.target);
            }
        });
    });
    
    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
        observer.observe(statsSection);
    }
});
</script>
@endpush
