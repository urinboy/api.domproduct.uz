@extends('docs.layout')

@section('title', $title)

@section('breadcrumb')
<div class="breadcrumb">
    <a href="{{ route('docs.index') }}" data-translate="api-documentation">API Documentation</a>
    <i class="fas fa-chevron-right"></i>
    <span data-translate="quick-start">Quick Start</span>
</div>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title" data-translate="getting-started-title">🚀 Getting Started</h1>
    <p class="page-description" data-translate="getting-started-desc">
        Learn how to quickly start using the DOM Product API. This guide will walk you through
        the basic setup and your first API calls.
    </p>
</div>

<!-- Prerequisites -->
<div class="section">
    <h2 data-translate="prerequisites-title">📋 Prerequisites</h2>
    <div class="prerequisite-list">
        <div class="prerequisite-item">
            <div class="prerequisite-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="prerequisite-content">
                <h4 data-translate="prereq-account">Valid User Account</h4>
                <p data-translate="prereq-account-desc">You need a registered account on DOM Product platform</p>
            </div>
        </div>

        <div class="prerequisite-item">
            <div class="prerequisite-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="prerequisite-content">
                <h4 data-translate="prereq-tools">Development Tools</h4>
                <p data-translate="prereq-tools-desc">cURL, Postman, or any HTTP client for testing API calls</p>
            </div>
        </div>

        <div class="prerequisite-item">
            <div class="prerequisite-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="prerequisite-content">
                <h4 data-translate="prereq-knowledge">Basic Knowledge</h4>
                <p data-translate="prereq-knowledge-desc">Understanding of REST APIs and JSON format</p>
            </div>
        </div>
    </div>
</div>

<!-- Step 1: Authentication -->
<div class="section">
    <h2 data-translate="step1-title">🔐 Step 1: Authentication</h2>
    <p data-translate="step1-desc">First, you need to authenticate and get an access token.</p>

    <div class="step-content">
        <h3 data-translate="register-title">1.1 Register a New Account</h3>
        <p data-translate="register-desc">If you don't have an account, register first:</p>

        <div class="code-example">
            <div class="code-header">
                <span class="code-title">POST {{ $baseUrl }}/register</span>
                <button class="copy-btn" onclick="copyCode('register-curl')">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
            <pre><code id="register-curl" class="language-bash">curl -X POST "{{ $baseUrl }}/register" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'</code></pre>
        </div>

        <h3 data-translate="login-title">1.2 Login to Get Access Token</h3>
        <p data-translate="login-desc">Use your credentials to get an access token:</p>

        <div class="code-example">
            <div class="code-header">
                <span class="code-title">POST {{ $baseUrl }}/login</span>
                <button class="copy-btn" onclick="copyCode('login-curl')">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
            <pre><code id="login-curl" class="language-bash">curl -X POST "{{ $baseUrl }}/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'</code></pre>
        </div>

        <div class="response-example">
            <h4 data-translate="response">Response:</h4>
            <pre><code class="language-json">{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
  }
}</code></pre>
        </div>

        <div class="important-note">
            <div class="note-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="note-content">
                <h4 data-translate="important">Important!</h4>
                <p data-translate="token-security">Save the token securely. You'll need it for all authenticated API calls.</p>
            </div>
        </div>
    </div>
</div>

<!-- Step 2: Making API Calls -->
<div class="section">
    <h2 data-translate="step2-title">📡 Step 2: Making Your First API Call</h2>
    <p data-translate="step2-desc">Now that you have a token, you can make authenticated requests.</p>

    <div class="step-content">
        <h3 data-translate="first-call-title">2.1 Get User Profile</h3>
        <p data-translate="first-call-desc">Let's start with getting your user profile:</p>

        <div class="code-example">
            <div class="code-header">
                <span class="code-title">GET {{ $baseUrl }}/user</span>
                <button class="copy-btn" onclick="copyCode('profile-curl')">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
            <pre><code id="profile-curl" class="language-bash">curl -X GET "{{ $baseUrl }}/user" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"</code></pre>
        </div>

        <h3 data-translate="products-call-title">2.2 Get Products List</h3>
        <p data-translate="products-call-desc">Retrieve a list of available products:</p>

        <div class="code-example">
            <div class="code-header">
                <span class="code-title">GET {{ $baseUrl }}/products</span>
                <button class="copy-btn" onclick="copyCode('products-curl')">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
            <pre><code id="products-curl" class="language-bash">curl -X GET "{{ $baseUrl }}/products?page=1&limit=10" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"</code></pre>
        </div>
    </div>
