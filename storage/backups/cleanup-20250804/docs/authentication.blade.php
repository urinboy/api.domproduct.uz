@extends('docs.layout')

@section('title', $title)

@section('breadcrumb')
<div class="breadcrumb">
    <a href="{{ route('docs.index') }}" data-translate="api-documentation">API Documentation</a>
    <i class="fas fa-chevron-right"></i>
    <span data-translate="authentication">Authentication</span>
</div>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title" data-translate="auth-main-title">🔐 Authentication</h1>
    <p class="page-description" data-translate="auth-main-desc">
        Learn how to authenticate with the DOM Product API using JSON Web Tokens (JWT).
        All API endpoints require proper authentication except for registration and login.
    </p>
</div>

<!-- Authentication Overview -->
<div class="section">
    <h2 data-translate="auth-overview-title">🎯 Authentication Overview</h2>
    <p data-translate="auth-overview-desc">
        DOM Product API uses Bearer Token authentication with JWT (JSON Web Tokens).
        Once you obtain a token, you must include it in the Authorization header of all API requests.
    </p>

    <div class="auth-flow">
        <div class="auth-step">
            <div class="step-number">1</div>
            <div class="step-content">
                <h4 data-translate="auth-step1">Register/Login</h4>
                <p data-translate="auth-step1-desc">Create account or login with credentials</p>
            </div>
        </div>

        <div class="auth-arrow">→</div>

        <div class="auth-step">
            <div class="step-number">2</div>
            <div class="step-content">
                <h4 data-translate="auth-step2">Receive Token</h4>
                <p data-translate="auth-step2-desc">Get JWT token in response</p>
            </div>
        </div>

        <div class="auth-arrow">→</div>

        <div class="auth-step">
            <div class="step-number">3</div>
            <div class="step-content">
                <h4 data-translate="auth-step3">Use Token</h4>
                <p data-translate="auth-step3-desc">Include token in Authorization header</p>
            </div>
        </div>
    </div>
</div>

<!-- Registration -->
<div class="section">
    <h2 data-translate="registration-title">📝 User Registration</h2>
    <p data-translate="registration-desc">Create a new user account to start using the API.</p>

    <div class="endpoint-card">
        <div class="endpoint-header">
            <span class="method post">POST</span>
            <span class="endpoint-url">{{ $endpoints['register'] ?? '/api/register' }}</span>
        </div>

        <div class="endpoint-content">
            <h4 data-translate="request-body">Request Body</h4>
            <div class="params-table">
                <div class="param-row">
                    <div class="param-name">name</div>
                    <div class="param-type">string</div>
                    <div class="param-required">required</div>
                    <div class="param-desc" data-translate="param-name-desc">Full name of the user</div>
                </div>
                <div class="param-row">
                    <div class="param-name">email</div>
                    <div class="param-type">string</div>
                    <div class="param-required">required</div>
                    <div class="param-desc" data-translate="param-email-desc">Valid email address</div>
                </div>
                <div class="param-row">
                    <div class="param-name">password</div>
                    <div class="param-type">string</div>
                    <div class="param-required">required</div>
                    <div class="param-desc" data-translate="param-password-desc">Password (min 8 characters)</div>
                </div>
                <div class="param-row">
                    <div class="param-name">password_confirmation</div>
                    <div class="param-type">string</div>
                    <div class="param-required">required</div>
                    <div class="param-desc" data-translate="param-password-conf-desc">Password confirmation</div>
                </div>
            </div>

            <div class="code-example">
                <div class="code-header">
                    <span class="code-title" data-translate="example-request">Example Request</span>
                    <button class="copy-btn" onclick="copyCode('register-example')">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <pre><code id="register-example" class="language-bash">curl -X POST "{{ config('app.url') }}/api/register" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john.doe@example.com",
    "password": "secretpassword",
    "password_confirmation": "secretpassword"
  }'</code></pre>
            </div>

            <div class="response-example">
                <h4 data-translate="success-response">Success Response (201 Created)</h4>
                <pre><code class="language-json">{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john.doe@example.com",
      "email_verified_at": null,
      "created_at": "2025-08-01T10:00:00.000000Z",
      "updated_at": "2025-08-01T10:00:00.000000Z"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
  }
}</code></pre>
            </div>
        </div>
    </div>
