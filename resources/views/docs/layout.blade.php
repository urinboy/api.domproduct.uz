<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="DOM Product Project API Documentation - Complete developer guide">
    <meta name="keywords" content="API, Documentation, Laravel, E-commerce, REST API">
    <meta name="author" content="DOM Product Project">
    <title>@yield('title', 'API Documentation') - DOM Product Project</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Prism.js for syntax highlighting -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-okaidia.min.css" rel="stylesheet">

    <style>
        :root {
            /* Brand Colors */
            --primary-color: #087c36;
            --secondary-color: #0d6a32;
            --accent-color: #10b94c;

            /* Documentation Colors */
            --docs-bg: #fafbfc;
            --docs-sidebar-bg: #ffffff;
            --docs-content-bg: #ffffff;
            --docs-border: #e1e8ed;
            --docs-code-bg: #1e1e1e;
            --docs-code-border: #333;

            /* Text Colors */
            --text-primary: #2c3e50;
            --text-secondary: #5a6c7d;
            --text-muted: #8898aa;
            --text-inverse: #ffffff;

            /* Status Colors */
            --success-color: #28a745;
            --error-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;

            /* Shadows */
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 25px rgba(0,0,0,0.15);

            /* Border Radius */
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;

            /* Transitions */
            --transition-fast: 0.15s ease;
            --transition-normal: 0.3s ease;
        }

        /* Dark Theme */
        [data-theme="dark"] {
            --docs-bg: #0d1117;
            --docs-sidebar-bg: #161b22;
            --docs-content-bg: #161b22;
            --docs-border: #30363d;
            --text-primary: #f0f6fc;
            --text-secondary: #8b949e;
            --text-muted: #6e7681;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--docs-bg);
            color: var(--text-primary);
            line-height: 1.6;
            transition: var(--transition-normal);
        }

        /* Layout Structure */
        .docs-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .docs-sidebar {
            width: 280px;
            background: var(--docs-sidebar-bg);
            border-right: 1px solid var(--docs-border);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: var(--transition-normal);
        }

        .docs-sidebar.mobile-hidden {
            transform: translateX(-100%);
        }

        /* Sidebar Header */
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--docs-border);
            background: var(--docs-sidebar-bg);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.25rem;
        }

        .sidebar-logo i {
            font-size: 1.5rem;
        }

        /* Navigation */
        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            padding: 0 1.5rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
        }

        .nav-items {
            list-style: none;
        }

        .nav-item {
            margin: 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition-fast);
            position: relative;
        }

        .nav-link:hover {
            background: rgba(8, 124, 54, 0.1);
            color: var(--primary-color);
        }

        .nav-link.active {
            background: rgba(8, 124, 54, 0.15);
            color: var(--primary-color);
            font-weight: 600;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--primary-color);
        }

        /* Main Content */
        .docs-main {
            flex: 1;
            margin-left: 280px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Header */
        .docs-header {
            background: var(--docs-content-bg);
            border-bottom: 1px solid var(--docs-border);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-primary);
            cursor: pointer;
            padding: 0.5rem;
        }

        .header-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .theme-toggle {
            background: none;
            border: 1px solid var(--docs-border);
            border-radius: var(--radius-md);
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            color: var(--text-secondary);
            transition: var(--transition-fast);
        }

        .theme-toggle:hover {
            background: var(--docs-bg);
            color: var(--primary-color);
        }

        .lang-toggle {
            background: none;
            border: 1px solid var(--docs-border);
            border-radius: var(--radius-md);
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            color: var(--text-secondary);
            transition: var(--transition-fast);
            font-size: 1.2rem;
            width: 45px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lang-toggle:hover {
            background: var(--docs-bg);
            color: var(--primary-color);
        }

        /* Content Area */
        .docs-content {
            flex: 1;
            padding: 2rem;
            background: var(--docs-content-bg);
            overflow-x: auto;
        }

        .content-wrapper {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        /* Page Title */
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .page-description {
            font-size: 1.125rem;
            color: var(--text-secondary);
            margin-bottom: 3rem;
            line-height: 1.7;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .docs-sidebar {
                transform: translateX(-100%);
            }

            .docs-sidebar.mobile-visible {
                transform: translateX(0);
            }

            .docs-main {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: block;
            }

            .docs-content {
                padding: 1rem;
            }

            .page-title {
                font-size: 2rem;
            }
        }

        /* Utility Classes */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }

        .mb-1 { margin-bottom: 0.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .mb-3 { margin-bottom: 1.5rem; }
        .mb-4 { margin-bottom: 2rem; }

        .mt-1 { margin-top: 0.5rem; }
        .mt-2 { margin-top: 1rem; }
        .mt-3 { margin-top: 1.5rem; }
        .mt-4 { margin-top: 2rem; }
    </style>

    @stack('styles')
</head>
<body data-theme="light">
    <div class="docs-layout">
        <!-- Sidebar -->
        <aside class="docs-sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('docs.index') }}" class="sidebar-logo">
                    <i class="fas fa-book"></i>
                    <span data-translate="api-docs">API Docs</span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title" data-translate="getting-started">Getting Started</div>
                    <ul class="nav-items">
                        <li class="nav-item">
                            <a href="{{ route('docs.index') }}" class="nav-link {{ request()->routeIs('docs.index') ? 'active' : '' }}">
                                <i class="fas fa-home"></i>
                                <span data-translate="overview">Overview</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('docs.getting-started') }}" class="nav-link {{ request()->routeIs('docs.getting-started') ? 'active' : '' }}">
                                <i class="fas fa-rocket"></i>
                                <span data-translate="quick-start">Quick Start</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('docs.authentication') }}" class="nav-link {{ request()->routeIs('docs.authentication') ? 'active' : '' }}">
                                <i class="fas fa-shield-alt"></i>
                                <span data-translate="authentication">Authentication</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title" data-translate="api-endpoints">API Endpoints</div>
                    <ul class="nav-items">
                        <li class="nav-item">
                            <a href="{{ route('docs.endpoints', 'auth') }}" class="nav-link {{ request()->route('section') === 'auth' ? 'active' : '' }}">
                                <i class="fas fa-user-shield"></i>
                                <span data-translate="authentication">Authentication</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('docs.endpoints', 'users') }}" class="nav-link {{ request()->route('section') === 'users' ? 'active' : '' }}">
                                <i class="fas fa-users"></i>
                                <span data-translate="users">Users</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('docs.endpoints', 'products') }}" class="nav-link {{ request()->route('section') === 'products' ? 'active' : '' }}">
                                <i class="fas fa-box"></i>
                                <span data-translate="products">Products</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('docs.endpoints', 'categories') }}" class="nav-link {{ request()->route('section') === 'categories' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <span data-translate="categories">Categories</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('docs.endpoints', 'orders') }}" class="nav-link {{ request()->route('section') === 'orders' ? 'active' : '' }}">
                                <i class="fas fa-shopping-cart"></i>
                                <span data-translate="orders">Orders</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title" data-translate="tools">Tools</div>
                    <ul class="nav-items">
                        <li class="nav-item">
                            <a href="{{ route('docs.api-tester') }}" class="nav-link {{ request()->routeIs('docs.api-tester') ? 'active' : '' }}">
                                <i class="fas fa-flask"></i>
                                <span data-translate="api-tester">API Tester</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="docs-main">
            <!-- Header -->
            <header class="docs-header">
                <button class="mobile-menu-btn" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="header-controls">
                    <button class="lang-toggle" onclick="toggleLanguage()">
                        <span id="lang-flag">🇺🇿</span>
                    </button>
                    <button class="theme-toggle" onclick="toggleTheme()">
                        <i class="fas fa-moon" id="theme-icon"></i>
                    </button>
                </div>
            </header>

            <!-- Content -->
            <div class="docs-content">
                <div class="content-wrapper">
                    @yield('breadcrumb')

                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <!-- Prism.js for syntax highlighting -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>

    <script>
        // Language translations
        const translations = {
            uz: {
                'api-docs': 'API Hujjatlari',
                'getting-started': 'Boshlash',
                'overview': 'Umumiy Ko\'rinish',
                'quick-start': 'Tezkor Boshlash',
                'authentication': 'Autentifikatsiya',
                'api-endpoints': 'API Nuqtalari',
                'users': 'Foydalanuvchilar',
                'products': 'Mahsulotlar',
                'categories': 'Kategoriyalar',
                'orders': 'Buyurtmalar',
                'tools': 'Vositalar',
                'api-tester': 'API Tester',
                'documentation': 'Hujjatlar',

                // Index page translations
                'main-title': 'DOM Product API Hujjatlari',
                'main-subtitle': 'DOM Product Project uchun to\'liq API hujjatlariga xush kelibsiz. Bu qo\'llanma sizga elektron tijorat platformamiz bilan tez va samarali integratsiya qilishda yordam beradi.',
                'api-documentation': 'API Hujjatlari',
                'base-url': 'Asosiy URL',
                'base-url-desc': 'Barcha API nuqtalari ushbu asosiy URL ga nisbatan',
                'version': 'Versiya',
                'version-desc': 'Joriy API versiyasi to\'liq orqaga qarab moslik bilan',
                'auth-title': 'Autentifikatsiya',
                'auth-desc': 'JSON Web Tokens yordamida xavfsiz autentifikatsiya',
                'response-format': 'Javob Formati',
                'response-format-desc': 'Barcha javoblar JSON formatida qaytariladi',
                'quick-start-title': 'Tezkor Boshlash',
                'quick-start-desc': 'DOM Product API bilan bir necha qadamda ishlashni boshlang:',
                'step-1-title': 'Hisob Yarating',
                'step-1-desc': 'Yangi foydalanuvchi hisobini yarating yoki mavjud hisobingizdan foydalaning',
                'step-2-title': 'Token Oling',
                'step-2-desc': 'Autentifikatsiya qiling va API kirish tokeningizni oling',
                'step-3-title': 'API Chaqiruvlari',
                'step-3-desc': 'API nuqtalarimizga so\'rovlar yuborishni boshlang',
                'learn-auth': 'Autentifikatsiya haqida o\'rganish →',
                'see-guide': 'Boshlash qo\'llanmasini ko\'rish →',
                'explore-endpoints': 'API Nuqtalarini o\'rganish →',
                'api-features': 'API Imkoniyatlari',
                'user-management': 'Foydalanuvchilarni Boshqarish',
                'user-management-desc': 'To\'liq foydalanuvchi ro\'yxatdan o\'tish, autentifikatsiya va profil boshqaruvi',
                'product-catalog': 'Mahsulot Katalogi',
                'product-catalog-desc': 'Mahsulotlar, kategoriyalar, inventar va narxlarni boshqarish',
                'order-processing': 'Buyurtmalarni Qayta Ishlash',
                'order-processing-desc': 'Buyurtmalar, to\'lovlar va buyurtma holati boshqaruvi',
                'wishlist-favorites': 'Istaklar va Sevimlilar',
                'wishlist-favorites-desc': 'Foydalanuvchi istak ro\'yxati va sevimli mahsulotlar funksiyasi',
                'address-management': 'Manzillarni Boshqarish',
                'address-management-desc': 'Foydalanuvchi manzillari, yetkazib berish joylari va dostavka boshqaruvi',
                'notifications': 'Bildirishnomalar',
                'notifications-desc': 'Real vaqt bildirish va xabar tizimi',
                'example-request': 'So\'rov Misoli',
                'example-request-desc': 'API so\'rovini qanday yuborish misoli:',
                'response': 'Javob:'
            },
            en: {
                'api-docs': 'API Docs',
                'getting-started': 'Getting Started',
                'overview': 'Overview',
                'quick-start': 'Quick Start',
                'authentication': 'Authentication',
                'api-endpoints': 'API Endpoints',
                'users': 'Users',
                'products': 'Products',
                'categories': 'Categories',
                'orders': 'Orders',
                'tools': 'Tools',
                'api-tester': 'API Tester',
                'documentation': 'Documentation',

                // Index page translations
                'main-title': 'DOM Product API Documentation',
                'main-subtitle': 'Welcome to the comprehensive API documentation for DOM Product Project. This guide will help you integrate with our e-commerce platform quickly and efficiently.',
                'api-documentation': 'API Documentation',
                'base-url': 'Base URL',
                'base-url-desc': 'All API endpoints are relative to this base URL',
                'version': 'Version',
                'version-desc': 'Current API version with full backward compatibility',
                'auth-title': 'Authentication',
                'auth-desc': 'Secure authentication using JSON Web Tokens',
                'response-format': 'Response Format',
                'response-format-desc': 'All responses are returned in JSON format',
                'quick-start-title': 'Quick Start',
                'quick-start-desc': 'Get started with DOM Product API in just a few steps:',
                'step-1-title': 'Register an Account',
                'step-1-desc': 'Create a new user account or use existing credentials',
                'step-2-title': 'Get Access Token',
                'step-2-desc': 'Authenticate and receive your API access token',
                'step-3-title': 'Make API Calls',
                'step-3-desc': 'Start making requests to our API endpoints',
                'learn-auth': 'Learn about Authentication →',
                'see-guide': 'See Getting Started Guide →',
                'explore-endpoints': 'Explore API Endpoints →',
                'api-features': 'API Features',
                'user-management': 'User Management',
                'user-management-desc': 'Complete user registration, authentication, and profile management',
                'product-catalog': 'Product Catalog',
                'product-catalog-desc': 'Manage products, categories, inventory, and pricing',
                'order-processing': 'Order Processing',
                'order-processing-desc': 'Handle orders, payments, and order status management',
                'wishlist-favorites': 'Wishlist & Favorites',
                'wishlist-favorites-desc': 'User wishlist and favorite products functionality',
                'address-management': 'Address Management',
                'address-management-desc': 'User addresses, shipping locations, and delivery management',
                'notifications': 'Notifications',
                'notifications-desc': 'Real-time notifications and messaging system',
                'example-request': 'Example Request',
                'example-request-desc': 'Here\'s a simple example of how to make an API request:',
                'response': 'Response:'
            },
            ru: {
                'api-docs': 'API Документы',
                'getting-started': 'Начало работы',
                'overview': 'Обзор',
                'quick-start': 'Быстрый старт',
                'authentication': 'Аутентификация',
                'api-endpoints': 'API Конечные точки',
                'users': 'Пользователи',
                'products': 'Продукты',
                'categories': 'Категории',
                'orders': 'Заказы',
                'tools': 'Инструменты',
                'api-tester': 'API Тестер',
                'documentation': 'Документация',

                // Index page translations
                'main-title': 'Документация DOM Product API',
                'main-subtitle': 'Добро пожаловать в полную документацию API для DOM Product Project. Это руководство поможет вам быстро и эффективно интегрироваться с нашей платформой электронной коммерции.',
                'api-documentation': 'API Документация',
                'base-url': 'Базовый URL',
                'base-url-desc': 'Все конечные точки API относительны к этому базовому URL',
                'version': 'Версия',
                'version-desc': 'Текущая версия API с полной обратной совместимостью',
                'auth-title': 'Аутентификация',
                'auth-desc': 'Безопасная аутентификация с использованием JSON Web Tokens',
                'response-format': 'Формат ответа',
                'response-format-desc': 'Все ответы возвращаются в формате JSON',
                'quick-start-title': 'Быстрый старт',
                'quick-start-desc': 'Начните работу с DOM Product API всего за несколько шагов:',
                'step-1-title': 'Зарегистрируйте аккаунт',
                'step-1-desc': 'Создайте новую учетную запись пользователя или используйте существующие данные',
                'step-2-title': 'Получите токен доступа',
                'step-2-desc': 'Авторизуйтесь и получите ваш токен доступа к API',
                'step-3-title': 'Делайте API вызовы',
                'step-3-desc': 'Начните отправлять запросы к нашим конечным точкам API',
                'learn-auth': 'Узнать об аутентификации →',
                'see-guide': 'Посмотреть руководство по началу работы →',
                'explore-endpoints': 'Изучить конечные точки API →',
                'api-features': 'Возможности API',
                'user-management': 'Управление пользователями',
                'user-management-desc': 'Полная регистрация пользователей, аутентификация и управление профилем',
                'product-catalog': 'Каталог продуктов',
                'product-catalog-desc': 'Управление продуктами, категориями, инвентарем и ценообразованием',
                'order-processing': 'Обработка заказов',
                'order-processing-desc': 'Обработка заказов, платежей и управление статусом заказа',
                'wishlist-favorites': 'Список желаний и избранное',
                'wishlist-favorites-desc': 'Функциональность списка желаний пользователя и избранных продуктов',
                'address-management': 'Управление адресами',
                'address-management-desc': 'Адреса пользователей, места доставки и управление доставкой',
                'notifications': 'Уведомления',
                'notifications-desc': 'Система уведомлений и сообщений в реальном времени',
                'example-request': 'Пример запроса',
                'example-request-desc': 'Вот простой пример того, как сделать API запрос:',
                'response': 'Ответ:'
            }
        };

        let currentLang = localStorage.getItem('docs-language') || 'uz';

        // Language Toggle
        function toggleLanguage() {
            const languages = ['uz', 'en', 'ru'];
            const flags = ['🇺🇿', '🇺🇸', '🇷🇺'];

            const currentIndex = languages.indexOf(currentLang);
            const nextIndex = (currentIndex + 1) % languages.length;

            currentLang = languages[nextIndex];
            localStorage.setItem('docs-language', currentLang);

            document.getElementById('lang-flag').textContent = flags[nextIndex];
            updateTexts();
        }

        // Update all translatable texts
        function updateTexts() {
            const elements = document.querySelectorAll('[data-translate]');
            elements.forEach(element => {
                const key = element.getAttribute('data-translate');
                if (translations[currentLang] && translations[currentLang][key]) {
                    element.textContent = translations[currentLang][key];
                }
            });
        }

        // Theme Toggle
        function toggleTheme() {
            const body = document.body;
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            const icon = document.getElementById('theme-icon');

            body.setAttribute('data-theme', newTheme);
            icon.className = newTheme === 'light' ? 'fas fa-moon' : 'fas fa-sun';

            localStorage.setItem('theme', newTheme);
        }

        // Sidebar Toggle for Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('mobile-visible');
        }

        // Initialize theme and language from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            const body = document.body;
            const icon = document.getElementById('theme-icon');

            body.setAttribute('data-theme', savedTheme);
            icon.className = savedTheme === 'light' ? 'fas fa-moon' : 'fas fa-sun';

            // Initialize language
            const savedLang = localStorage.getItem('docs-language') || 'uz';
            const languages = ['uz', 'en', 'ru'];
            const flags = ['🇺🇿', '🇺🇸', '🇷🇺'];

            currentLang = savedLang;
            const langIndex = languages.indexOf(currentLang);
            document.getElementById('lang-flag').textContent = flags[langIndex];

            updateTexts();
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const menuBtn = document.querySelector('.mobile-menu-btn');

            if (window.innerWidth <= 768 &&
                !sidebar.contains(e.target) &&
                !menuBtn.contains(e.target)) {
                sidebar.classList.remove('mobile-visible');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
