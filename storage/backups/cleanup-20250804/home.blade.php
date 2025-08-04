<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Home') }} - DomProduct</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="font-sans">
    <!-- Navigation -->
    <nav class="bg-white/10 backdrop-blur-md border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-white">DomProduct</h1>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div class="relative">
                        <select onchange="changeLanguage(this.value)"
                                class="bg-white/10 text-white border border-white/20 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="uz" {{ app()->getLocale() == 'uz' ? 'selected' : '' }}>🇺🇿 O'zbek</option>
                            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>🇺🇸 English</option>
                            <option value="ru" {{ app()->getLocale() == 'ru' ? 'selected' : '' }}>🇷🇺 Русский</option>
                        </select>
                    </div>

                    @if(Auth::check())
                        <div class="text-white">
                            {{ __('Welcome') }}, {{ Auth::user()->name }}!
                        </div>
                        <form action="/logout" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm transition-colors">
                                {{ __('Logout') }}
                            </button>
                        </form>
                    @else
                        <a href="/admin/login" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-md text-sm transition-colors">
                            {{ __('Login') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                {{ __('Welcome to DomProduct') }}
            </h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                {{ __('Your one-stop shop for quality products. Browse our extensive catalog and find everything you need.') }}
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Feature 1 -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                <div class="text-emerald-400 text-3xl mb-4">🛍️</div>
                <h3 class="text-xl font-semibold text-white mb-2">{{ __('Wide Selection') }}</h3>
                <p class="text-gray-300">{{ __('Thousands of products across multiple categories') }}</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                <div class="text-emerald-400 text-3xl mb-4">🚚</div>
                <h3 class="text-xl font-semibold text-white mb-2">{{ __('Fast Delivery') }}</h3>
                <p class="text-gray-300">{{ __('Quick and reliable shipping to your doorstep') }}</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                <div class="text-emerald-400 text-3xl mb-4">💎</div>
                <h3 class="text-xl font-semibold text-white mb-2">{{ __('Quality Guarantee') }}</h3>
                <p class="text-gray-300">{{ __('All products are carefully selected for quality') }}</p>
            </div>
        </div>

        <!-- User Status -->
        @if(Auth::check())
            <div class="bg-emerald-500/10 backdrop-blur-md rounded-xl p-6 border border-emerald-500/20 text-center">
                <h2 class="text-2xl font-semibold text-white mb-4">{{ __('User Dashboard') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-center">
                    <div class="bg-white/10 rounded-lg p-4">
                        <div class="text-2xl font-bold text-emerald-400">{{ Auth::user()->name }}</div>
                        <div class="text-gray-300">{{ __('Username') }}</div>
                    </div>
                    <div class="bg-white/10 rounded-lg p-4">
                        <div class="text-2xl font-bold text-emerald-400">{{ Auth::user()->email }}</div>
                        <div class="text-gray-300">{{ __('Email') }}</div>
                    </div>
                    <div class="bg-white/10 rounded-lg p-4">
                        <div class="text-2xl font-bold text-emerald-400">{{ Auth::user()->role ?? 'user' }}</div>
                        <div class="text-gray-300">{{ __('Role') }}</div>
                    </div>
                    <div class="bg-white/10 rounded-lg p-4">
                        <div class="text-2xl font-bold text-emerald-400">{{ Auth::user()->created_at->format('M Y') }}</div>
                        <div class="text-gray-300">{{ __('Member Since') }}</div>
                    </div>
                </div>

                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'manager')
                    <div class="mt-6">
                        <a href="/admin" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                            {{ __('Go to Admin Panel') }} 🚀
                        </a>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white/10 backdrop-blur-md rounded-xl p-8 border border-white/20 text-center">
                <h2 class="text-2xl font-semibold text-white mb-4">{{ __('Join DomProduct Today') }}</h2>
                <p class="text-gray-300 mb-6">{{ __('Create an account to start shopping and enjoy exclusive member benefits') }}</p>
                <div class="space-x-4">
                    <a href="/admin/login" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                        {{ __('Login / Register') }}
                    </a>
                </div>
            </div>
        @endif

        <!-- Coming Soon Section -->
        <div class="mt-12 bg-yellow-500/10 backdrop-blur-md rounded-xl p-6 border border-yellow-500/20">
            <div class="text-center">
                <div class="text-4xl mb-4">🚧</div>
                <h3 class="text-xl font-semibold text-white mb-2">{{ __('Under Development') }}</h3>
                <p class="text-gray-300">
                    {{ __('This is a temporary home page. Full e-commerce functionality is currently under development. Stay tuned for the complete shopping experience!') }}
                </p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-black/20 backdrop-blur-md border-t border-white/20 mt-12">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-400">
                <p>&copy; 2025 DomProduct. {{ __('All rights reserved.') }}</p>
                <p class="mt-2 text-sm">{{ __('Developed with') }} ❤️ {{ __('for the community') }}</p>
            </div>
        </div>
    </footer>

    <script>
        function changeLanguage(locale) {
            window.location.href = `/language/switch/${locale}?redirect=${encodeURIComponent(window.location.pathname)}`;
        }
    </script>
</body>
</html>
