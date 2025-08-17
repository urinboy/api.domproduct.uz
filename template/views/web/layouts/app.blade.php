<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="index, follow">
    <meta name="author" content="DomProduct">

    <title>@yield('title', config('app.name', 'DomProduct'))</title>
    <meta name="description" content="@yield('description', __('DomProduct - Zamonaviy onlayn do\'kon'))">
    <meta name="keywords" content="@yield('keywords', __('onlayn xarid, mahsulotlar, do\'kon, DomProduct'))">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', config('app.name'))">
    <meta property="og:description" content="@yield('description', __('DomProduct - Zamonaviy onlayn do\'kon'))">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', config('app.name'))">
    <meta property="twitter:description" content="@yield('description', __('DomProduct - Zamonaviy onlayn do\'kon'))">
    <meta property="twitter:image" content="@yield('og_image', asset('images/og-image.jpg'))">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/web/base.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/web/components.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/web/layout.css') }}">
    
    @stack('styles')

    <!-- Analytics -->
    @if(config('app.env') === 'production')
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'GA_MEASUREMENT_ID');
    </script>
    @endif
</head>
<body class="web-layout @yield('body-class')">
    <!-- Header -->
    @include('web.components.header')

    <!-- Navigation -->
    @include('web.components.navigation')

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('web.components.footer')

    <!-- Overlay Components -->
    @include('web.components.search-modal')
    @include('web.components.cart-sidebar')
    @include('web.components.auth-modal')
    @include('web.components.toast')
    @include('web.components.loading')
    
    <!-- Back to Top -->
    <button class="back-to-top" id="backToTop" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script src="{{ asset('template/assets/js/web/app.js') }}" defer></script>
    
    @stack('scripts')

    <!-- Initialize App -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize web app
            if (typeof WebApp !== 'undefined') {
                WebApp.init();
            }

            // Set CSRF token for AJAX requests
            if (typeof axios !== 'undefined') {
                axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            }

            // Initialize tooltips
            const tooltips = document.querySelectorAll('[data-tooltip]');
            tooltips.forEach(initTooltip);

            // Lazy load images
            if ('IntersectionObserver' in window) {
                const lazyImages = document.querySelectorAll('img[data-lazy]');
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.lazy;
                            img.classList.remove('lazy');
                            imageObserver.unobserve(img);
                        }
                    });
                });
                lazyImages.forEach(img => imageObserver.observe(img));
            }

            // Initialize smooth scrolling
            const smoothLinks = document.querySelectorAll('a[href^="#"]');
            smoothLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    const target = document.querySelector(link.getAttribute('href'));
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });
        });

        // Global functions
        function initTooltip(element) {
            // Tooltip implementation
        }

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Show/hide back to top button
        window.addEventListener('scroll', function() {
            const backToTop = document.getElementById('backToTop');
            if (window.pageYOffset > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });
    </script>
</body>
</html>
