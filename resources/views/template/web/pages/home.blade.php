@extends('template.web.layouts.app')

@section('title', __('Bosh sahifa'))

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-slider" id="heroSlider">
        <div class="hero-slide active" style="background-image: url('{{ asset('images/hero/hero-1.jpg') }}')">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title">{{ __('Zamonaviy texnologiyalar') }}</h1>
                        <p class="hero-subtitle">{{ __('Eng yangi va sifatli mahsulotlar 50% gacha chegirma bilan') }}</p>
                        <div class="hero-actions">
                            <a href="{{ route('products') }}" class="btn btn-primary btn-lg">
                                {{ __('Xarid qilish') }}
                                <i class="fas fa-arrow-right"></i>
                            </a>
                            <a href="{{ route('promotions') }}" class="btn btn-outline btn-lg">
                                {{ __('Aksiyalar') }}
                            </a>
                        </div>
                    </div>
                    <div class="hero-image">
                        <img src="{{ asset('images/hero/product-hero-1.png') }}" alt="{{ __('Mahsulot') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-slide" style="background-image: url('{{ asset('images/hero/hero-2.jpg') }}')">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title">{{ __('Muddatli to\'lovga xarid') }}</h1>
                        <p class="hero-subtitle">{{ __('0% foizsiz, hech qanday qo\'shimcha to\'lovsiz') }}</p>
                        <div class="hero-actions">
                            <a href="{{ route('installment') }}" class="btn btn-primary btn-lg">
                                {{ __('Batafsil') }}
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="hero-image">
                        <img src="{{ asset('images/hero/product-hero-2.png') }}" alt="{{ __('Muddatli to\'lov') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-slide" style="background-image: url('{{ asset('images/hero/hero-3.jpg') }}')">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1 class="hero-title">{{ __('Tezkor yetkazib berish') }}</h1>
                        <p class="hero-subtitle">{{ __('Toshkent bo\'ylab 2 soatda, viloyatlarga 1 kunda yetkazib beramiz') }}</p>
                        <div class="hero-actions">
                            <a href="{{ route('delivery') }}" class="btn btn-primary btn-lg">
                                {{ __('Ma\'lumot') }}
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="hero-image">
                        <img src="{{ asset('images/hero/product-hero-3.png') }}" alt="{{ __('Yetkazib berish') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Navigation -->
    <div class="hero-navigation">
        <button class="hero-prev" onclick="changeSlide(-1)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="hero-next" onclick="changeSlide(1)">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>

    <!-- Hero Indicators -->
    <div class="hero-indicators">
        <button class="indicator active" onclick="goToSlide(0)"></button>
        <button class="indicator" onclick="goToSlide(1)"></button>
        <button class="indicator" onclick="goToSlide(2)"></button>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="feature-content">
                    <h3 class="feature-title">{{ __('Tezkor yetkazib berish') }}</h3>
                    <p class="feature-text">{{ __('Toshkent bo\'ylab 2 soatda') }}</p>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="feature-content">
                    <h3 class="feature-title">{{ __('Kafolat') }}</h3>
                    <p class="feature-text">{{ __('Rasmiy kafolat va xizmat') }}</p>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="feature-content">
                    <h3 class="feature-title">{{ __('Muddatli to\'lov') }}</h3>
                    <p class="feature-text">{{ __('0% foizsiz, komissiyasiz') }}</p>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="feature-content">
                    <h3 class="feature-title">{{ __('24/7 qo\'llab-quvvatlash') }}</h3>
                    <p class="feature-text">{{ __('Har doim sizning xizmatingizda') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">{{ __('Kategoriyalar') }}</h2>
            <a href="{{ route('categories') }}" class="section-link">
                {{ __('Barchasini ko\'rish') }}
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="categories-grid">
            @foreach($categories as $category)
            <div class="category-card">
                <a href="{{ route('category', $category->slug) }}" class="category-link">
                    <div class="category-image">
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                        @if($category->is_popular)
                        <div class="category-badge">{{ __('Ommabop') }}</div>
                        @endif
                    </div>
                    <div class="category-info">
                        <h3 class="category-name">{{ $category->name }}</h3>
                        <p class="category-count">{{ $category->products_count }} {{ __('mahsulot') }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Flash Sale Section -->
@if($flashSaleProducts->count() > 0)
<section class="flash-sale-section">
    <div class="container">
        <div class="section-header">
            <div class="section-title-group">
                <h2 class="section-title">
                    <i class="fas fa-bolt text-warning"></i>
                    {{ __('Flash Sale') }}
                </h2>
                <div class="sale-timer" id="flashSaleTimer">
                    <span class="timer-label">{{ __('Tugagunga qadar:') }}</span>
                    <div class="timer-display">
                        <div class="timer-item">
                            <span class="timer-value" id="hours">00</span>
                            <span class="timer-label">{{ __('soat') }}</span>
                        </div>
                        <div class="timer-item">
                            <span class="timer-value" id="minutes">00</span>
                            <span class="timer-label">{{ __('daqiqa') }}</span>
                        </div>
                        <div class="timer-item">
                            <span class="timer-value" id="seconds">00</span>
                            <span class="timer-label">{{ __('soniya') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('flash-sale') }}" class="section-link">
                {{ __('Barchasini ko\'rish') }}
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="products-slider" id="flashSaleSlider">
            <div class="products-track">
                @foreach($flashSaleProducts as $product)
                    @include('template.web.components.product-card-simple', ['product' => $product, 'style' => 'sale'])
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- New Products Section -->
<section class="products-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">{{ __('Yangi mahsulotlar') }}</h2>
            <div class="section-tabs">
                <button class="tab-btn active" data-category="all">{{ __('Barchasi') }}</button>
                @foreach($featuredCategories as $category)
                <button class="tab-btn" data-category="{{ $category->slug }}">{{ $category->name }}</button>
                @endforeach
            </div>
            <a href="{{ route('products.new') }}" class="section-link">
                {{ __('Barchasini ko\'rish') }}
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="products-grid" id="newProductsGrid">
            @foreach($newProducts as $product)
                @include('template.web.components.product-card-simple', ['product' => $product])
            @endforeach
        </div>

        <div class="section-actions">
            <button class="btn btn-outline btn-lg" onclick="loadMoreProducts('new')">
                {{ __('Yana ko\'rsatish') }}
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
</section>

<!-- Banner Section -->
<section class="banner-section">
    <div class="container">
        <div class="banners-grid">
            <div class="banner-card large">
                <div class="banner-content">
                    <h3 class="banner-title">{{ __('Katta chegirmalar') }}</h3>
                    <p class="banner-text">{{ __('Telefon va noutbuklar uchun 70% gacha chegirma') }}</p>
                    <a href="{{ route('category', 'electronics') }}" class="banner-btn">
                        {{ __('Xarid qilish') }}
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="banner-image">
                    <img src="{{ asset('images/banners/electronics-banner.jpg') }}" alt="{{ __('Elektronika') }}">
                </div>
            </div>

            <div class="banner-card">
                <div class="banner-content">
                    <h3 class="banner-title">{{ __('Kiyim-kechak') }}</h3>
                    <p class="banner-text">{{ __('Yangi kolleksiya 40% chegirma') }}</p>
                    <a href="{{ route('category', 'fashion') }}" class="banner-btn">
                        {{ __('Ko\'rish') }}
                    </a>
                </div>
                <div class="banner-image">
                    <img src="{{ asset('images/banners/fashion-banner.jpg') }}" alt="{{ __('Kiyim') }}">
                </div>
            </div>

            <div class="banner-card">
                <div class="banner-content">
                    <h3 class="banner-title">{{ __('Uy-ro\'zg\'or') }}</h3>
                    <p class="banner-text">{{ __('Maishiy texnika chegirmada') }}</p>
                    <a href="{{ route('category', 'home') }}" class="banner-btn">
                        {{ __('Ko\'rish') }}
                    </a>
                </div>
                <div class="banner-image">
                    <img src="{{ asset('images/banners/home-banner.jpg') }}" alt="{{ __('Uy') }}">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Products Section -->
<section class="products-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">{{ __('Ommabop mahsulotlar') }}</h2>
            <a href="{{ route('products.popular') }}" class="section-link">
                {{ __('Barchasini ko\'rish') }}
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="products-slider" id="popularProductsSlider">
            <div class="products-track">
                @foreach($popularProducts as $product)
                    @include('template.web.components.product-card-simple', ['product' => $product])
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Brands Section -->
<section class="brands-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">{{ __('Mashhur brendlar') }}</h2>
            <a href="{{ route('brands') }}" class="section-link">
                {{ __('Barchasini ko\'rish') }}
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="brands-slider" id="brandsSlider">
            <div class="brands-track">
                @foreach($brands as $brand)
                <div class="brand-card">
                    <a href="{{ route('brand', $brand->slug) }}" class="brand-link">
                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}">
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content">
            <div class="newsletter-text">
                <h2 class="newsletter-title">{{ __('Yangiliklar va takliflardan xabardor bo\'ling') }}</h2>
                <p class="newsletter-subtitle">{{ __('Maxsus takliflar va chegirmalar haqida birinchi bo\'lib bilib oling') }}</p>
            </div>

            <form class="newsletter-form" onsubmit="subscribeNewsletter(event)">
                <div class="form-group">
                    <input type="email"
                           class="newsletter-input"
                           placeholder="{{ __('Email manzilingizni kiriting') }}"
                           required>
                    <button type="submit" class="newsletter-btn">
                        <i class="fas fa-paper-plane"></i>
                        <span>{{ __('Obuna bo\'lish') }}</span>
                    </button>
                </div>
                <p class="newsletter-note">
                    {{ __('Obuna bo\'lish orqali siz bizning') }}
                    <a href="{{ route('privacy') }}">{{ __('Maxfiylik siyosati') }}</a>{{ __('mizga rozilik bildirasiz') }}
                </p>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeHomePage();
});

