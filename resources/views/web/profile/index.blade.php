@extends('web.layouts.app')

@section('title', 'Мой профиль')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Мой профиль</h1>
        <nav class="text-sm text-gray-600">
            <a href="{{ route('web.home') }}" class="hover:text-blue-600">Главная</a>
            <span class="mx-2">/</span>
            <span>Профиль</span>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <!-- User Avatar -->
                <div class="text-center mb-6">
                    <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                 alt="{{ auth()->user()->name }}"
                                 class="w-24 h-24 rounded-full object-cover">
                        @else
                            <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                    </div>
                    <h3 class="font-semibold text-gray-800">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
                </div>

                <!-- Navigation Menu -->
                <nav class="space-y-2">
                    <a href="#profile" class="profile-tab flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 active">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        Личные данные
                    </a>
                    <a href="#orders" class="profile-tab flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 8a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 12a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                        </svg>
                        Мои заказы
                    </a>
                    <a href="#wishlist" class="profile-tab flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                        Избранное
                    </a>
                    <a href="#addresses" class="profile-tab flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        Адреса доставки
                    </a>
                    <a href="#security" class="profile-tab flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        Безопасность
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Profile Information Tab -->
            <div id="profile-content" class="tab-content bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Личные данные</h2>

                <form action="{{ route('web.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Avatar Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Фото профиля</label>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center">
                                @if(auth()->user()->avatar)
                                    <img id="avatarPreview" src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                         alt="{{ auth()->user()->name }}"
                                         class="w-16 h-16 rounded-full object-cover">
                                @else
                                    <svg id="avatarIcon" class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden">
                                <button type="button" onclick="document.getElementById('avatar').click()"
                                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                    Изменить фото
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Имя</label>
                        <input type="text" id="name" name="name" value="{{ auth()->user()->name }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ auth()->user()->email }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Телефон</label>
                        <input type="tel" id="phone" name="phone" value="{{ auth()->user()->phone ?? '' }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Дата рождения</label>
                        <input type="date" id="birth_date" name="birth_date" value="{{ auth()->user()->birth_date ?? '' }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Пол</label>
                        <div class="flex gap-4">
                            <label class="flex items-center">
                                <input type="radio" name="gender" value="male"
                                       {{ (auth()->user()->gender ?? '') === 'male' ? 'checked' : '' }}
                                       class="mr-2">
                                Мужской
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="gender" value="female"
                                       {{ (auth()->user()->gender ?? '') === 'female' ? 'checked' : '' }}
                                       class="mr-2">
                                Женский
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                            Сохранить изменения
                        </button>
                    </div>
                </form>
            </div>

            <!-- Orders Tab -->
            <div id="orders-content" class="tab-content bg-white rounded-lg shadow-md p-6 hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Мои заказы</h2>

                <!-- Orders will be loaded here -->
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 8a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 12a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">У вас пока нет заказов</h3>
                    <p class="text-gray-500 mb-4">Начните покупки, чтобы увидеть ваши заказы здесь</p>
                    <a href="{{ route('web.products.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Перейти к покупкам
                    </a>
                </div>
            </div>

            <!-- Wishlist Tab -->
            <div id="wishlist-content" class="tab-content bg-white rounded-lg shadow-md p-6 hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Избранное</h2>

                <!-- Wishlist will be loaded here -->
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Ваш список избранного пуст</h3>
                    <p class="text-gray-500 mb-4">Добавляйте товары в избранное, чтобы не потерять их</p>
                    <a href="{{ route('web.products.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Просмотреть товары
                    </a>
                </div>
            </div>

            <!-- Addresses Tab -->
            <div id="addresses-content" class="tab-content bg-white rounded-lg shadow-md p-6 hidden">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Адреса доставки</h2>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Добавить адрес
                    </button>
                </div>

                <!-- Addresses will be loaded here -->
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Нет сохраненных адресов</h3>
                    <p class="text-gray-500 mb-4">Добавьте адрес доставки для быстрого оформления заказов</p>
                </div>
            </div>

            <!-- Security Tab -->
            <div id="security-content" class="tab-content bg-white rounded-lg shadow-md p-6 hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Безопасность</h2>

                <form action="{{ route('web.profile.change-password') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Текущий пароль</label>
                        <input type="password" id="current_password" name="current_password"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Новый пароль</label>
                        <input type="password" id="new_password" name="new_password"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Подтвердите новый пароль</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                            Изменить пароль
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Tab switching functionality
    const profileTabs = document.querySelectorAll('.profile-tab');
    const tabContents = document.querySelectorAll('.tab-content');

    profileTabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();

            // Remove active class from all tabs
            profileTabs.forEach(t => t.classList.remove('active', 'bg-blue-100', 'text-blue-600'));

            // Add active class to clicked tab
            this.classList.add('active', 'bg-blue-100', 'text-blue-600');

            // Hide all tab contents
            tabContents.forEach(content => content.classList.add('hidden'));

            // Show relevant tab content
            const targetId = this.getAttribute('href').substring(1) + '-content';
            const targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
        });
    });

    // Avatar preview functionality
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarIcon = document.getElementById('avatarIcon');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (avatarPreview) {
                        avatarPreview.src = e.target.result;
                    } else {
                        // Create new image element if preview doesn't exist
                        const img = document.createElement('img');
                        img.id = 'avatarPreview';
                        img.src = e.target.result;
                        img.className = 'w-16 h-16 rounded-full object-cover';
                        img.alt = 'Avatar Preview';

                        const container = avatarIcon.parentElement;
                        container.innerHTML = '';
                        container.appendChild(img);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endsection