</div>

<!-- Login -->
<div class="section">
    <h2 data-translate="login-title">🔑 User Login</h2>
    <p data-translate="login-desc">Authenticate with existing credentials to get an access token.</p>

    <div class="endpoint-card">
        <div class="endpoint-header">
            <span class="method post">POST</span>
            <span class="endpoint-url">{{ $endpoints['login'] ?? '/api/login' }}</span>
        </div>

        <div class="endpoint-content">
            <h4 data-translate="request-body">Request Body</h4>
            <div class="params-table">
                <div class="param-row">
                    <div class="param-name">email</div>
                    <div class="param-type">string</div>
                    <div class="param-required">required</div>
                    <div class="param-desc" data-translate="param-email-desc">Valid email address</div>
                </div>
                <div class="param-row">
                    <div class="param-name">password</div>
                    <div class="param-type">string</div>
                    <div class="param-required">required</div>
                    <div class="param-desc" data-translate="param-password-desc">User password</div>
                </div>
            </div>

            <div class="code-example">
                <div class="code-header">
                    <span class="code-title" data-translate="example-request">Example Request</span>
                    <button class="copy-btn" onclick="copyCode('login-example')">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <pre><code id="login-example" class="language-bash">curl -X POST "{{ config('app.url') }}/api/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john.doe@example.com",
    "password": "secretpassword"
  }'</code></pre>
            </div>

            <div class="response-example">
                <h4 data-translate="success-response">Success Response (200 OK)</h4>
                <pre><code class="language-json">{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john.doe@example.com",
      "email_verified_at": "2025-08-01T10:00:00.000000Z"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
  }
}</code></pre>
            </div>
        </div>
    </div>
</div>

<!-- Using Token -->
<div class="section">
    <h2 data-translate="using-token-title">🎫 Using Access Token</h2>
    <p data-translate="using-token-desc">
        After successful login or registration, use the received token in the Authorization header
        for all subsequent API requests.
    </p>

    <div class="token-usage">
        <h4 data-translate="header-format">Header Format</h4>
        <div class="code-block">
            <code>Authorization: Bearer {YOUR_ACCESS_TOKEN}</code>
        </div>

        <h4 data-translate="example-usage">Example Usage</h4>
        <div class="code-example">
            <div class="code-header">
                <span class="code-title" data-translate="authenticated-request">Authenticated Request Example</span>
                <button class="copy-btn" onclick="copyCode('token-usage-example')">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
            <pre><code id="token-usage-example" class="language-bash">curl -X GET "{{ config('app.url') }}/api/user" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."</code></pre>
        </div>
    </div>
</div>

<!-- Logout -->
<div class="section">
    <h2 data-translate="logout-title">🚪 User Logout</h2>
    <p data-translate="logout-desc">Invalidate the current access token.</p>

    <div class="endpoint-card">
        <div class="endpoint-header">
            <span class="method post">POST</span>
            <span class="endpoint-url">{{ $endpoints['logout'] ?? '/api/logout' }}</span>
        </div>

        <div class="endpoint-content">
            <div class="code-example">
                <div class="code-header">
                    <span class="code-title" data-translate="example-request">Example Request</span>
                    <button class="copy-btn" onclick="copyCode('logout-example')">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <pre><code id="logout-example" class="language-bash">curl -X POST "{{ config('app.url') }}/api/logout" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"</code></pre>
            </div>

            <div class="response-example">
                <h4 data-translate="success-response">Success Response (200 OK)</h4>
                <pre><code class="language-json">{
  "success": true,
  "message": "Successfully logged out"
}</code></pre>
            </div>
        </div>
    </div>
