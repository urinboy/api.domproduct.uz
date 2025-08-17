<!-- Web Header Component -->
<header class="header" id="header">
    <div class="header-top">
        <div class="container">
            <div class="header-top-content">
                <div class="header-info">
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <span>+998 (90) 123-45-67</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <span>info@domproduct.uz</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <span>{{ __('Har kuni 9:00 - 21:00') }}</span>
                    </div>
                </div>
                
                <div class="header-actions">
                    <!-- Language Switcher -->
                    <div class="language-switcher">
                        <button class="lang-trigger" onclick="toggleLanguageMenu()">
                            <i class="fas fa-globe"></i>
                            <span>{{ app()->getLocale() == 'uz' ? 'UZ' : 'RU' }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="lang-menu" id="langMenu">
                            <a href="{{ route('lang.switch', 'uz') }}" class="{{ app()->getLocale() == 'uz' ? 'active' : '' }}">
                                <img src="{{ asset('images/flags/uz.png') }}" alt="UZ">
                                <span>O'zbekcha</span>
                            </a>
                            <a href="{{ route('lang.switch', 'ru') }}" class="{{ app()->getLocale() == 'ru' ? 'active' : '' }}">
                                <img src="{{ asset('images/flags/ru.png') }}" alt="RU">
                                <span>Русский</span>
                            </a>
                        </div>
                    </div>

                    <!-- Currency Switcher -->
                    <div class="currency-switcher">
                        <button class="currency-trigger">
                            <i class="fas fa-dollar-sign"></i>
                            <span>UZS</span>
                        </button>
                    </div>

                    <!-- Social Links -->
                    <div class="social-links">
                        <a href="#" class="social-link" data-tooltip="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link" data-tooltip="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" data-tooltip="Telegram">
                            <i class="fab fa-telegram-plane"></i>
                        </a>
                        <a href="#" class="social-link" data-tooltip="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="header-main">
        <div class="container">
            <div class="header-main-content">
                <!-- Logo -->
                <div class="header-logo">
                    <a href="{{ route('home') }}" class="logo-link">
                        <img src="{{ asset('images/logo.svg') }}" alt="DomProduct" class="logo-image">
                        <span class="logo-text">DomProduct</span>
                    </a>
                </div>

                <!-- Search -->
                <div class="header-search">
                    <div class="search-box">
                        <div class="search-category">
                            <select class="category-select" id="searchCategory">
                                <option value="">{{ __('Barcha kategoriyalar') }}</option>
                                @foreach($categories ?? [] as $category)
                                <option value="{{ $category->slug }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="search-input-wrapper">
                            <input type="search" 
                                   class="search-input" 
                                   placeholder="{{ __('Mahsulot, brend yoki kategoriya izlang...') }}"
                                   id="searchInput"
                                   autocomplete="off">
                            <div class="search-suggestions" id="searchSuggestions"></div>
                        </div>
                        
                        <button class="search-btn" onclick="performSearch()">
                            <i class="fas fa-search"></i>
                            <span>{{ __('Qidirish') }}</span>
                        </button>
                    </div>

                    <!-- Quick Search Tags -->
                    <div class="search-tags">
                        <span class="tag-label">{{ __('Tez qidiruv') }}:</span>
                        <a href="#" class="search-tag">{{ __('Telefonlar') }}</a>
                        <a href="#" class="search-tag">{{ __('Noutbuklar') }}</a>
                        <a href="#" class="search-tag">{{ __('Televizorlar') }}</a>
                        <a href="#" class="search-tag">{{ __('Kiyimlar') }}</a>
                    </div>
                </div>

                <!-- Header Actions -->
                <div class="header-actions">
                    <!-- Compare -->
                    <div class="header-action">
                        <a href="{{ route('compare') }}" class="action-link" data-tooltip="{{ __('Taqqoslash') }}">
                            <div class="action-icon">
                                <i class="fas fa-balance-scale"></i>
                                <span class="action-count compare-count">{{ $compareCount ?? 0 }}</span>
                            </div>
                            <span class="action-text">{{ __('Taqqoslash') }}</span>
                        </a>
                    </div>

                    <!-- Wishlist -->
                    <div class="header-action">
                        <a href="{{ route('wishlist') }}" class="action-link" data-tooltip="{{ __('Sevimlilar') }}">
                            <div class="action-icon">
                                <i class="fas fa-heart"></i>
                                <span class="action-count wishlist-count">{{ $wishlistCount ?? 0 }}</span>
                            </div>
                            <span class="action-text">{{ __('Sevimlilar') }}</span>
                        </a>
                    </div>

                    <!-- Cart -->
                    <div class="header-action">
                        <button class="action-link" onclick="toggleCartSidebar()" data-tooltip="{{ __('Savat') }}">
                            <div class="action-icon">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="action-count cart-count">{{ $cartCount ?? 0 }}</span>
                            </div>
                            <div class="action-details">
                                <span class="action-text">{{ __('Savat') }}</span>
                                <span class="action-price">{{ number_format($cartTotal ?? 0) }} {{ __('so\'m') }}</span>
                            </div>
                        </button>
                    </div>

                    <!-- User Account -->
                    <div class="header-action user-account">
                        @auth
                        <div class="user-menu">
                            <button class="user-trigger" onclick="toggleUserMenu()">
                                <div class="user-avatar">
                                    @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                                    @else
                                    <i class="fas fa-user"></i>
                                    @endif
                                </div>
                                <div class="user-info">
                                    <span class="user-name">{{ auth()->user()->name }}</span>
                                    <span class="user-status">{{ __('Akkaunti') }}</span>
                                </div>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            
                            <div class="user-dropdown" id="userDropdown">
                                <div class="user-header">
                                    <div class="user-avatar">
                                        @if(auth()->user()->avatar)
                                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                                        @else
                                        <i class="fas fa-user"></i>
                                        @endif
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ auth()->user()->name }}</div>
                                        <div class="user-email">{{ auth()->user()->email }}</div>
                                    </div>
                                </div>
                                
                                <div class="user-menu-items">
                                    <a href="{{ route('profile') }}" class="menu-item">
                                        <i class="fas fa-user"></i>
                                        <span>{{ __('Profil') }}</span>
                                    </a>
                                    <a href="{{ route('orders') }}" class="menu-item">
                                        <i class="fas fa-shopping-bag"></i>
                                        <span>{{ __('Buyurtmalar') }}</span>
                                    </a>
                                    <a href="{{ route('addresses') }}" class="menu-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ __('Manzillar') }}</span>
                                    </a>
                                    <a href="{{ route('settings') }}" class="menu-item">
                                        <i class="fas fa-cog"></i>
                                        <span>{{ __('Sozlamalar') }}</span>
                                    </a>
                                    <div class="menu-divider"></div>
                                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                                        @csrf
                                        <button type="submit" class="menu-item logout-btn">
                                            <i class="fas fa-sign-out-alt"></i>
                                            <span>{{ __('Chiqish') }}</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                        <button class="action-link" onclick="openAuthModal()" data-tooltip="{{ __('Kirish') }}">
                            <div class="action-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="action-details">
                                <span class="action-text">{{ __('Kirish') }}</span>
                                <span class="action-subtext">{{ __('yoki Ro\'yxatdan o\'tish') }}</span>
                            </div>
                        </button>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
