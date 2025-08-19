<!-- Mobile Navigation Component -->
<nav class="mobile-nav">
    <!-- Top Navigation Bar -->
    <div class="nav-top">
        <div class="container">
            <div class="nav-brand">
                <a href="{{ route('home') }}" class="brand-logo">
                    <span class="brand-text">DomProduct</span>
                </a>
            </div>
            
            <div class="nav-actions">
                <!-- Search Toggle -->
                <button class="nav-btn search-toggle" onclick="toggleSearch()" aria-label="Qidiruv">
                    <i class="fas fa-search"></i>
                </button>
                
                <!-- Cart -->
                <a href="{{ route('cart') }}" class="nav-btn cart-btn">
                    <i class="fas fa-shopping-cart"></i>
                    @if(session('cart') && count(session('cart')) > 0)
                    <span class="cart-count">{{ array_sum(array_column(session('cart'), 'quantity')) }}</span>
                    @endif
                </a>
                
                <!-- Menu Toggle -->
                <button class="nav-btn menu-toggle" onclick="toggleMenu()" aria-label="Menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        
        <!-- Search Bar (Hidden by default) -->
        <div class="search-bar" id="searchBar">
            <div class="container">
                <form action="{{ route('search') }}" method="GET" class="search-form">
                    <div class="search-input-group">
                        <input type="text" name="q" placeholder="{{ __('Mahsulot qidiring...') }}" 
                               value="{{ request('q') }}" class="search-input">
                        <button type="submit" class="search-submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="toggleMenu()"></div>
    
    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="menu-header">
            <h3>{{ __('Menu') }}</h3>
            <button class="menu-close" onclick="toggleMenu()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="menu-content">
            <!-- User Section -->
            @auth
            <div class="menu-user">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="user-info">
                    <h4>{{ auth()->user()->name }}</h4>
                    <p>{{ auth()->user()->email }}</p>
                </div>
            </div>
            @else
            <div class="menu-auth">
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    {{ __('Kirish') }}
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline">
                    <i class="fas fa-user-plus"></i>
                    {{ __('Ro\'yxatdan o\'tish') }}
                </a>
            </div>
            @endauth
            
            <!-- Menu Items -->
            <div class="menu-items">
                <a href="{{ route('home') }}" class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>{{ __('Bosh sahifa') }}</span>
                </a>
                
                <a href="{{ route('products') }}" class="menu-item {{ request()->routeIs('products*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>{{ __('Mahsulotlar') }}</span>
                </a>
                
                <a href="{{ route('categories') }}" class="menu-item {{ request()->routeIs('categories*') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>{{ __('Kategoriyalar') }}</span>
                </a>
                
                <a href="{{ route('cart') }}" class="menu-item {{ request()->routeIs('cart') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>{{ __('Savat') }}</span>
                    @if(session('cart') && count(session('cart')) > 0)
                    <span class="menu-badge">{{ array_sum(array_column(session('cart'), 'quantity')) }}</span>
                    @endif
                </a>
                
                <a href="{{ route('wishlist') }}" class="menu-item {{ request()->routeIs('wishlist') ? 'active' : '' }}">
                    <i class="fas fa-heart"></i>
                    <span>{{ __('Sevimlilar') }}</span>
                    @if(session('wishlist') && count(session('wishlist')) > 0)
                    <span class="menu-badge">{{ count(session('wishlist')) }}</span>
                    @endif
                </a>
                
                @auth
                <a href="{{ route('profile') }}" class="menu-item {{ request()->routeIs('profile*') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>{{ __('Profil') }}</span>
                </a>
                
                <a href="{{ route('orders') }}" class="menu-item {{ request()->routeIs('orders*') ? 'active' : '' }}">
                    <i class="fas fa-list-alt"></i>
                    <span>{{ __('Buyurtmalar') }}</span>
                </a>
                @endauth
            </div>
            
            <!-- Language Switcher -->
            <div class="menu-section">
                <h4>{{ __('Til') }}</h4>
                <div class="language-switcher">
                    <a href="{{ route('language.switch', 'uz') }}" 
                       class="lang-btn {{ app()->getLocale() == 'uz' ? 'active' : '' }}">
                        <i class="fas fa-globe"></i>
                        O'zbek
                    </a>
                    <a href="{{ route('language.switch', 'ru') }}" 
                       class="lang-btn {{ app()->getLocale() == 'ru' ? 'active' : '' }}">
                        <i class="fas fa-globe"></i>
                        Русский
                    </a>
                </div>
            </div>
            
            @auth
            <!-- Logout -->
            <div class="menu-section">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="menu-item logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>{{ __('Chiqish') }}</span>
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </div>
</nav>

<!-- Bottom Navigation (Optional) -->
<nav class="bottom-nav">
    <a href="{{ route('home') }}" class="bottom-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
        <span>{{ __('Bosh') }}</span>
    </a>
    
    <a href="{{ route('categories') }}" class="bottom-nav-item {{ request()->routeIs('categories*') ? 'active' : '' }}">
        <i class="fas fa-th-large"></i>
        <span>{{ __('Kategoriya') }}</span>
    </a>
    
    <a href="{{ route('search') }}" class="bottom-nav-item {{ request()->routeIs('search') ? 'active' : '' }}">
        <i class="fas fa-search"></i>
        <span>{{ __('Qidiruv') }}</span>
    </a>
    
    <a href="{{ route('wishlist') }}" class="bottom-nav-item {{ request()->routeIs('wishlist') ? 'active' : '' }}">
        <i class="fas fa-heart"></i>
        <span>{{ __('Sevimli') }}</span>
        @if(session('wishlist') && count(session('wishlist')) > 0)
        <span class="nav-badge">{{ count(session('wishlist')) }}</span>
        @endif
    </a>
    
    <a href="{{ route('profile') }}" class="bottom-nav-item {{ request()->routeIs('profile*') ? 'active' : '' }}">
        <i class="fas fa-user"></i>
        <span>{{ __('Profil') }}</span>
    </a>
</nav>

<script>
function toggleSearch() {
    const searchBar = document.getElementById('searchBar');
    searchBar.classList.toggle('active');
    
    if (searchBar.classList.contains('active')) {
        searchBar.querySelector('input').focus();
    }
}

function toggleMenu() {
    const overlay = document.getElementById('mobileMenuOverlay');
    const menu = document.getElementById('mobileMenu');
    
    overlay.classList.toggle('active');
    menu.classList.toggle('active');
    
    // Prevent body scroll when menu is open
    if (menu.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}
</script>