</div>

<!-- Token Refresh -->
<div class="section">
    <h2 data-translate="refresh-title">🔄 Token Refresh</h2>
    <p data-translate="refresh-desc">Refresh your access token to extend its validity.</p>

    <div class="endpoint-card">
        <div class="endpoint-header">
            <span class="method post">POST</span>
            <span class="endpoint-url">{{ $endpoints['refresh'] ?? '/api/refresh' }}</span>
        </div>

        <div class="endpoint-content">
            <div class="code-example">
                <div class="code-header">
                    <span class="code-title" data-translate="example-request">Example Request</span>
                    <button class="copy-btn" onclick="copyCode('refresh-example')">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <pre><code id="refresh-example" class="language-bash">curl -X POST "{{ config('app.url') }}/api/refresh" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"</code></pre>
            </div>

            <div class="response-example">
                <h4 data-translate="success-response">Success Response (200 OK)</h4>
                <pre><code class="language-json">{
  "success": true,
  "message": "Token refreshed successfully",
  "data": {
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "expires_in": 3600
  }
}</code></pre>
            </div>
        </div>
    </div>
</div>

<!-- Security Best Practices -->
<div class="section">
    <h2 data-translate="security-title">🛡️ Security Best Practices</h2>
    <div class="security-tips">
        <div class="security-tip">
            <div class="tip-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="tip-content">
                <h4 data-translate="security-tip1">Store Tokens Securely</h4>
                <p data-translate="security-tip1-desc">Never store tokens in plain text or expose them in client-side code</p>
            </div>
        </div>

        <div class="security-tip">
            <div class="tip-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="tip-content">
                <h4 data-translate="security-tip2">Token Expiration</h4>
                <p data-translate="security-tip2-desc">Tokens have a limited lifetime. Implement refresh logic in your application</p>
            </div>
        </div>

        <div class="security-tip">
            <div class="tip-icon">
                <i class="fas fa-lock"></i>
            </div>
            <div class="tip-content">
                <h4 data-translate="security-tip3">HTTPS Only</h4>
                <p data-translate="security-tip3-desc">Always use HTTPS in production to prevent token interception</p>
            </div>
        </div>

        <div class="security-tip">
            <div class="tip-icon">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <div class="tip-content">
                <h4 data-translate="security-tip4">Logout Properly</h4>
                <p data-translate="security-tip4-desc">Always call the logout endpoint to invalidate tokens when done</p>
            </div>
        </div>
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

    /* Auth Flow */
    .auth-flow {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        margin: 2rem 0;
        flex-wrap: wrap;
    }

    .auth-step {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        background: rgba(8, 124, 54, 0.05);
        border-radius: var(--radius-lg);
        border: 1px solid rgba(8, 124, 54, 0.2);
        flex: 1;
        min-width: 200px;
        max-width: 250px;
    }

    .auth-step .step-number {
        width: 40px;
        height: 40px;
        background: var(--primary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.125rem;
        flex-shrink: 0;
    }

    .auth-step .step-content h4 {
        color: var(--text-primary);
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }

    .auth-step .step-content p {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin: 0;
    }

    .auth-arrow {
        font-size: 1.5rem;
        color: var(--primary-color);
        font-weight: bold;
    }

    /* Endpoint Cards */
    .endpoint-card {
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        margin: 1.5rem 0;
    }

    .endpoint-header {
        background: var(--docs-bg);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-bottom: 1px solid var(--docs-border);
    }

    .method {
        padding: 0.25rem 0.75rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.875rem;
        color: white;
        min-width: 60px;
        text-align: center;
    }

    .method.post {
        background: #28a745;
    }

    .method.get {
        background: #007bff;
    }

    .method.put {
        background: #ffc107;
        color: #000;
    }

    .method.delete {
        background: #dc3545;
    }

    .endpoint-url {
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 0.875rem;
        color: var(--text-primary);
        font-weight: 500;
    }

    .endpoint-content {
        padding: 1.5rem;
    }

    .endpoint-content h4 {
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-size: 1.125rem;
    }

    /* Parameters Table */
    .params-table {
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-md);
        overflow: hidden;
        margin: 1rem 0;
    }

    .param-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 2fr;
        gap: 1rem;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--docs-border);
        align-items: center;
    }

    .param-row:last-child {
        border-bottom: none;
    }

    .param-row:nth-child(odd) {
        background: rgba(8, 124, 54, 0.02);
    }

    .param-name {
        font-family: 'Monaco', 'Courier New', monospace;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.875rem;
    }

    .param-type {
        color: var(--info-color);
        font-size: 0.875rem;
        font-weight: 500;
    }

    .param-required {
        color: var(--error-color);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .param-desc {
        color: var(--text-secondary);
        font-size: 0.875rem;
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

    /* Token Usage */
    .token-usage {
        margin-top: 1.5rem;
    }

    .token-usage h4 {
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        font-size: 1.125rem;
    }

    .code-block {
        background: var(--docs-code-bg);
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-md);
        padding: 1rem;
        margin: 1rem 0;
    }

    .code-block code {
        color: #61dafb;
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 0.875rem;
    }

    /* Security Tips */
    .security-tips {
        display: grid;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .security-tip {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        background: rgba(23, 162, 184, 0.05);
        border-radius: var(--radius-md);
        border-left: 4px solid var(--info-color);
    }

    .tip-icon {
        width: 40px;
        height: 40px;
        background: rgba(23, 162, 184, 0.1);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .tip-icon i {
        color: var(--info-color);
        font-size: 1.125rem;
    }

    .tip-content h4 {
        color: var(--text-primary);
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }

    .tip-content p {
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

        .auth-flow {
            flex-direction: column;
        }

        .auth-arrow {
            transform: rotate(90deg);
        }

        .param-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        .param-name,
        .param-type,
        .param-required {
            font-size: 0.75rem;
        }

        .endpoint-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .security-tip {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Add translations for Authentication page
    const authTranslations = {
        uz: {
            'auth-main-title': '🔐 Autentifikatsiya',
            'auth-main-desc': 'JSON Web Tokens (JWT) yordamida DOM Product API bilan qanday autentifikatsiya qilishni o\'rganing. Ro\'yxatdan o\'tish va kirish bundan mustasno, barcha API nuqtalari to\'g\'ri autentifikatsiyani talab qiladi.',
            'auth-overview-title': '🎯 Autentifikatsiya Umumiy Ko\'rinishi',
            'auth-overview-desc': 'DOM Product API JWT (JSON Web Tokens) bilan Bearer Token autentifikatsiyasidan foydalanadi. Token olgandan so\'ng, uni barcha API so\'rovlarining Authorization sarlavhasiga kiritishingiz kerak.',
            'auth-step1': 'Ro\'yxatdan o\'tish/Kirish',
            'auth-step1-desc': 'Hisob yaratish yoki ma\'lumotlar bilan kirish',
            'auth-step2': 'Token Olish',
            'auth-step2-desc': 'Javobda JWT tokenini olish',
            'auth-step3': 'Token Ishlatish',
            'auth-step3-desc': 'Authorization sarlavhasiga tokenni kiritish',
            'registration-title': '📝 Foydalanuvchi Ro\'yxatdan O\'tishi',
            'registration-desc': 'API dan foydalanishni boshlash uchun yangi foydalanuvchi hisobini yarating.',
            'request-body': 'So\'rov Tanasi',
            'param-name-desc': 'Foydalanuvchining to\'liq ismi',
            'param-email-desc': 'Yaroqli email manzil',
            'param-password-desc': 'Parol (kamida 8 ta belgi)',
            'param-password-conf-desc': 'Parol tasdiqlanishi',
            'example-request': 'So\'rov Misoli',
            'success-response': 'Muvaffaqiyatli Javob',
            'login-title': '🔑 Foydalanuvchi Kirishi',
            'login-desc': 'Kirish tokenini olish uchun mavjud ma\'lumotlar bilan autentifikatsiya qiling.',
            'using-token-title': '🎫 Kirish Tokenidan Foydalanish',
            'using-token-desc': 'Muvaffaqiyatli kirish yoki ro\'yxatdan o\'tgandan so\'ng, olingan tokenni keyingi barcha API so\'rovlarida Authorization sarlavhasida ishlating.',
            'header-format': 'Sarlavha Formati',
            'example-usage': 'Foydalanish Misoli',
            'authenticated-request': 'Autentifikatsiya qilingan so\'rov misoli',
            'logout-title': '🚪 Foydalanuvchi Chiqishi',
            'logout-desc': 'Joriy kirish tokenini bekor qiling.',
            'refresh-title': '🔄 Token Yangilash',
            'refresh-desc': 'Kirish tokeningizni amal qilish muddatini uzaytirish uchun yangilang.',
            'security-title': '🛡️ Xavfsizlik Eng Yaxshi Amaliyotlari',
            'security-tip1': 'Tokenlarni Xavfsiz Saqlang',
            'security-tip1-desc': 'Tokenlarni hech qachon oddiy matnda saqlamang yoki mijoz tomonidagi kodda ochiq qoldirmang',
            'security-tip2': 'Token Muddati',
            'security-tip2-desc': 'Tokenlarning cheklangan hayot muddati bor. Ilovangizda yangilash mantiqini amalga oshiring',
            'security-tip3': 'Faqat HTTPS',
            'security-tip3-desc': 'Token tutilishining oldini olish uchun ishlab chiqarishda doimo HTTPS dan foydalaning',
            'security-tip4': 'To\'g\'ri Chiqish',
            'security-tip4-desc': 'Ish tugaganda tokenlarni bekor qilish uchun doimo logout nuqtasini chaqiring'
        },
        en: {
            'auth-main-title': '🔐 Authentication',
            'auth-main-desc': 'Learn how to authenticate with the DOM Product API using JSON Web Tokens (JWT). All API endpoints require proper authentication except for registration and login.',
            'auth-overview-title': '🎯 Authentication Overview',
            'auth-overview-desc': 'DOM Product API uses Bearer Token authentication with JWT (JSON Web Tokens). Once you obtain a token, you must include it in the Authorization header of all API requests.',
            'auth-step1': 'Register/Login',
            'auth-step1-desc': 'Create account or login with credentials',
            'auth-step2': 'Receive Token',
            'auth-step2-desc': 'Get JWT token in response',
            'auth-step3': 'Use Token',
            'auth-step3-desc': 'Include token in Authorization header',
            'registration-title': '📝 User Registration',
            'registration-desc': 'Create a new user account to start using the API.',
            'request-body': 'Request Body',
            'param-name-desc': 'Full name of the user',
            'param-email-desc': 'Valid email address',
            'param-password-desc': 'Password (min 8 characters)',
            'param-password-conf-desc': 'Password confirmation',
            'example-request': 'Example Request',
            'success-response': 'Success Response',
            'login-title': '🔑 User Login',
            'login-desc': 'Authenticate with existing credentials to get an access token.',
            'using-token-title': '🎫 Using Access Token',
            'using-token-desc': 'After successful login or registration, use the received token in the Authorization header for all subsequent API requests.',
            'header-format': 'Header Format',
            'example-usage': 'Example Usage',
            'authenticated-request': 'Authenticated Request Example',
            'logout-title': '🚪 User Logout',
            'logout-desc': 'Invalidate the current access token.',
            'refresh-title': '🔄 Token Refresh',
            'refresh-desc': 'Refresh your access token to extend its validity.',
            'security-title': '🛡️ Security Best Practices',
            'security-tip1': 'Store Tokens Securely',
            'security-tip1-desc': 'Never store tokens in plain text or expose them in client-side code',
            'security-tip2': 'Token Expiration',
            'security-tip2-desc': 'Tokens have a limited lifetime. Implement refresh logic in your application',
            'security-tip3': 'HTTPS Only',
            'security-tip3-desc': 'Always use HTTPS in production to prevent token interception',
            'security-tip4': 'Logout Properly',
            'security-tip4-desc': 'Always call the logout endpoint to invalidate tokens when done'
        },
        ru: {
            'auth-main-title': '🔐 Аутентификация',
            'auth-main-desc': 'Узнайте, как аутентифицироваться с DOM Product API, используя JSON Web Tokens (JWT). Все конечные точки API требуют правильной аутентификации, за исключением регистрации и входа.',
            'auth-overview-title': '🎯 Обзор аутентификации',
            'auth-overview-desc': 'DOM Product API использует аутентификацию Bearer Token с JWT (JSON Web Tokens). После получения токена вы должны включить его в заголовок Authorization всех API запросов.',
            'auth-step1': 'Регистрация/Вход',
            'auth-step1-desc': 'Создать аккаунт или войти с учетными данными',
            'auth-step2': 'Получить токен',
            'auth-step2-desc': 'Получить JWT токен в ответе',
            'auth-step3': 'Использовать токен',
            'auth-step3-desc': 'Включить токен в заголовок Authorization',
            'registration-title': '📝 Регистрация пользователя',
            'registration-desc': 'Создайте новую учетную запись пользователя, чтобы начать использовать API.',
            'request-body': 'Тело запроса',
            'param-name-desc': 'Полное имя пользователя',
            'param-email-desc': 'Действительный адрес электронной почты',
            'param-password-desc': 'Пароль (минимум 8 символов)',
            'param-password-conf-desc': 'Подтверждение пароля',
            'example-request': 'Пример запроса',
            'success-response': 'Успешный ответ',
            'login-title': '🔑 Вход пользователя',
            'login-desc': 'Аутентифицируйтесь с существующими учетными данными, чтобы получить токен доступа.',
            'using-token-title': '🎫 Использование токена доступа',
            'using-token-desc': 'После успешного входа или регистрации используйте полученный токен в заголовке Authorization для всех последующих API запросов.',
            'header-format': 'Формат заголовка',
            'example-usage': 'Пример использования',
            'authenticated-request': 'Пример аутентифицированного запроса',
            'logout-title': '🚪 Выход пользователя',
            'logout-desc': 'Аннулировать текущий токен доступа.',
            'refresh-title': '🔄 Обновление токена',
            'refresh-desc': 'Обновите ваш токен доступа, чтобы продлить его срок действия.',
            'security-title': '🛡️ Лучшие практики безопасности',
            'security-tip1': 'Безопасное хранение токенов',
            'security-tip1-desc': 'Никогда не храните токены в открытом виде и не раскрывайте их в клиентском коде',
            'security-tip2': 'Срок действия токена',
            'security-tip2-desc': 'Токены имеют ограниченное время жизни. Реализуйте логику обновления в вашем приложении',
            'security-tip3': 'Только HTTPS',
            'security-tip3-desc': 'Всегда используйте HTTPS в продакшене для предотвращения перехвата токенов',
            'security-tip4': 'Правильный выход',
            'security-tip4-desc': 'Всегда вызывайте конечную точку logout для аннулирования токенов по завершении'
        }
    };

    // Merge with existing translations
    Object.keys(authTranslations).forEach(lang => {
        if (translations[lang]) {
            Object.assign(translations[lang], authTranslations[lang]);
        }
    });
</script>
@endpush

<!-- Navigation Component -->
@include('docs.components.page-navigation', $navigation)

@endsection

@push('scripts')
<script>
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