function initializeHomePage() {
    initHeroSlider();
    initProductSliders();
    initProductTabs();
    initFlashSaleTimer();
}

// Hero Slider
let currentSlide = 0;
let heroInterval;

function initHeroSlider() {
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.hero-indicators .indicator');

    // Auto slide
    heroInterval = setInterval(nextSlide, 5000);

    // Pause on hover
    const heroSection = document.querySelector('.hero-section');
    heroSection.addEventListener('mouseenter', () => clearInterval(heroInterval));
    heroSection.addEventListener('mouseleave', () => {
        heroInterval = setInterval(nextSlide, 5000);
    });
}

function changeSlide(direction) {
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.hero-indicators .indicator');

    slides[currentSlide].classList.remove('active');
    indicators[currentSlide].classList.remove('active');

    currentSlide += direction;

    if (currentSlide >= slides.length) {
        currentSlide = 0;
    } else if (currentSlide < 0) {
        currentSlide = slides.length - 1;
    }

    slides[currentSlide].classList.add('active');
    indicators[currentSlide].classList.add('active');
}

function goToSlide(slideIndex) {
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.hero-indicators .indicator');

    slides[currentSlide].classList.remove('active');
    indicators[currentSlide].classList.remove('active');

    currentSlide = slideIndex;

    slides[currentSlide].classList.add('active');
    indicators[currentSlide].classList.add('active');
}

