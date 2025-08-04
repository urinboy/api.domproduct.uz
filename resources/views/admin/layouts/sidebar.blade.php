<!-- Sidebar -->
<div class="flex flex-col flex-grow border-r border-gray-200 pt-5 pb-4 bg-white overflow-y-auto">
    <!-- Logo -->
    <div class="flex items-center flex-shrink-0 px-4">
        <img class="h-8 w-auto" src="{{ asset('images/logo.png') }}" alt="DOM Product" onerror="this.style.display='none'">
        <h2 class="ml-3 text-xl font-bold text-gray-900">DOM Product</h2>
    </div>

    <!-- User info -->
    <div class="mt-5 px-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                @if(auth()->user()->avatar)
                    <img class="h-10 w-10 rounded-full object-cover" src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}">
                @else
                    <div class="h-10 w-10 rounded-full bg-primary-500 flex items-center justify-center">
                        <span class="text-white font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-8 flex-1 px-2 space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7zm0 0V5a2 2 0 012-2h6l2 2h6a2 2 0 012 2v2M7 13h10M7 17h10" />
            </svg>
            {{ __('admin.dashboard') }}
        </a>

        <!-- Content Management Section -->
        @canany(['categories.view', 'products.view'])
        <div class="mt-8">
            <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                {{ __('admin.content_management') }}
            </h3>

            <!-- Categories -->
            @can('categories.view')
            <a href="{{ route('admin.categories.index') }}"
               class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-7H5m14 14H5" />
                </svg>
                {{ __('admin.categories') }}
            </a>
            @endcan

            <!-- Products -->
            @can('products.view')
            <a href="{{ route('admin.products.index') }}"
               class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                {{ __('admin.products') }}
            </a>
            @endcan
        </div>
        @endcanany

        <!-- Order Management -->
        @can('orders.view')
        <div class="mt-8">
            <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                {{ __('admin.system_management') }}
            </h3>

            <a href="{{ route('admin.orders.index') }}"
               class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                {{ __('admin.orders') }}
                @if(isset($pendingOrdersCount) && $pendingOrdersCount > 0)
                    <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ $pendingOrdersCount }}
                    </span>
                @endif
            </a>
        </div>
        @endcan

        <!-- User Management -->
        @can('users.view')
        <a href="{{ route('admin.users.index') }}"
           class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
            {{ __('admin.users') }}
        </a>
        @endcan

        <!-- Languages & Translations -->
        @canany(['languages.view', 'translations.view'])
        <div class="mt-8">
            <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                {{ __('admin.multilingual') }}
            </h3>

            @can('languages.view')
            <a href="{{ route('admin.languages.index') }}"
               class="nav-item {{ request()->routeIs('admin.languages.*') ? 'active' : '' }}">
                <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                </svg>
                {{ __('admin.languages') }}
            </a>
            @endcan

            @can('translations.view')
            <a href="{{ route('admin.translations.index') }}"
               class="nav-item {{ request()->routeIs('admin.translations.*') ? 'active' : '' }}">
                <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                {{ __('admin.translations') }}
            </a>
            @endcan
        </div>
        @endcanany

        <!-- Reports -->
        @can('reports.view')
        <div class="mt-8">
            <a href="{{ route('admin.reports.index') }}"
               class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                {{ __('admin.reports') }}
            </a>
        </div>
        @endcan

        <!-- Settings -->
        @if(auth()->user()->isAdmin())
        <div class="mt-8">
            <a href="{{ route('admin.settings.index') }}"
               class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <svg class="mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ __('admin.settings') }}
            </a>
        </div>
        @endif
    </nav>

    <!-- Language Switcher -->
    <div class="flex-shrink-0 px-4 py-4 border-t border-gray-200" x-data="languageSwitcher()">
        <div class="relative">
            <button @click="open = !open"
                    x-data="{ open: false }"
                    class="w-full flex items-center justify-between text-left px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md">
                <div class="flex items-center">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                    </svg>
                    <span>
                        @switch(app()->getLocale())
                            @case('uz')
                                O'zbek
                                @break
                            @case('en')
                                English
                                @break
                            @case('ru')
                                Русский
                                @break
                        @endswitch
                    </span>
                </div>
                <svg class="h-4 w-4" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open"
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute bottom-full left-0 w-full mb-1 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
                 style="display: none;">
                <div class="py-1">
                    <a href="?lang=uz" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() == 'uz' ? 'bg-gray-50' : '' }}">
                        <span class="mr-2">🇺🇿</span>
                        O'zbek
                    </a>
                    <a href="?lang=en" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() == 'en' ? 'bg-gray-50' : '' }}">
                        <span class="mr-2">🇺🇸</span>
                        English
                    </a>
                    <a href="?lang=ru" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() == 'ru' ? 'bg-gray-50' : '' }}">
                        <span class="mr-2">🇷🇺</span>
                        Русский
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