</div>

<!-- Error Handling -->
<div class="section">
    <h2 data-translate="error-handling-title">❌ Error Handling</h2>
    <p data-translate="error-handling-desc">Understanding how to handle API errors:</p>

    <div class="error-codes">
        <div class="error-code">
            <div class="error-status">400</div>
            <div class="error-details">
                <h4 data-translate="error-400">Bad Request</h4>
                <p data-translate="error-400-desc">Invalid request data or missing required fields</p>
            </div>
        </div>

        <div class="error-code">
            <div class="error-status">401</div>
            <div class="error-details">
                <h4 data-translate="error-401">Unauthorized</h4>
                <p data-translate="error-401-desc">Invalid or missing authentication token</p>
            </div>
        </div>

        <div class="error-code">
            <div class="error-status">404</div>
            <div class="error-details">
                <h4 data-translate="error-404">Not Found</h4>
                <p data-translate="error-404-desc">Requested resource doesn't exist</p>
            </div>
        </div>

        <div class="error-code">
            <div class="error-status">422</div>
            <div class="error-details">
                <h4 data-translate="error-422">Validation Error</h4>
                <p data-translate="error-422-desc">Request data failed validation rules</p>
            </div>
        </div>
    </div>

    <div class="error-example">
        <h4 data-translate="error-response-example">Error Response Example:</h4>
        <pre><code class="language-json">{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}</code></pre>
    </div>
</div>

<!-- Next Steps -->
<div class="section">
    <h2 data-translate="next-steps-title">🎯 Next Steps</h2>
    <div class="next-steps-grid">
        <a href="{{ route('docs.authentication') }}" class="next-step-card">
            <div class="step-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h4 data-translate="learn-auth-detailed">Learn Authentication</h4>
            <p data-translate="learn-auth-detailed-desc">Detailed authentication guide with examples</p>
        </a>

        <a href="{{ route('docs.endpoints', 'products') }}" class="next-step-card">
            <div class="step-icon">
                <i class="fas fa-box"></i>
            </div>
            <h4 data-translate="explore-products">Explore Products API</h4>
            <p data-translate="explore-products-desc">Manage products, categories, and inventory</p>
        </a>

        <a href="{{ route('docs.api-tester') }}" class="next-step-card">
            <div class="step-icon">
                <i class="fas fa-flask"></i>
            </div>
            <h4 data-translate="try-api-tester">Try API Tester</h4>
            <p data-translate="try-api-tester-desc">Interactive tool to test API endpoints</p>
        </a>
    </div>
</div>

