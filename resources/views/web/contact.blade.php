@extends('web.layouts.app')

@section('title', 'Контакты')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Свяжитесь с нами</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            У вас есть вопросы, предложения или нужна помощь? Мы всегда готовы помочь вам!
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Contact Form -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Отправить сообщение</h2>

            <form id="contactForm" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Имя *</label>
                    <input type="text" id="name" name="name" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Введите ваше имя">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" id="email" name="email" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="your@email.com">
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Телефон</label>
                    <input type="tel" id="phone" name="phone"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="+998 90 123 45 67">
                </div>

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Тема *</label>
                    <select id="subject" name="subject" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Выберите тему</option>
                        <option value="general">Общий вопрос</option>
                        <option value="support">Техническая поддержка</option>
                        <option value="order">Вопрос по заказу</option>
                        <option value="partnership">Сотрудничество</option>
                        <option value="complaint">Жалоба</option>
                        <option value="suggestion">Предложение</option>
                    </select>
                </div>

                <!-- Message -->
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Сообщение *</label>
                    <textarea id="message" name="message" rows="6" required
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Напишите ваше сообщение..."></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors duration-300 font-medium">
                    Отправить сообщение
                </button>
            </form>
        </div>

        <!-- Contact Information -->
        <div class="space-y-8">
            <!-- Contact Details -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Контактная информация</h2>

                <div class="space-y-6">
                    <!-- Address -->
                    <div class="flex items-start gap-4">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Адрес</h3>
                            <p class="text-gray-600">г. Ташкент, ул. Амир Темур, 123<br>Бизнес-центр "DOM", 5 этаж</p>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="flex items-start gap-4">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Телефон</h3>
                            <p class="text-gray-600">
                                <a href="tel:+998712501234" class="hover:text-blue-600">+998 (71) 250-12-34</a><br>
                                <a href="tel:+998901234567" class="hover:text-blue-600">+998 (90) 123-45-67</a>
                            </p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start gap-4">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Email</h3>
                            <p class="text-gray-600">
                                <a href="mailto:info@domproduct.uz" class="hover:text-blue-600">info@domproduct.uz</a><br>
                                <a href="mailto:support@domproduct.uz" class="hover:text-blue-600">support@domproduct.uz</a>
                            </p>
                        </div>
                    </div>

                    <!-- Working Hours -->
                    <div class="flex items-start gap-4">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Часы работы</h3>
                            <div class="text-gray-600 space-y-1">
                                <p>Пн-Пт: 9:00 - 20:00</p>
                                <p>Суббота: 10:00 - 18:00</p>
                                <p>Воскресенье: 10:00 - 16:00</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Мы в социальных сетях</h2>

                <div class="flex gap-4">
                    <a href="#" class="bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition-colors duration-300">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                    <a href="#" class="bg-blue-800 text-white p-3 rounded-lg hover:bg-blue-900 transition-colors duration-300">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="bg-pink-600 text-white p-3 rounded-lg hover:bg-pink-700 transition-colors duration-300">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.987 11.987S24.014 18.607 24.014 11.987C24.014 5.367 18.647.001 12.017.001zM12.017 18.634c-3.662 0-6.647-2.984-6.647-6.647s2.984-6.647 6.647-6.647 6.647 2.984 6.647 6.647-2.985 6.647-6.647 6.647z"/>
                            <path d="M15.391 8.023c-1.111 0-2.014.903-2.014 2.014s.903 2.014 2.014 2.014 2.014-.903 2.014-2.014-.903-2.014-2.014-2.014z"/>
                            <path d="M12.017 7.075c-2.714 0-4.912 2.198-4.912 4.912s2.198 4.912 4.912 4.912 4.912-2.198 4.912-4.912-2.198-4.912-4.912-4.912zm0 8.067c-1.742 0-3.155-1.414-3.155-3.155s1.414-3.155 3.155-3.155 3.155 1.414 3.155 3.155-1.414 3.155-3.155 3.155z"/>
                        </svg>
                    </a>
                    <a href="#" class="bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600 transition-colors duration-300">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- FAQ Link -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-8 text-white">
                <h2 class="text-2xl font-semibold mb-4">Часто задаваемые вопросы</h2>
                <p class="mb-6">Возможно, ответ на ваш вопрос уже есть в нашем разделе FAQ</p>
                <a href="#" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors duration-300">
                    Перейти к FAQ
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Form validation
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value.trim();

    if (!name || !email || !subject || !message) {
        webUtils.showNotification('Пожалуйста, заполните все обязательные поля', 'error');
        return;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        webUtils.showNotification('Пожалуйста, введите корректный email адрес', 'error');
        return;
    }

    // Here you would typically send the form data to the server
    console.log('Form data:', { name, email, subject, message });
    webUtils.showNotification('Ваше сообщение отправлено! Мы свяжемся с вами в ближайшее время.', 'success');

    // Reset form
    this.reset();
});
</script>
@endsection
