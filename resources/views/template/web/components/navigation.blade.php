<!-- Web Navigation Component -->
<nav class="navigation" id="navigation">
    <div class="container">
        <div class="nav-content">
            <!-- Categories Menu -->
            <div class="nav-categories">
                <button class="categories-trigger" onclick="toggleCategoriesMenu()" id="categoriesBtn">
                    <i class="fas fa-bars"></i>
                    <span>{{ __('Kategoriyalar') }}</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                
                <div class="categories-mega-menu" id="categoriesMenu">
                    <div class="mega-menu-content">
                        @foreach($categories ?? [] as $category)
                        <div class="category-column">
                            <div class="category-header">
                                <a href="{{ route('category', $category->slug) }}" class="category-title">
                                    @if($category->icon)
                                    <img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->name }}" class="category-icon">
                                    @endif
                                    <span>{{ $category->name }}</span>
                                </a>
                            </div>
                            
                            @if($category->children->count() > 0)
                            <div class="category-subcategories">
                                @foreach($category->children as $subcategory)
                                <a href="{{ route('category', $subcategory->slug) }}" class="subcategory-link">
                                    {{ $subcategory->name }}
                                </a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Featured Banner in Menu -->
                    <div class="menu-banner">
                        <div class="banner-content">
                            <h3>{{ __('Eng sara takliflar') }}</h3>
                            <p>{{ __('50% gacha chegirma') }}</p>
                            <a href="{{ route('promotions') }}" class="banner-btn">{{ __('Ko\'rish') }}</a>
                        </div>
                        <div class="banner-image">
                            <img src="{{ asset('images/menu-banner.jpg') }}" alt="{{ __('Takliflar') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Navigation Menu -->
            <div class="nav-menu">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                            <span>{{ __('Bosh sahifa') }}</span>
                        </a>
                    </li>
                    
                    <li class="nav-item has-dropdown">
                        <a href="{{ route('products') }}" class="nav-link">
                            <span>{{ __('Mahsulotlar') }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="nav-dropdown">
                            <a href="{{ route('products.new') }}" class="dropdown-item">
                                <i class="fas fa-star"></i>
                                <span>{{ __('Yangi mahsulotlar') }}</span>
                            </a>
                            <a href="{{ route('products.bestsellers') }}" class="dropdown-item">
                                <i class="fas fa-fire"></i>
                                <span>{{ __('Ommabop') }}</span>
                            </a>
                            <a href="{{ route('products.discounts') }}" class="dropdown-item">
                                <i class="fas fa-percent"></i>
                                <span>{{ __('Chegirmalar') }}</span>
                            </a>
                            <a href="{{ route('products.brands') }}" class="dropdown-item">
                                <i class="fas fa-tags"></i>
                                <span>{{ __('Brendlar') }}</span>
                            </a>
                        </div>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('promotions') }}" class="nav-link">
                            <span>{{ __('Aksiyalar') }}</span>
                            <span class="nav-badge">{{ __('Yangi') }}</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('brands') }}" class="nav-link">
                            <span>{{ __('Brendlar') }}</span>
                        </a>
                    </li>
                    
                    <li class="nav-item has-dropdown">
                        <a href="#" class="nav-link">
                            <span>{{ __('Xizmatlar') }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="nav-dropdown">
                            <a href="{{ route('services.delivery') }}" class="dropdown-item">
                                <i class="fas fa-truck"></i>
                                <span>{{ __('Yetkazib berish') }}</span>
                            </a>
                            <a href="{{ route('services.installation') }}" class="dropdown-item">
                                <i class="fas fa-tools"></i>
                                <span>{{ __('O\'rnatish') }}</span>
                            </a>
                            <a href="{{ route('services.warranty') }}" class="dropdown-item">
                                <i class="fas fa-shield-alt"></i>
                                <span>{{ __('Kafolat') }}</span>
                            </a>
                            <a href="{{ route('services.support') }}" class="dropdown-item">
                                <i class="fas fa-headset"></i>
                                <span>{{ __('Qo\'llab-quvvatlash') }}</span>
                            </a>
                        </div>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('about') }}" class="nav-link">
                            <span>{{ __('Biz haqimizda') }}</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('contact') }}" class="nav-link">
                            <span>{{ __('Aloqa') }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Navigation Actions -->
            <div class="nav-actions">
                <!-- Track Order -->
                <div class="nav-action">
                    <button class="action-btn" onclick="openTrackOrderModal()" data-tooltip="{{ __('Buyurtmani kuzatish') }}">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ __('Buyurtma holati') }}</span>
                    </button>
                </div>
                
                <!-- Support -->
                <div class="nav-action">
                    <a href="{{ route('support') }}" class="action-btn" data-tooltip="{{ __('Yordam') }}">
                        <i class="fas fa-question-circle"></i>
                        <span>{{ __('Yordam') }}</span>
                    </a>
                </div>
                
                <!-- Mobile Menu Toggle -->
                <div class="nav-action mobile-only">
                    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Navigation Overlay -->
    <div class="mobile-nav-overlay" id="mobileNavOverlay">
        <div class="mobile-nav-content">
            <div class="mobile-nav-header">
                <div class="mobile-nav-logo">
                    <img src="{{ asset('images/logo.svg') }}" alt="DomProduct">
                </div>
                <button class="mobile-nav-close" onclick="closeMobileMenu()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mobile-nav-body">
                <div class="mobile-nav-search">
                    <div class="search-wrapper">
                        <input type="search" placeholder="{{ __('Qidirish...') }}" class="mobile-search-input">
                        <button class="mobile-search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                
                <div class="mobile-nav-menu">
                    <ul class="mobile-nav-list">
                        <li class="mobile-nav-item">
                            <a href="{{ route('home') }}" class="mobile-nav-link">
                                <i class="fas fa-home"></i>
                                <span>{{ __('Bosh sahifa') }}</span>
                            </a>
                        </li>
                        
                        <li class="mobile-nav-item has-submenu">
                            <a href="#" class="mobile-nav-link" onclick="toggleMobileSubmenu(this)">
                                <i class="fas fa-th-large"></i>
                                <span>{{ __('Kategoriyalar') }}</span>
                                <i class="fas fa-chevron-right submenu-arrow"></i>
                            </a>
                            <ul class="mobile-submenu">
                                @foreach($categories ?? [] as $category)
                                <li>
                                    <a href="{{ route('category', $category->slug) }}">{{ $category->name }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        
                        <li class="mobile-nav-item">
                            <a href="{{ route('promotions') }}" class="mobile-nav-link">
                                <i class="fas fa-percent"></i>
                                <span>{{ __('Aksiyalar') }}</span>
                                <span class="mobile-nav-badge">{{ __('Yangi') }}</span>
                            </a>
                        </li>
                        
                        <li class="mobile-nav-item">
                            <a href="{{ route('brands') }}" class="mobile-nav-link">
                                <i class="fas fa-tags"></i>
                                <span>{{ __('Brendlar') }}</span>
                            </a>
                        </li>
                        
                        <li class="mobile-nav-item has-submenu">
                            <a href="#" class="mobile-nav-link" onclick="toggleMobileSubmenu(this)">
                                <i class="fas fa-concierge-bell"></i>
                                <span>{{ __('Xizmatlar') }}</span>
                                <i class="fas fa-chevron-right submenu-arrow"></i>
                            </a>
                            <ul class="mobile-submenu">
                                <li><a href="{{ route('services.delivery') }}">{{ __('Yetkazib berish') }}</a></li>
                                <li><a href="{{ route('services.installation') }}">{{ __('O\'rnatish') }}</a></li>
                                <li><a href="{{ route('services.warranty') }}">{{ __('Kafolat') }}</a></li>
                                <li><a href="{{ route('services.support') }}">{{ __('Qo\'llab-quvvatlash') }}</a></li>
                            </ul>
                        </li>
                        
                        <li class="mobile-nav-item">
                            <a href="{{ route('about') }}" class="mobile-nav-link">
                                <i class="fas fa-info-circle"></i>
                                <span>{{ __('Biz haqimizda') }}</span>
                            </a>
                        </li>
                        
                        <li class="mobile-nav-item">
                            <a href="{{ route('contact') }}" class="mobile-nav-link">
                                <i class="fas fa-phone"></i>
                                <span>{{ __('Aloqa') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="mobile-nav-footer">
                    <div class="mobile-nav-info">
                        <div class="info-item">
                            <i class="fas fa-phone"></i>
                            <span>+998 (90) 123-45-67</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <span>info@domproduct.uz</span>
                        </div>
                    </div>
                    
                    <div class="mobile-social-links">
                        <a href="#" class="social-link">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-telegram-plane"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Breadcrumb Component -->
@if(!request()->routeIs('home'))
<div class="breadcrumb-section">
    <div class="container">
        <nav class="breadcrumb">
            <a href="{{ route('home') }}" class="breadcrumb-item">
                <i class="fas fa-home"></i>
                <span>{{ __('Bosh sahifa') }}</span>
            </a>
            @yield('breadcrumb')
        </nav>
    </div>
</div>
@endif

<script>
// Navigation functionality
let categoriesMenuOpen = false;
let mobileMenuOpen = false;

function toggleCategoriesMenu() {
    const menu = document.getElementById('categoriesMenu');
    const btn = document.getElementById('categoriesBtn');
    
    categoriesMenuOpen = !categoriesMenuOpen;
    
    if (categoriesMenuOpen) {
        menu.classList.add('active');
        btn.classList.add('active');
    } else {
        menu.classList.remove('active');
        btn.classList.remove('active');
    }
}

function toggleMobileMenu() {
    const overlay = document.getElementById('mobileNavOverlay');
    mobileMenuOpen = !mobileMenuOpen;
    
    if (mobileMenuOpen) {
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    } else {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }
}

function closeMobileMenu() {
    toggleMobileMenu();
}

function toggleMobileSubmenu(trigger) {
    const submenu = trigger.nextElementSibling;
    const arrow = trigger.querySelector('.submenu-arrow');
    
    if (submenu.classList.contains('active')) {
        submenu.classList.remove('active');
        arrow.style.transform = 'rotate(0deg)';
    } else {
        // Close other open submenus
        document.querySelectorAll('.mobile-submenu.active').forEach(menu => {
            menu.classList.remove('active');
            menu.previousElementSibling.querySelector('.submenu-arrow').style.transform = 'rotate(0deg)';
        });
        
        submenu.classList.add('active');
        arrow.style.transform = 'rotate(90deg)';
    }
}

function openTrackOrderModal() {
    // Implementation for order tracking modal
    showToast('{{ __("Buyurtma kuzatish funksiyasi ishlab chiqilmoqda") }}', 'info');
}

// Close menus when clicking outside
document.addEventListener('click', function(e) {
    // Close categories menu
    if (!e.target.closest('.nav-categories') && categoriesMenuOpen) {
        toggleCategoriesMenu();
    }
    
    // Close navigation dropdowns
    document.querySelectorAll('.nav-dropdown').forEach(dropdown => {
        if (!e.target.closest('.has-dropdown')) {
            dropdown.style.display = 'none';
        }
    });
});

// Handle navigation dropdown hover
document.querySelectorAll('.has-dropdown').forEach(item => {
    const dropdown = item.querySelector('.nav-dropdown');
    
    item.addEventListener('mouseenter', () => {
        dropdown.style.display = 'block';
    });
    
    item.addEventListener('mouseleave', () => {
        dropdown.style.display = 'none';
    });
});

// Sticky navigation
let lastScrollTop = 0;
const navigation = document.getElementById('navigation');
const header = document.getElementById('header');

window.addEventListener('scroll', function() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    if (scrollTop > header.offsetHeight) {
        navigation.classList.add('sticky');
        
        // Hide/show navigation based on scroll direction
        if (scrollTop > lastScrollTop && scrollTop > 200) {
            navigation.classList.add('nav-hidden');
        } else {
            navigation.classList.remove('nav-hidden');
        }
    } else {
        navigation.classList.remove('sticky');
        navigation.classList.remove('nav-hidden');
    }
    
    lastScrollTop = scrollTop;
});

// Mobile search functionality
document.querySelector('.mobile-search-btn').addEventListener('click', function() {
    const query = document.querySelector('.mobile-search-input').value.trim();
    if (query) {
        window.location.href = '{{ route("search") }}?q=' + encodeURIComponent(query);
    }
});

document.querySelector('.mobile-search-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        const query = this.value.trim();
        if (query) {
            window.location.href = '{{ route("search") }}?q=' + encodeURIComponent(query);
        }
    }
});

// Update active navigation item based on current route
function updateActiveNavigation() {
    const currentPath = window.location.pathname;
    
    document.querySelectorAll('.nav-link').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateActiveNavigation();
});
</script>
