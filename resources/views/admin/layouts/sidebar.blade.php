<!-- Sidebar content -->
<div class="flex flex-col flex-grow border-r border-gray-200 dark:border-gray-700 pt-3 sm:pt-5 pb-4 bg-white dark:bg-gray-800 overflow-y-auto transition-colors duration-200">
    <!-- Logo -->
    <div class="flex items-center flex-shrink-0 px-4 sm:px-6 mb-6 sm:mb-8">
        <div class="flex items-center justify-center h-8 w-8 sm:h-10 sm:w-10 bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg sm:rounded-xl shadow-lg">
            <svg class="h-4 w-4 sm:h-6 sm:w-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
            </svg>
        </div>
        <div class="ml-2 sm:ml-3">
            <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">DOM Product</h2>
            <p class="text-xs text-primary-600 dark:text-primary-400 font-medium">Admin Panel</p>
        </div>
    </div>

    <!-- User info -->
    <div class="px-4 sm:px-6 mb-6 sm:mb-8">
        <div class="flex items-center p-3 sm:p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-lg sm:rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm">
            <div class="flex-shrink-0">
                @if(auth()->user()->avatar)
                    <img class="h-10 w-10 sm:h-12 sm:w-12 rounded-full object-cover ring-2 sm:ring-3 ring-primary-500 ring-offset-1 sm:ring-offset-2 ring-offset-white dark:ring-offset-gray-800" src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}">
                @else
                    <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center ring-2 sm:ring-3 ring-primary-500 ring-offset-1 sm:ring-offset-2 ring-offset-white dark:ring-offset-gray-800 shadow-lg">
                        <span class="text-white font-bold text-sm sm:text-lg">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                <p class="text-xs sm:text-sm font-bold text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-600 dark:text-gray-300 font-medium capitalize">{{ auth()->user()->role }}</p>
                <div class="flex items-center mt-1">
                    <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-green-400 rounded-full mr-1 sm:mr-2"></div>
                    <span class="text-xs text-green-600 dark:text-green-400 font-medium">Online</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-2 flex-1 px-3 sm:px-4 space-y-2">
        <!-- Dashboard -->
        <div class="mb-4 sm:mb-6">
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm font-medium text-gray-900 dark:text-white bg-primary-50 dark:bg-primary-900/30 rounded-lg sm:rounded-xl border border-primary-100 dark:border-primary-800 shadow-sm hover:shadow-md transition-all duration-200">
                <svg class="mr-3 sm:mr-4 h-4 w-4 sm:h-5 sm:w-5 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                </svg>
                <span class="truncate">{{ __('admin.dashboard') }}</span>
                <span class="ml-auto bg-primary-600 text-white text-xs px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full hidden sm:inline">{{ __('admin.main') }}</span>
            </a>
        </div>

        <!-- Management Section -->
        <div x-data="{ open: true }" class="mb-4 sm:mb-6">
            <div class="px-3 sm:px-4 mb-2 sm:mb-3">
                <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('admin.management') }}</h3>
            </div>

            <div class="space-y-1 sm:space-y-2">
                <!-- Products -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="w-full group flex items-center justify-between px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                        <div class="flex items-center min-w-0">
                            <svg class="mr-3 sm:mr-4 h-4 w-4 sm:h-5 sm:w-5 text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM10 18V8l5 3.5V18h-2v-4a1 1 0 00-1-1H8a1 1 0 00-1 1v4H5V11.5L10 8z" clip-rule="evenodd"/>
                            </svg>
                            <span class="truncate">{{ __('admin.products') }}</span>
                        </div>
                        <svg class="h-3 w-3 sm:h-4 sm:w-4 text-gray-400 transform transition-transform flex-shrink-0" :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-1 sm:mt-2 space-y-1 pl-3 sm:pl-4">
                        <a href="#" class="group flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200 border-l-2 border-gray-200 dark:border-gray-600 hover:border-primary-500">
                            <svg class="mr-2 sm:mr-3 h-3 w-3 sm:h-4 sm:w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="truncate">{{ __('admin.all_products') }}</span>
                        </a>
                        <a href="#" class="group flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200 border-l-2 border-gray-200 dark:border-gray-600 hover:border-primary-500">
                            <svg class="mr-2 sm:mr-3 h-3 w-3 sm:h-4 sm:w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            <span class="truncate">{{ __('admin.add_product') }}</span>
                        </a>
                        <a href="#" class="group flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200 border-l-2 border-gray-200 dark:border-gray-600 hover:border-primary-500">
                            <svg class="mr-2 sm:mr-3 h-3 w-3 sm:h-4 sm:w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
                            </svg>
                            <span class="truncate">{{ __('admin.categories') }}</span>
                        </a>
                    </div>
                </div>

                <!-- Orders -->
                <a href="#" class="group flex items-center px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                    <svg class="mr-3 sm:mr-4 h-4 w-4 sm:h-5 sm:w-5 text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                    </svg>
                    <span class="truncate">{{ __('admin.orders') }}</span>
                    <span class="ml-auto bg-orange-500 text-white text-xs px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full">5</span>
                </a>

                <!-- Users -->
                <a href="#" class="group flex items-center px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                    <svg class="mr-3 sm:mr-4 h-4 w-4 sm:h-5 sm:w-5 text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    <span class="truncate">{{ __('admin.users') }}</span>
                </a>
            </div>
        </div>

        <!-- Settings Section -->
        <div x-data="{ open: true }" class="mb-4 sm:mb-6">
            <div class="px-3 sm:px-4 mb-2 sm:mb-3">
                <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('admin.settings') }}</h3>
            </div>

            <div class="space-y-1 sm:space-y-2">
                <!-- Language Switcher -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="w-full group flex items-center justify-between px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                        <div class="flex items-center min-w-0">
                            <svg class="mr-3 sm:mr-4 h-4 w-4 sm:h-5 sm:w-5 text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7 2a1 1 0 011 1v1h3a1 1 0 110 2H9.578a18.87 18.87 0 01-1.724 4.78c.29.354.596.696.914 1.026a1 1 0 11-1.44 1.389c-.188-.196-.373-.396-.554-.6a19.098 19.098 0 01-3.107 3.567 1 1 0 01-1.334-1.49 17.087 17.087 0 003.13-3.733 18.992 18.992 0 01-1.487-2.494 1 1 0 111.79-.89c.234.47.489.928.764 1.372.417-.934.752-1.913.997-2.927H3a1 1 0 110-2h3V3a1 1 0 011-1zm6 6a1 1 0 01.894.553l2.991 5.982a.869.869 0 01.02.037l.99 1.98a1 1 0 11-1.79.895L15.383 16h-4.764l-.724 1.447a1 1 0 11-1.788-.894l.99-1.98.019-.038 2.99-5.982A1 1 0 0113 8zm-1.382 6h2.764L13 11.236 11.618 14z" clip-rule="evenodd"/>
                            </svg>
                            <span class="truncate">{{ __('admin.language') }}</span>
                            <span class="ml-1 sm:ml-2 text-xs bg-gray-200 dark:bg-gray-600 px-1.5 sm:px-2 py-0.5 sm:py-1 rounded uppercase">{{ app()->getLocale() }}</span>
                        </div>
                        <svg class="h-3 w-3 sm:h-4 sm:w-4 text-gray-400 transform transition-transform flex-shrink-0" :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-1 sm:mt-2 space-y-1 pl-3 sm:pl-4">
                        <a href="{{ url()->current() }}?lang=uz" class="group flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200 border-l-2 border-gray-200 dark:border-gray-600 hover:border-primary-500 {{ app()->getLocale() == 'uz' ? 'text-primary-600 dark:text-primary-400 border-primary-500' : '' }}">
                            <span class="mr-2 sm:mr-3 text-sm sm:text-lg">🇺🇿</span>
                            <span class="truncate">O'zbek</span>
                            @if(app()->getLocale() == 'uz')
                                <svg class="ml-auto h-3 w-3 sm:h-4 sm:w-4 text-primary-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </a>
                        <a href="{{ url()->current() }}?lang=en" class="group flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200 border-l-2 border-gray-200 dark:border-gray-600 hover:border-primary-500 {{ app()->getLocale() == 'en' ? 'text-primary-600 dark:text-primary-400 border-primary-500' : '' }}">
                            <span class="mr-2 sm:mr-3 text-sm sm:text-lg">🇺🇸</span>
                            <span class="truncate">English</span>
                            @if(app()->getLocale() == 'en')
                                <svg class="ml-auto h-3 w-3 sm:h-4 sm:w-4 text-primary-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </a>
                        <a href="{{ url()->current() }}?lang=ru" class="group flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200 border-l-2 border-gray-200 dark:border-gray-600 hover:border-primary-500 {{ app()->getLocale() == 'ru' ? 'text-primary-600 dark:text-primary-400 border-primary-500' : '' }}">
                            <span class="mr-2 sm:mr-3 text-sm sm:text-lg">🇷🇺</span>
                            <span class="truncate">Русский</span>
                            @if(app()->getLocale() == 'ru')
                                <svg class="ml-auto h-3 w-3 sm:h-4 sm:w-4 text-primary-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </a>
                    </div>
                </div>

                <!-- Settings -->
                <a href="#" class="group flex items-center px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                    <svg class="mr-3 sm:mr-4 h-4 w-4 sm:h-5 sm:w-5 text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                    <span class="truncate">{{ __('admin.settings') }}</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Logout -->
    <div class="flex-shrink-0 p-3 sm:p-4 border-t border-gray-200 dark:border-gray-700">
        <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full group flex items-center px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200 border border-red-200 dark:border-red-800 hover:border-red-300 dark:hover:border-red-700">
                <svg class="mr-2 sm:mr-3 h-4 w-4 sm:h-5 sm:w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                </svg>
                <span class="truncate">{{ __('admin.logout') }}</span>
            </button>
        </form>
    </div>