// Header functionality
function toggleLanguageMenu() {
    const menu = document.getElementById('langMenu');
    menu.classList.toggle('active');
}

function toggleUserMenu() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('active');
}

function performSearch() {
    const query = document.getElementById('searchInput').value.trim();
    const category = document.getElementById('searchCategory').value;
    
    if (!query) {
        showToast('{{ __("Qidiruv so\'zini kiriting") }}', 'warning');
        return;
    }
    
    let searchUrl = '{{ route("search") }}?q=' + encodeURIComponent(query);
    if (category) {
        searchUrl += '&category=' + category;
    }
    
    window.location.href = searchUrl;
}

// Search suggestions
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.trim();
    
    if (query.length < 2) {
        document.getElementById('searchSuggestions').style.display = 'none';
        return;
    }
    
    searchTimeout = setTimeout(() => {
        fetch('{{ route("search.suggestions") }}?q=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                displaySearchSuggestions(data);
            })
            .catch(error => console.error('Search suggestions error:', error));
    }, 300);
});

function displaySearchSuggestions(suggestions) {
    const container = document.getElementById('searchSuggestions');
    
    if (!suggestions || suggestions.length === 0) {
        container.style.display = 'none';
        return;
    }
    
    let html = '';
    suggestions.forEach(item => {
        html += `
            <div class="suggestion-item" onclick="selectSuggestion('${item.text}')">
                <i class="fas fa-search"></i>
                <span>${item.text}</span>
            </div>
        `;
    });
    
    container.innerHTML = html;
    container.style.display = 'block';
}

function selectSuggestion(text) {
    document.getElementById('searchInput').value = text;
    document.getElementById('searchSuggestions').style.display = 'none';
    performSearch();
}

// Search input enter key
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        performSearch();
    }
});

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    // Close language menu
    if (!e.target.closest('.language-switcher')) {
        document.getElementById('langMenu').classList.remove('active');
    }
    
    // Close user menu
    if (!e.target.closest('.user-account')) {
        const userDropdown = document.getElementById('userDropdown');
        if (userDropdown) {
            userDropdown.classList.remove('active');
        }
    }
    
    // Close search suggestions
    if (!e.target.closest('.search-input-wrapper')) {
        document.getElementById('searchSuggestions').style.display = 'none';
    }
});

// Update action counts
function updateActionCounts(data) {
    if (data.cart !== undefined) {
        const cartCount = document.querySelector('.cart-count');
        const cartPrice = document.querySelector('.action-price');
        
        if (cartCount) cartCount.textContent = data.cart.count;
        if (cartPrice) cartPrice.textContent = data.cart.total + ' {{ __("so\'m") }}';
    }
    
    if (data.wishlist !== undefined) {
        const wishlistCount = document.querySelector('.wishlist-count');
        if (wishlistCount) wishlistCount.textContent = data.wishlist;
    }
    
    if (data.compare !== undefined) {
        const compareCount = document.querySelector('.compare-count');
        if (compareCount) compareCount.textContent = data.compare;
    }
}
</script>
