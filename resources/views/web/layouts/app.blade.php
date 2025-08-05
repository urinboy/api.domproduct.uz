<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#10b981">

    <title>@yield('title', 'DOM PRODUCT - Bosh sahifa')</title>
    <meta name="description" content="@yield('description', 'DOM PRODUCT - O\'zbekistondagi eng yaxshi onlayn oziq-ovqat marketi. Toza va sifatli mahsulotlar, tez yetkazib berish, qulay narxlar.')">
    <meta name="keywords" content="@yield('keywords', 'onlayn market, oziq-ovqat, yetkazib berish, Toshkent, mevalar, sabzavotlar')">

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <link rel="icon" href="{{ asset('favicon.png') }}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tailwind-components.css') }}" rel="stylesheet">
    <link href="{{ mix('css/web.css') }}" rel="stylesheet">

    <!-- Tailwind CSS CDN for development -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#10b981',
                        'primary-dark': '#059669',
                        'primary-light': '#34d399',
                        secondary: '#1f2937',
                        accent: '#f59e0b'
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom styles */
        .gradient-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .btn {
            @apply px-6 py-3 rounded-lg font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2;
        }

        .btn-primary {
            @apply bg-primary hover:bg-primary-dark text-white focus:ring-primary/50;
        }

        .btn-outline {
            @apply border-2 border-primary text-primary hover:bg-primary hover:text-white focus:ring-primary/50;
        }

        .btn-secondary {
            @apply bg-gray-600 hover:bg-gray-700 text-white focus:ring-gray-500;
        }

        .form-input {
            @apply w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors;
        }

        .form-label {
            @apply block text-sm font-semibold text-gray-700 mb-2;
        }

        .form-group {
            @apply mb-4;
        }

        .card {
            @apply bg-white rounded-2xl shadow-sm p-6;
        }

        .loading {
            @apply opacity-50 pointer-events-none;
        }

        /* Animation */
        .slide-up {
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Mobile menu animation */
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }

        .mobile-menu.active {
            transform: translateX(0);
        }

        /* Cart badge */
        .cart-badge {
            @apply absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Navigation Header -->
    <header class="gradient-bg text-white sticky top-0 z-50 shadow-lg">
        <div class="container mx-auto px-4">
            <!-- Top Bar (Desktop) -->
            <div class="hidden md:flex items-center justify-between py-2 text-sm border-b border-white/20">
                <div class="flex items-center space-x-6">
                    <span>📞 +998 78 150 15 01</span>
                    <span>📧 info@domproduct.uz</span>
                    <span>🕒 24/7 xizmat</span>
                </div>
                <div class="flex items-center space-x-4">
                    <select class="bg-transparent text-white text-sm" onchange="changeLanguage(this.value)">
                        <option value="uz" {{ app()->getLocale() == 'uz' ? 'selected' : '' }}>🇺🇿 O'zbek</option>
                        <option value="ru" {{ app()->getLocale() == 'ru' ? 'selected' : '' }}>🇷🇺 Русский</option>
                        <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>🇺🇸 English</option>
                    </select>
                    @auth
                        <a href="{{ route('web.profile') }}" class="hover:text-green-200">{{ auth()->user()->name }}</a>
                        <form action="{{ route('web.auth.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-green-200">Chiqish</button>
                        </form>
                    @else
                        <a href="{{ route('web.login') }}" class="hover:text-green-200">Kirish</a>
                    @endauth
                </div>
            </div>

            <!-- Main Navigation -->
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7ZM9 3V4H15V3H9ZM7 6V19H17V6H7Z"/>
                        </svg>
                    </div>
                    <div>
                        <a href="{{ route('web.home') }}">
                            <h1 class="text-xl font-bold">DOM PRODUCT</h1>
                            <p class="text-xs text-green-200">Onlayn Market</p>
                        </a>
                    </div>
                </div>

                <!-- Search Bar (Desktop) -->
                <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                    <div class="relative w-full">
                        <form action="{{ route('web.search') }}" method="GET">
                            <input type="text"
                                   name="q"
                                   value="{{ request('q') }}"
                                   placeholder="Mahsulot, kategoriya yoki brend qidiring..."
                                   class="w-full px-4 py-2 rounded-lg bg-white/20 text-white placeholder-green-200 border border-white/30 focus:border-primary focus:outline-none focus:ring-2 focus:ring-white/20">
                            <button type="submit" class="absolute right-2 top-2 text-green-200 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- User Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Wishlist (Desktop) -->
                    @auth
                    <a href="{{ route('web.profile.wishlist') }}" class="hidden md:flex p-2 hover:bg-white/20 rounded-lg transition-colors relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-xs text-white rounded-full w-5 h-5 flex items-center justify-center" id="wishlist-count">
                            {{ auth()->user()->favoriteProducts()->count() ?? 0 }}
                        </span>
                    </a>
                    @endauth

                    <!-- Cart -->
                    <a href="{{ route('web.cart') }}" class="relative p-2 hover:bg-white/20 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9"></path>
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-accent text-xs text-white rounded-full w-5 h-5 flex items-center justify-center" id="cart-count">
                            {{ session('cart') ? collect(session('cart'))->sum('quantity') : 0 }}
                        </span>
                    </a>

                    <!-- Profile -->
                    @auth
                        <a href="{{ route('web.profile') }}" class="p-2 hover:bg-white/20 rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('web.login') }}" class="p-2 hover:bg-white/20 rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </a>
                    @endauth

                    <!-- Menu Toggle (Mobile) -->
                    <button id="mobile-menu-btn" class="md:hidden p-2 hover:bg-white/20 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Search -->
            <div class="md:hidden pb-4">
                <div class="relative">
                    <form action="{{ route('web.search') }}" method="GET">
                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Qidirish..."
                               class="w-full px-4 py-2 rounded-lg bg-white/20 text-white placeholder-green-200 border border-white/30 focus:border-primary focus:outline-none">
                        <button type="submit" class="absolute right-2 top-2 text-green-200 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="fixed inset-0 z-40 bg-black/50 hidden">
        <div class="mobile-menu fixed left-0 top-0 h-full w-80 bg-white">
            <div class="p-4 border-b">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800">Menyu</h2>
                    <button id="close-mobile-menu" class="p-2 text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <nav class="p-4 space-y-2">
                <a href="{{ route('web.home') }}" class="block py-3 text-gray-700 hover:text-primary">Bosh sahifa</a>
                <a href="{{ route('web.categories.index') }}" class="block py-3 text-gray-700 hover:text-primary">Kategoriyalar</a>
                <a href="{{ route('web.products.index') }}" class="block py-3 text-gray-700 hover:text-primary">Mahsulotlar</a>
                <a href="{{ route('web.about') }}" class="block py-3 text-gray-700 hover:text-primary">Biz haqimizda</a>
                <a href="{{ route('web.contact') }}" class="block py-3 text-gray-700 hover:text-primary">Bog'lanish</a>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-secondary text-white mt-16">
        <div class="container mx-auto px-4 py-12">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7Z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold">DOM PRODUCT</h3>
                    </div>
                    <p class="text-gray-300 mb-4">Eng yaxshi mahsulotlar, tez yetkazib berish, arzon narxlar bilan sizga xizmat qilamiz.</p>
                    <div class="flex space-x-3">
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-white/20 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-white/20 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-white/20 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Tezkor havolalar</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="{{ route('web.home') }}" class="hover:text-white transition-colors">Bosh sahifa</a></li>
                        <li><a href="{{ route('web.categories.index') }}" class="hover:text-white transition-colors">Kategoriyalar</a></li>
                        <li><a href="{{ route('web.products.index') }}" class="hover:text-white transition-colors">Mahsulotlar</a></li>
                        <li><a href="{{ route('web.about') }}" class="hover:text-white transition-colors">Biz haqimizda</a></li>
                        <li><a href="{{ route('web.contact') }}" class="hover:text-white transition-colors">Bog'lanish</a></li>
                    </ul>
                </div>

                <!-- Customer Service -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Mijozlar xizmati</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-white transition-colors">Yetkazib berish</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Qaytarish</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Ko'p so'raladigan savollar</a></li>
                        <li><a href="{{ route('web.terms') }}" class="hover:text-white transition-colors">Foydalanish shartlari</a></li>
                        <li><a href="{{ route('web.privacy') }}" class="hover:text-white transition-colors">Maxfiylik siyosati</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Bog'lanish</h4>
                    <div class="space-y-3 text-gray-300">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>+998 90 123 45 67</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                            <span>info@domproduct.uz</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Toshkent shahri, Yunusobod tumani</span>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-8 border-gray-600">

            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    © {{ date('Y') }} DOM PRODUCT. Barcha huquqlar himoyalangan.
                </p>
                <div class="flex items-center space-x-4 mt-4 md:mt-0">
                    <span class="text-gray-400 text-sm">To'lov usullari:</span>
                    <div class="flex space-x-2">
                        <div class="w-8 h-5 bg-white/10 rounded"></div>
                        <div class="w-8 h-5 bg-white/10 rounded"></div>
                        <div class="w-8 h-5 bg-white/10 rounded"></div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Loading Spinner -->
    <div id="loading-spinner" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div>
            <p class="mt-4 text-gray-600">Yuklanmoqda...</p>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // CSRF Token setup
        window.csrfToken = '{{ csrf_token() }}';

        // Base URL
        window.baseUrl = '{{ url('/') }}';

        // Auth user
        window.authUser = @json(auth()->check() ? auth()->user() : null);

        // Current locale
        window.currentLocale = '{{ app()->getLocale() }}';
    </script>

    <script>
        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeMobileMenu = document.getElementById('close-mobile-menu');
        const mobileMenuContent = document.querySelector('.mobile-menu');

        function openMobileMenu() {
            mobileMenu.classList.remove('hidden');
            setTimeout(() => {
                mobileMenuContent.classList.add('active');
            }, 10);
        }

        function closeMobileMenuFn() {
            mobileMenuContent.classList.remove('active');
            setTimeout(() => {
                mobileMenu.classList.add('hidden');
            }, 300);
        }

        mobileMenuBtn?.addEventListener('click', openMobileMenu);
        closeMobileMenu?.addEventListener('click', closeMobileMenuFn);
        mobileMenu?.addEventListener('click', (e) => {
            if (e.target === mobileMenu) {
                closeMobileMenuFn();
            }
        });

        // Mobile search functionality
        const mobileSearchBtn = document.getElementById('mobile-search-btn');
        const mobileSearch = document.getElementById('mobile-search');

        mobileSearchBtn?.addEventListener('click', () => {
            mobileSearch.classList.toggle('hidden');
        });

        // Profile dropdown functionality
        const profileMenuBtn = document.getElementById('profile-menu-btn');
        const profileDropdown = document.getElementById('profile-dropdown');

        profileMenuBtn?.addEventListener('click', () => {
            profileDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!profileMenuBtn?.contains(e.target) && !profileDropdown?.contains(e.target)) {
                profileDropdown?.classList.add('hidden');
            }
        });

        // Cart functionality
        function updateCartBadge() {
            fetch('/cart/count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('cart-badge');
                    if (data.cart_count > 0) {
                        badge.textContent = data.cart_count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                })
                .catch(error => console.error('Error updating cart badge:', error));
        }

        // Initialize cart badge
        updateCartBadge();

        // Global loading functions
        function showLoading() {
            document.getElementById('loading-spinner').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loading-spinner').classList.add('hidden');
        }

        // Global AJAX error handler
        function handleAjaxError(error) {
            hideLoading();
            console.error('AJAX Error:', error);

            if (error.status === 401) {
                window.location.href = '/login';
                return;
            }

            const message = error.responseJSON?.message || 'Xatolik yuz berdi';
            alert(message);
        }

        // Utility functions
        function formatPrice(price) {
            return new Intl.NumberFormat('uz-UZ').format(price) + ' so\'m';
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    </script>

    <!-- Custom JavaScript -->
    <script src="{{ mix('js/web.js') }}"></script>

    @stack('scripts')
</body>
</html>