@push('styles')
<style>
    .section {
        margin: 3rem 0;
        padding: 2rem;
        background: var(--docs-content-bg);
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-lg);
    }

    .section h2 {
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        font-size: 1.75rem;
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 0.5rem;
    }

    /* Prerequisites */
    .prerequisite-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .prerequisite-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        background: rgba(8, 124, 54, 0.05);
        border-radius: var(--radius-md);
        border-left: 4px solid var(--primary-color);
    }

    .prerequisite-icon i {
        color: var(--success-color);
        font-size: 1.25rem;
    }

    .prerequisite-content h4 {
        color: var(--text-primary);
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }

    .prerequisite-content p {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin: 0;
    }

    /* Step Content */
    .step-content {
        margin-top: 1.5rem;
    }

    .step-content h3 {
        color: var(--text-primary);
        margin: 2rem 0 1rem;
        font-size: 1.25rem;
        border-left: 4px solid var(--primary-color);
        padding-left: 1rem;
    }

    /* Code Examples */
    .code-example {
        margin: 1.5rem 0;
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        background: var(--docs-code-bg);
    }

    .code-header {
        background: #2d3748;
        padding: 0.75rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #4a5568;
    }

    .code-title {
        color: #cbd5e0;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .copy-btn {
        background: none;
        border: none;
        color: #cbd5e0;
        cursor: pointer;
        padding: 0.25rem 0.5rem;
        border-radius: var(--radius-sm);
        transition: var(--transition-fast);
    }

    .copy-btn:hover {
        background: rgba(203, 213, 224, 0.1);
    }

    .response-example {
        margin-top: 1.5rem;
    }

    .response-example h4 {
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        font-size: 1.125rem;
    }

    .response-example pre {
        background: var(--docs-code-bg);
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-lg);
        padding: 1rem;
        overflow-x: auto;
    }

    /* Important Note */
    .important-note {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        background: rgba(255, 193, 7, 0.1);
        border: 1px solid var(--warning-color);
        border-radius: var(--radius-md);
        margin: 1.5rem 0;
    }

    .note-icon i {
        color: var(--warning-color);
        font-size: 1.25rem;
    }

    .note-content h4 {
        color: var(--warning-color);
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .note-content p {
        color: var(--text-secondary);
        margin: 0;
        font-size: 0.875rem;
    }

    /* Error Handling */
    .error-codes {
        display: grid;
        gap: 1rem;
        margin: 1.5rem 0;
    }

    .error-code {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: var(--docs-content-bg);
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-md);
    }

    .error-status {
        width: 60px;
        height: 60px;
        background: var(--error-color);
        color: white;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.125rem;
        flex-shrink: 0;
    }

    .error-details h4 {
        color: var(--text-primary);
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }

    .error-details p {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin: 0;
    }

    .error-example {
        margin-top: 2rem;
    }

    .error-example h4 {
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        font-size: 1.125rem;
    }

    .error-example pre {
        background: var(--docs-code-bg);
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-lg);
        padding: 1rem;
        overflow-x: auto;
    }

    /* Next Steps */
    .next-steps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .next-step-card {
        display: block;
        padding: 1.5rem;
        background: var(--docs-content-bg);
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-lg);
        text-decoration: none;
        transition: var(--transition-normal);
        color: inherit;
    }

    .next-step-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
        border-color: var(--primary-color);
    }

    .step-icon {
        width: 50px;
        height: 50px;
        background: rgba(8, 124, 54, 0.1);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .step-icon i {
        font-size: 1.5rem;
        color: var(--primary-color);
    }

    .next-step-card h4 {
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 1.125rem;
    }

    .next-step-card p {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin: 0;
        line-height: 1.6;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .section {
            padding: 1rem;
        }

        .prerequisite-item,
        .error-code {
            flex-direction: column;
            text-align: center;
        }

        .next-steps-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Add translations for Getting Started page
    const gettingStartedTranslations = {
        uz: {
            'getting-started-title': '🚀 Boshlash',
            'getting-started-desc': 'DOM Product API dan qanday qilib tez foydalanishni o\'rganing. Bu qo\'llanma sizga asosiy sozlash va birinchi API chaqiruvlarini o\'tkazishda yordam beradi.',
            'prerequisites-title': '📋 Oldindan Talab',
            'prereq-account': 'Yaroqli Foydalanuvchi Hisobi',
            'prereq-account-desc': 'DOM Product platformasida ro\'yxatdan o\'tgan hisobingiz bo\'lishi kerak',
            'prereq-tools': 'Ishlab Chiqish Vositalari',
            'prereq-tools-desc': 'API chaqiruvlarini test qilish uchun cURL, Postman yoki boshqa HTTP mijoz',
            'prereq-knowledge': 'Asosiy Bilim',
            'prereq-knowledge-desc': 'REST API va JSON formatini tushunish',
            'step1-title': '🔐 1-qadam: Autentifikatsiya',
            'step1-desc': 'Avval autentifikatsiyadan o\'tib, kirish tokenini olishingiz kerak.',
            'register-title': '1.1 Yangi Hisob Yaratish',
            'register-desc': 'Agar hisobingiz yo\'q bo\'lsa, avval ro\'yxatdan o\'ting:',
            'login-title': '1.2 Kirish Tokeni Olish',
            'login-desc': 'Hisobingiz ma\'lumotlaridan foydalanib kirish tokenini oling:',
            'response': 'Javob:',
            'important': 'Muhim!',
            'token-security': 'Tokenni xavfsiz saqlang. U barcha autentifikatsiya talab qilingan API chaqiruvlar uchun kerak bo\'ladi.',
            'step2-title': '📡 2-qadam: Birinchi API Chaqiruvi',
            'step2-desc': 'Endi sizda token bor, autentifikatsiya qilingan so\'rovlar yubora olasiz.',
            'first-call-title': '2.1 Foydalanuvchi Profilini Olish',
            'first-call-desc': 'Foydalanuvchi profilingizni olishdan boshlaylik:',
            'products-call-title': '2.2 Mahsulotlar Ro\'yxatini Olish',
            'products-call-desc': 'Mavjud mahsulotlar ro\'yxatini oling:',
            'error-handling-title': '❌ Xatoliklarni Boshqarish',
            'error-handling-desc': 'API xatoliklarini qanday boshqarishni tushunish:',
            'error-400': 'Noto\'g\'ri So\'rov',
            'error-400-desc': 'Noto\'g\'ri so\'rov ma\'lumotlari yoki kerakli maydonlar yo\'q',
            'error-401': 'Ruxsatsiz',
            'error-401-desc': 'Noto\'g\'ri yoki yo\'qolgan autentifikatsiya tokeni',
            'error-404': 'Topilmadi',
            'error-404-desc': 'So\'ralgan resurs mavjud emas',
            'error-422': 'Tasdiqlash Xatosi',
            'error-422-desc': 'So\'rov ma\'lumotlari tasdiqlash qoidalaridan o\'tmadi',
            'error-response-example': 'Xato Javobi Misoli:',
            'next-steps-title': '🎯 Keyingi Qadamlar',
            'learn-auth-detailed': 'Autentifikatsiyani O\'rganish',
            'learn-auth-detailed-desc': 'Misollar bilan batafsil autentifikatsiya qo\'llanmasi',
            'explore-products': 'Mahsulotlar API ni O\'rganish',
            'explore-products-desc': 'Mahsulotlar, kategoriyalar va inventarni boshqarish',
            'try-api-tester': 'API Testerni Sinab Ko\'ring',
            'try-api-tester-desc': 'API nuqtalarini test qilish uchun interaktiv vosita'
        },
        en: {
            'getting-started-title': '🚀 Getting Started',
            'getting-started-desc': 'Learn how to quickly start using the DOM Product API. This guide will walk you through the basic setup and your first API calls.',
            'prerequisites-title': '📋 Prerequisites',
            'prereq-account': 'Valid User Account',
            'prereq-account-desc': 'You need a registered account on DOM Product platform',
            'prereq-tools': 'Development Tools',
            'prereq-tools-desc': 'cURL, Postman, or any HTTP client for testing API calls',
            'prereq-knowledge': 'Basic Knowledge',
            'prereq-knowledge-desc': 'Understanding of REST APIs and JSON format',
            'step1-title': '🔐 Step 1: Authentication',
            'step1-desc': 'First, you need to authenticate and get an access token.',
            'register-title': '1.1 Register a New Account',
            'register-desc': 'If you don\'t have an account, register first:',
            'login-title': '1.2 Login to Get Access Token',
            'login-desc': 'Use your credentials to get an access token:',
            'response': 'Response:',
            'important': 'Important!',
            'token-security': 'Save the token securely. You\'ll need it for all authenticated API calls.',
            'step2-title': '📡 Step 2: Making Your First API Call',
            'step2-desc': 'Now that you have a token, you can make authenticated requests.',
            'first-call-title': '2.1 Get User Profile',
            'first-call-desc': 'Let\'s start with getting your user profile:',
            'products-call-title': '2.2 Get Products List',
            'products-call-desc': 'Retrieve a list of available products:',
            'error-handling-title': '❌ Error Handling',
            'error-handling-desc': 'Understanding how to handle API errors:',
            'error-400': 'Bad Request',
            'error-400-desc': 'Invalid request data or missing required fields',
            'error-401': 'Unauthorized',
            'error-401-desc': 'Invalid or missing authentication token',
            'error-404': 'Not Found',
            'error-404-desc': 'Requested resource doesn\'t exist',
            'error-422': 'Validation Error',
            'error-422-desc': 'Request data failed validation rules',
            'error-response-example': 'Error Response Example:',
            'next-steps-title': '🎯 Next Steps',
            'learn-auth-detailed': 'Learn Authentication',
            'learn-auth-detailed-desc': 'Detailed authentication guide with examples',
            'explore-products': 'Explore Products API',
            'explore-products-desc': 'Manage products, categories, and inventory',
            'try-api-tester': 'Try API Tester',
            'try-api-tester-desc': 'Interactive tool to test API endpoints'
        },
        ru: {
            'getting-started-title': '🚀 Начало работы',
            'getting-started-desc': 'Узнайте, как быстро начать использовать DOM Product API. Это руководство проведет вас через базовую настройку и ваши первые вызовы API.',
            'prerequisites-title': '📋 Предварительные требования',
            'prereq-account': 'Действительная учетная запись пользователя',
            'prereq-account-desc': 'Вам нужна зарегистрированная учетная запись на платформе DOM Product',
            'prereq-tools': 'Инструменты разработки',
            'prereq-tools-desc': 'cURL, Postman или любой HTTP-клиент для тестирования вызовов API',
            'prereq-knowledge': 'Базовые знания',
            'prereq-knowledge-desc': 'Понимание REST API и формата JSON',
            'step1-title': '🔐 Шаг 1: Аутентификация',
            'step1-desc': 'Сначала вам нужно аутентифицироваться и получить токен доступа.',
            'register-title': '1.1 Регистрация нового аккаунта',
            'register-desc': 'Если у вас нет аккаунта, сначала зарегистрируйтесь:',
            'login-title': '1.2 Вход для получения токена доступа',
            'login-desc': 'Используйте свои учетные данные для получения токена доступа:',
            'response': 'Ответ:',
            'important': 'Важно!',
            'token-security': 'Сохраните токен в безопасности. Он понадобится вам для всех аутентифицированных вызовов API.',
            'step2-title': '📡 Шаг 2: Выполнение первого вызова API',
            'step2-desc': 'Теперь, когда у вас есть токен, вы можете делать аутентифицированные запросы.',
            'first-call-title': '2.1 Получить профиль пользователя',
            'first-call-desc': 'Начнем с получения вашего профиля пользователя:',
            'products-call-title': '2.2 Получить список продуктов',
            'products-call-desc': 'Получить список доступных продуктов:',
            'error-handling-title': '❌ Обработка ошибок',
            'error-handling-desc': 'Понимание того, как обрабатывать ошибки API:',
            'error-400': 'Плохой запрос',
            'error-400-desc': 'Неверные данные запроса или отсутствуют обязательные поля',
            'error-401': 'Неавторизован',
            'error-401-desc': 'Неверный или отсутствующий токен аутентификации',
            'error-404': 'Не найдено',
            'error-404-desc': 'Запрашиваемый ресурс не существует',
            'error-422': 'Ошибка валидации',
            'error-422-desc': 'Данные запроса не прошли правила валидации',
            'error-response-example': 'Пример ответа с ошибкой:',
            'next-steps-title': '🎯 Следующие шаги',
            'learn-auth-detailed': 'Изучить аутентификацию',
            'learn-auth-detailed-desc': 'Подробное руководство по аутентификации с примерами',
            'explore-products': 'Изучить Products API',
            'explore-products-desc': 'Управление продуктами, категориями и инвентарем',
            'try-api-tester': 'Попробовать API Tester',
            'try-api-tester-desc': 'Интерактивный инструмент для тестирования конечных точек API'
        }
    };

    // Merge with existing translations
    Object.keys(gettingStartedTranslations).forEach(lang => {
        if (translations[lang]) {
            Object.assign(translations[lang], gettingStartedTranslations[lang]);
        }
    });

    function copyCode(elementId) {
        const element = document.getElementById(elementId);
        const text = element.textContent;

        navigator.clipboard.writeText(text).then(function() {
            // Show success feedback
            const btn = event.target.closest('.copy-btn');
            const icon = btn.querySelector('i');
            const originalClass = icon.className;

            icon.className = 'fas fa-check';
            btn.style.color = '#28a745';

            setTimeout(() => {
                icon.className = originalClass;
                btn.style.color = '';
            }, 2000);
        });
    }
</script>
@endpush

<!-- Navigation Component -->
@include('docs.components.page-navigation', $navigation)

@endsection

@push('scripts')
<script>
    // Copy code functionality
    function setupCopyButtons() {
        const copyButtons = document.querySelectorAll('.copy-code');

        copyButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const codeElement = btn.nextElementSibling.querySelector('code');
                const code = codeElement.textContent;

                navigator.clipboard.writeText(code).then(() => {
                    const icon = btn.querySelector('i');
                    const originalClass = icon.className;

                    icon.className = 'fas fa-check';
                    btn.style.color = '#28a745';

                    setTimeout(() => {
                        icon.className = originalClass;
                        btn.style.color = '';
                    }, 2000);
                });
            });
        });
    }

    // Initialize copy buttons when page loads
    document.addEventListener('DOMContentLoaded', setupCopyButtons);

    // Initialize copy buttons when language changes
    document.addEventListener('languageChanged', () => {
        setTimeout(() => {
            if (typeof Prism !== 'undefined') {
                Prism.highlightAll();
            }
            setupCopyButtons();
        }, 100);
    });

    function copyToClipboard(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const icon = btn.querySelector('i');
            const originalClass = icon.className;

            icon.className = 'fas fa-check';
            btn.style.color = '#28a745';

            setTimeout(() => {
                icon.className = originalClass;
                btn.style.color = '';
            }, 2000);
        });
    }
</script>
@endpush
