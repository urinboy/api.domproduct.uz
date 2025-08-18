<!-- Mobile Navigation Component -->
<nav class="mobile-nav">
    <!-- Top Navigation Bar -->
    <div class="nav-top">
        <div class="container">
            <div class="nav-brand">
                <a href="{{ route('template.mobile.home') }}" class="brand-logo">
                    <span class="brand-text">DomProduct</span>
                </a>
            </div>

            <div class="nav-actions">
                <!-- Search Toggle -->
                <button class="nav-btn search-toggle" onclick="toggleSearch()" aria-label="Qidiruv">
                    <i class="fas fa-search"></i>
                </button>

                <!-- Cart -->
                <a href="{{ route('template.mobile.cart') }}" class="nav-btn cart-btn">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">0</span>
                </a>

                <!-- Menu Toggle -->
                <button class="nav-btn menu-toggle" onclick="toggleMenu()" aria-label="Menyu">
                    <span class="menu-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
            </div>
        </div>

        <!-- Search Bar (Hidden by default) -->
        <div class="search-bar" id="searchBar">
            <div class="container">
                <form action="{{ route('template.mobile.products') }}" method="GET" class="search-form">
                    <div class="search-input-group">
                        <input type="text" name="q" placeholder="{{ __('Mahsulot qidiring...') }}"
                               value="{{ request('q') }}" class="search-input">
                        <button type="submit" class="search-submit">
                            <i class="fas fa-search"></i>
                        </button>
                        <button type="button" class="search-close" onclick="toggleSearch()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Side Menu (Hidden by default) -->
    <div class="side-menu" id="sideMenu">
        <div class="menu-backdrop" onclick="toggleMenu()"></div>
        <div class="menu-content">
            <!-- User Section -->
            <div class="menu-header">
                @guest
                <div class="guest-section">
                    <div class="guest-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="guest-info">
                        <h4>{{ __('Xush kelibsiz!') }}</h4>
                        <p>{{ __('Hisobingizga kiring yoki ro\'yxatdan o\'ting') }}</p>
                    </div>
                </div>
                <div class="auth-buttons">
                    <a href="#" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        {{ __('Kirish') }}
                    </a>
                    <a href="#" class="btn btn-outline">
                        <i class="fas fa-user-plus"></i>
                        {{ __('Ro\'yxatdan o\'tish') }}
                    </a>
                </div>
                @endguest
                @auth
                <div class="user-section">
                    <div class="user-avatar">
                        <img src="{{ asset('images/default-avatar.png') }}" alt="{{ auth()->user()->name }}">
                    </div>
                    <div class="user-info">
                        <h4>{{ auth()->user()->name }}</h4>
                        <p>{{ auth()->user()->email }}</p>
                    </div>
                </div>
                @endauth
            </div>

            <!-- Menu Items -->
            <div class="menu-items">
                <a href="{{ route('template.mobile.home') }}" class="menu-item {{ request()->routeIs('template.mobile.home') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>{{ __('Bosh sahifa') }}</span>
                </a>

                <a href="{{ route('template.mobile.products') }}" class="menu-item {{ request()->routeIs('template.mobile.products*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>{{ __('Mahsulotlar') }}</span>
                </a>

                <a href="{{ route('template.mobile.categories') }}" class="menu-item {{ request()->routeIs('template.mobile.categories*') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>{{ __('Kategoriyalar') }}</span>
                </a>

                <a href="{{ route('template.mobile.cart') }}" class="menu-item {{ request()->routeIs('template.mobile.cart') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>{{ __('Savatcha') }}</span>
                    <span class="cart-count">0</span>
                </a>

                @auth
                <hr class="menu-divider">

                <a href="{{ route('template.mobile.wishlist') }}" class="menu-item {{ request()->routeIs('template.mobile.wishlist') ? 'active' : '' }}">
                    <i class="fas fa-heart"></i>
                    <span>{{ __('Sevimlilar') }}</span>
                </a>

                <a href="#" class="menu-item">
                    <i class="fas fa-user"></i>
                    <span>{{ __('Profil') }}</span>
                </a>

                <a href="#" class="menu-item">
                    <i class="fas fa-shopping-bag"></i>
                    <span>{{ __('Buyurtmalar') }}</span>
                </a>

                <hr class="menu-divider">

                <!-- Language Switcher -->
                <div class="language-switcher">
                    <h5>{{ __('Til') }}</h5>
                    <a href="#" class="language-item {{ app()->getLocale() == 'uz' ? 'active' : '' }}">
                        <span class="flag">🇺🇿</span>
                        <span>O'zbekcha</span>
                    </a>
                    <a href="#" class="language-item {{ app()->getLocale() == 'ru' ? 'active' : '' }}">
                        <span class="flag">🇷🇺</span>
                        <span>Русский</span>
                    </a>
                </div>

                <hr class="menu-divider">

                <!-- Logout -->
                <form method="POST" action="#" class="logout-form">
                    @csrf
                    <button type="submit" class="menu-item logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>{{ __('Chiqish') }}</span>
                    </button>
                </form>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Bottom Navigation -->
<div class="bottom-navigation">
    <a href="{{ route('template.mobile.home') }}" class="bottom-nav-item {{ request()->routeIs('template.mobile.home') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
        <span>{{ __('Bosh') }}</span>
    </a>
    <a href="{{ route('template.mobile.categories') }}" class="bottom-nav-item {{ request()->routeIs('template.mobile.categories*') ? 'active' : '' }}">
        <i class="fas fa-th-large"></i>
        <span>{{ __('Kategoriya') }}</span>
    </a>
    <a href="{{ route('template.mobile.products') }}" class="bottom-nav-item {{ request()->routeIs('template.mobile.products') ? 'active' : '' }}">
        <i class="fas fa-search"></i>
        <span>{{ __('Qidiruv') }}</span>
    </a>
    <a href="{{ route('template.mobile.wishlist') }}" class="bottom-nav-item {{ request()->routeIs('template.mobile.wishlist') ? 'active' : '' }}">
        <i class="fas fa-heart"></i>
        <span>{{ __('Sevimli') }}</span>
    </a>
    @auth
    <a href="#" class="bottom-nav-item">
        <i class="fas fa-user"></i>
        <span>{{ __('Profil') }}</span>
    </a>
    @else
    <a href="#" class="bottom-nav-item">
        <i class="fas fa-sign-in-alt"></i>
        <span>{{ __('Kirish') }}</span>
    </a>
    @endauth
</div>

<script>
function toggleSearch() {
    const searchBar = document.getElementById('searchBar');
    searchBar.style.display = searchBar.style.display === 'block' ? 'none' : 'block';
}

function toggleMenu() {
    const sideMenu = document.getElementById('sideMenu');
    sideMenu.classList.toggle('active');
}
</script>