function nextSlide() {
    changeSlide(1);
}

// Product Sliders
function initProductSliders() {
    const sliders = document.querySelectorAll('.products-slider');

    sliders.forEach(slider => {
        const track = slider.querySelector('.products-track');
        const cards = track.querySelectorAll('.product-card');

        if (cards.length > 4) {
            // Add navigation buttons
            const prevBtn = document.createElement('button');
            prevBtn.className = 'slider-btn slider-prev';
            prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';

            const nextBtn = document.createElement('button');
            nextBtn.className = 'slider-btn slider-next';
            nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';

            slider.appendChild(prevBtn);
            slider.appendChild(nextBtn);

            // Add scroll functionality
            let scrollPosition = 0;
            const cardWidth = cards[0].offsetWidth + 20; // card width + gap
            const visibleCards = 4;
            const maxScroll = (cards.length - visibleCards) * cardWidth;

            nextBtn.addEventListener('click', () => {
                if (scrollPosition < maxScroll) {
                    scrollPosition += cardWidth;
                    track.style.transform = `translateX(-${scrollPosition}px)`;
                }
            });

            prevBtn.addEventListener('click', () => {
                if (scrollPosition > 0) {
                    scrollPosition -= cardWidth;
                    track.style.transform = `translateX(-${scrollPosition}px)`;
                }
            });
        }
    });

    // Brands slider
    const brandsSlider = document.getElementById('brandsSlider');
    if (brandsSlider) {
        const brandsTrack = brandsSlider.querySelector('.brands-track');
        let brandsPosition = 0;

        setInterval(() => {
            brandsPosition -= 1;
            if (brandsPosition <= -brandsTrack.scrollWidth / 2) {
                brandsPosition = 0;
            }
            brandsTrack.style.transform = `translateX(${brandsPosition}px)`;
        }, 50);
    }
}

// Product Tabs
function initProductTabs() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const productsGrid = document.getElementById('newProductsGrid');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const category = btn.dataset.category;

            // Update active tab
            tabBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Load products for category
            loadCategoryProducts(category, productsGrid);
        });
    });
}

function loadCategoryProducts(category, container) {
    showLoading('{{ __("Mahsulotlar yuklanmoqda...") }}');

    const url = category === 'all' ?
        '{{ route("api.products.new") }}' :
        `{{ route("api.products.category", "") }}/${category}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            hideLoading();

            if (data.success) {
                container.innerHTML = data.html;
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showToast('{{ __("Xatolik yuz berdi") }}', 'error');
            console.error('Error:', error);
        });
}

// Flash Sale Timer
function initFlashSaleTimer() {
    const timer = document.getElementById('flashSaleTimer');
    if (!timer) return;

    const endTime = new Date().getTime() + (24 * 60 * 60 * 1000); // 24 hours from now

    const interval = setInterval(() => {
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance < 0) {
            clearInterval(interval);
            timer.innerHTML = '<span class="timer-ended">{{ __("Aksiya tugadi") }}</span>';
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
        document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
        document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
    }, 1000);
}

// Load More Products
function loadMoreProducts(type) {
    showLoading('{{ __("Mahsulotlar yuklanmoqda...") }}');

    const container = document.getElementById(type + 'ProductsGrid');
    const currentCount = container.children.length;

    fetch(`{{ route("api.products.load-more") }}?type=${type}&offset=${currentCount}`)
        .then(response => response.json())
        .then(data => {
            hideLoading();

            if (data.success && data.products.length > 0) {
                container.insertAdjacentHTML('beforeend', data.html);

                if (data.products.length < 8) {
                    // Hide load more button if no more products
                    event.target.style.display = 'none';
                }
            } else {
                showToast(data.message || '{{ __("Boshqa mahsulotlar yo\'q") }}', 'info');
                event.target.style.display = 'none';
            }
        })
        .catch(error => {
            hideLoading();
            showToast('{{ __("Xatolik yuz berdi") }}', 'error');
            console.error('Error:', error);
        });
}

// Newsletter Subscription
function subscribeNewsletter(event) {
    event.preventDefault();

    const form = event.target;
    const email = form.querySelector('input[type="email"]').value;

    showLoading('{{ __("Obuna bo\'linmoqda...") }}');

    fetch('{{ route("newsletter.subscribe") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();

        if (data.success) {
            showToast(data.message, 'success');
            form.reset();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showToast('{{ __("Xatolik yuz berdi") }}', 'error');
        console.error('Error:', error);
    });
}

// Lazy loading for images
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}
</script>
@endpush
