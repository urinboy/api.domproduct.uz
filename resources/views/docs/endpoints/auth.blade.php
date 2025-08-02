@extends('docs.layout')

@section('title', $title)

@section('breadcrumb')
<div class="breadcrumb">
    <a href="{{ route('docs.index') }}" data-translate="api-documentation">API Documentation</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('docs.endpoints', 'auth') }}" data-translate="auth-endpoints">Authentication Endpoints</a>
</div>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title" data-translate="auth-endpoints-title">🔐 Authentication Endpoints</h1>
    <p class="page-description" data-translate="auth-endpoints-desc">
        Complete reference for all authentication-related API endpoints including registration,
        login, logout, token refresh, and user profile management.
    </p>
</div>

<!-- Quick Navigation -->
<div class="quick-nav">
    <h3 data-translate="quick-nav-title">🚀 Quick Navigation</h3>
    <div class="nav-cards">
        <a href="#register" class="nav-card">
            <i class="fas fa-user-plus"></i>
            <span data-translate="register-endpoint">Register</span>
        </a>
        <a href="#login" class="nav-card">
            <i class="fas fa-sign-in-alt"></i>
            <span data-translate="login-endpoint">Login</span>
        </a>
        <a href="#user-profile" class="nav-card">
            <i class="fas fa-user"></i>
            <span data-translate="profile-endpoint">User Profile</span>
        </a>
        <a href="#refresh-token" class="nav-card">
            <i class="fas fa-sync-alt"></i>
            <span data-translate="refresh-endpoint">Refresh Token</span>
        </a>
        <a href="#logout" class="nav-card">
            <i class="fas fa-sign-out-alt"></i>
            <span data-translate="logout-endpoint">Logout</span>
        </a>
        <a href="#change-password" class="nav-card">
            <i class="fas fa-key"></i>
            <span data-translate="change-password-endpoint">Change Password</span>
        </a>
    </div>
</div>

<!-- Register Endpoint -->
<div id="register" class="endpoint-section">
    <h2 data-translate="register-title">📝 User Registration</h2>
    <p data-translate="register-desc">Create a new user account and receive an authentication token.</p>

    <div class="endpoint-card">
        <div class="endpoint-header">
            <span class="method post">POST</span>
            <span class="endpoint-url">{{ $baseUrl }}/register</span>
            <span class="auth-badge none" data-translate="auth-none">No Auth Required</span>
        </div>

        <div class="endpoint-content">
            <!-- Request Parameters -->
            <div class="params-section">
                <h4 data-translate="request-parameters">📤 Request Parameters</h4>
                <div class="params-table">
                    <div class="param-header">
                        <span data-translate="param-name">Parameter</span>
                        <span data-translate="param-type">Type</span>
                        <span data-translate="param-required">Required</span>
                        <span data-translate="param-description">Description</span>
                    </div>
                    <div class="param-row">
                        <code>name</code>
                        <span class="type-string">string</span>
                        <span class="required">required</span>
                        <span data-translate="register-name-desc">Full name of the user (2-255 characters)</span>
                    </div>
                    <div class="param-row">
                        <code>email</code>
                        <span class="type-string">string</span>
                        <span class="required">required</span>
                        <span data-translate="register-email-desc">Valid email address (must be unique)</span>
                    </div>
                    <div class="param-row">
                        <code>password</code>
                        <span class="type-string">string</span>
                        <span class="required">required</span>
                        <span data-translate="register-password-desc">Password (minimum 8 characters)</span>
                    </div>
                    <div class="param-row">
                        <code>password_confirmation</code>
                        <span class="type-string">string</span>
                        <span class="required">required</span>
                        <span data-translate="register-password-conf-desc">Password confirmation (must match password)</span>
                    </div>
                </div>
            </div>

            <!-- Code Example -->
            <div class="code-example">
                <div class="code-tabs">
                    <button class="tab-btn active" onclick="showCode('register', 'curl')" data-translate="curl-tab">cURL</button>
                    <button class="tab-btn" onclick="showCode('register', 'javascript')" data-translate="javascript-tab">JavaScript</button>
                    <button class="tab-btn" onclick="showCode('register', 'php')" data-translate="php-tab">PHP</button>
                    <button class="tab-btn" onclick="showCode('register', 'python')" data-translate="python-tab">Python</button>
                </div>

                <div class="code-content">
                    <!-- cURL -->
                    <div id="register-curl" class="code-block active">
                        <div class="code-header">
                            <span data-translate="curl-example">cURL Example</span>
                            <button class="copy-btn" onclick="copyCode('register-curl-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="register-curl-code" class="language-bash">curl -X POST "{{ $baseUrl }}/register" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john.doe@example.com",
    "password": "secretpassword123",
    "password_confirmation": "secretpassword123"
  }'</code></pre>
                    </div>

                    <!-- JavaScript -->
                    <div id="register-javascript" class="code-block">
                        <div class="code-header">
                            <span data-translate="js-example">JavaScript Example</span>
                            <button class="copy-btn" onclick="copyCode('register-js-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="register-js-code" class="language-javascript">const response = await fetch('{{ $baseUrl }}/register', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    name: 'John Doe',
    email: 'john.doe@example.com',
    password: 'secretpassword123',
    password_confirmation: 'secretpassword123'
  })
});

const data = await response.json();
console.log(data);</code></pre>
                    </div>

                    <!-- PHP -->
                    <div id="register-php" class="code-block">
                        <div class="code-header">
                            <span data-translate="php-example">PHP Example</span>
                            <button class="copy-btn" onclick="copyCode('register-php-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="register-php-code" class="language-php">$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, '{{ $baseUrl }}/register');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'name' => 'John Doe',
    'email' => 'john.doe@example.com',
    'password' => 'secretpassword123',
    'password_confirmation' => 'secretpassword123'
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

$result = curl_exec($ch);
curl_close($ch);

$data = json_decode($result, true);</code></pre>
                    </div>

                    <!-- Python -->
                    <div id="register-python" class="code-block">
                        <div class="code-header">
                            <span data-translate="python-example">Python Example</span>
                            <button class="copy-btn" onclick="copyCode('register-python-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="register-python-code" class="language-python">import requests
import json

url = '{{ $baseUrl }}/register'
data = {
    'name': 'John Doe',
    'email': 'john.doe@example.com',
    'password': 'secretpassword123',
    'password_confirmation': 'secretpassword123'
}

headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
}

response = requests.post(url, json=data, headers=headers)
result = response.json()</code></pre>
                    </div>
                </div>
            </div>

            <!-- Response Examples -->
            <div class="response-section">
                <h4 data-translate="response-examples">📥 Response Examples</h4>

                <!-- Success Response -->
                <div class="response-example success">
                    <div class="response-header">
                        <span class="status-code success">201 Created</span>
                        <span data-translate="success-response">Success Response</span>
                    </div>
                    <pre><code class="language-json">{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 15,
      "name": "John Doe",
      "email": "john.doe@example.com",
      "email_verified_at": null,
      "avatar": null,
      "created_at": "2025-08-01T10:30:00.000000Z",
      "updated_at": "2025-08-01T10:30:00.000000Z"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0L2FwaS9yZWdpc3RlciIsImlhdCI6MTcyMjUxNDIwMCwiZXhwIjoxNzIyNTE3ODAwLCJuYmYiOjE3MjI1MTQyMDAsImp0aSI6IlZhTWRGcjhNVFNyYWI3cGgiLCJzdWIiOiIxNSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.Fh_tVbBxKLm4O9f5Xk7QvVzN8PcR9jWe3Lm6Np2Tj5s",
    "token_type": "bearer",
    "expires_in": 3600
  }
}</code></pre>
                </div>

                <!-- Validation Error -->
                <div class="response-example error">
                    <div class="response-header">
                        <span class="status-code error">422 Unprocessable Entity</span>
                        <span data-translate="validation-error">Validation Error</span>
                    </div>
                    <pre><code class="language-json">{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email has already been taken."
    ],
    "password": [
      "The password must be at least 8 characters."
    ],
    "password_confirmation": [
      "The password confirmation does not match."
    ]
  }
}</code></pre>
                </div>

                <!-- Server Error -->
                <div class="response-example error">
                    <div class="response-header">
                        <span class="status-code error">500 Internal Server Error</span>
                        <span data-translate="server-error">Server Error</span>
                    </div>
                    <pre><code class="language-json">{
  "success": false,
  "message": "Internal server error occurred. Please try again later.",
  "error_code": "REGISTRATION_FAILED"
}</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Login Endpoint -->
<div id="login" class="endpoint-section">
    <h2 data-translate="login-title">🔑 User Login</h2>
    <p data-translate="login-desc">Authenticate user with email and password to get access token.</p>

    <div class="endpoint-card">
        <div class="endpoint-header">
            <span class="method post">POST</span>
            <span class="endpoint-url">{{ $baseUrl }}/login</span>
            <span class="auth-badge none" data-translate="auth-none">No Auth Required</span>
        </div>

        <div class="endpoint-content">
            <!-- Request Parameters -->
            <div class="params-section">
                <h4 data-translate="request-parameters">📤 Request Parameters</h4>
                <div class="params-table">
                    <div class="param-header">
                        <span data-translate="param-name">Parameter</span>
                        <span data-translate="param-type">Type</span>
                        <span data-translate="param-required">Required</span>
                        <span data-translate="param-description">Description</span>
                    </div>
                    <div class="param-row">
                        <code>email</code>
                        <span class="type-string">string</span>
                        <span class="required">required</span>
                        <span data-translate="login-email-desc">User's email address</span>
                    </div>
                    <div class="param-row">
                        <code>password</code>
                        <span class="type-string">string</span>
                        <span class="required">required</span>
                        <span data-translate="login-password-desc">User's password</span>
                    </div>
                </div>
            </div>

            <!-- Code Example -->
            <div class="code-example">
                <div class="code-tabs">
                    <button class="tab-btn active" onclick="showCode('login', 'curl')" data-translate="curl-tab">cURL</button>
                    <button class="tab-btn" onclick="showCode('login', 'javascript')" data-translate="javascript-tab">JavaScript</button>
                    <button class="tab-btn" onclick="showCode('login', 'php')" data-translate="php-tab">PHP</button>
                    <button class="tab-btn" onclick="showCode('login', 'python')" data-translate="python-tab">Python</button>
                </div>

                <div class="code-content">
                    <!-- cURL -->
                    <div id="login-curl" class="code-block active">
                        <div class="code-header">
                            <span data-translate="curl-example">cURL Example</span>
                            <button class="copy-btn" onclick="copyCode('login-curl-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="login-curl-code" class="language-bash">curl -X POST "{{ $baseUrl }}/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john.doe@example.com",
    "password": "secretpassword123"
  }'</code></pre>
                    </div>

                    <!-- JavaScript -->
                    <div id="login-javascript" class="code-block">
                        <div class="code-header">
                            <span data-translate="js-example">JavaScript Example</span>
                            <button class="copy-btn" onclick="copyCode('login-js-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="login-js-code" class="language-javascript">const response = await fetch('{{ $baseUrl }}/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    email: 'john.doe@example.com',
    password: 'secretpassword123'
  })
});

const data = await response.json();

// Store token for future requests
if (data.success) {
  localStorage.setItem('auth_token', data.data.token);
}</code></pre>
                    </div>

                    <!-- PHP -->
                    <div id="login-php" class="code-block">
                        <div class="code-header">
                            <span data-translate="php-example">PHP Example</span>
                            <button class="copy-btn" onclick="copyCode('login-php-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="login-php-code" class="language-php">$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, '{{ $baseUrl }}/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'email' => 'john.doe@example.com',
    'password' => 'secretpassword123'
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

$result = curl_exec($ch);
curl_close($ch);

$data = json_decode($result, true);

// Store token in session
if ($data['success']) {
    session(['auth_token' => $data['data']['token']]);
}</code></pre>
                    </div>

                    <!-- Python -->
                    <div id="login-python" class="code-block">
                        <div class="code-header">
                            <span data-translate="python-example">Python Example</span>
                            <button class="copy-btn" onclick="copyCode('login-python-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="login-python-code" class="language-python">import requests
import json

url = '{{ $baseUrl }}/login'
data = {
    'email': 'john.doe@example.com',
    'password': 'secretpassword123'
}

headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
}

response = requests.post(url, json=data, headers=headers)
result = response.json()

# Store token for future requests
if result['success']:
    auth_token = result['data']['token']</code></pre>
                    </div>
                </div>
            </div>

            <!-- Response Examples -->
            <div class="response-section">
                <h4 data-translate="response-examples">📥 Response Examples</h4>

                <!-- Success Response -->
                <div class="response-example success">
                    <div class="response-header">
                        <span class="status-code success">200 OK</span>
                        <span data-translate="success-response">Success Response</span>
                    </div>
                    <pre><code class="language-json">{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 15,
      "name": "John Doe",
      "email": "john.doe@example.com",
      "email_verified_at": "2025-08-01T10:30:00.000000Z",
      "avatar": null,
      "created_at": "2025-08-01T10:30:00.000000Z",
      "updated_at": "2025-08-01T10:30:00.000000Z"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0L2FwaS9sb2dpbiIsImlhdCI6MTcyMjUxNDgwMCwiZXhwIjoxNzIyNTE4NDAwLCJuYmYiOjE3MjI1MTQ4MDAsImp0aSI6IlZhTWRGcjhNVFNyYWI3cGgiLCJzdWIiOiIxNSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.Fh_tVbBxKLm4O9f5Xk7QvVzN8PcR9jWe3Lm6Np2Tj5s",
    "token_type": "bearer",
    "expires_in": 3600
  }
}</code></pre>
                </div>

                <!-- Invalid Credentials -->
                <div class="response-example error">
                    <div class="response-header">
                        <span class="status-code error">401 Unauthorized</span>
                        <span data-translate="invalid-credentials">Invalid Credentials</span>
                    </div>
                    <pre><code class="language-json">{
  "success": false,
  "message": "Invalid email or password",
  "error_code": "INVALID_CREDENTIALS"
}</code></pre>
                </div>

                <!-- Validation Error -->
                <div class="response-example error">
                    <div class="response-header">
                        <span class="status-code error">422 Unprocessable Entity</span>
                        <span data-translate="validation-error">Validation Error</span>
                    </div>
                    <pre><code class="language-json">{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required."
    ],
    "password": [
      "The password field is required."
    ]
  }
}</code></pre>
                </div>
            </div>
        </div>
        </div>
</div>

<!-- User Profile Endpoint -->
<div id="user-profile" class="endpoint-section">
    <h2 data-translate="profile-title">👤 Get User Profile</h2>
    <p data-translate="profile-desc">Get authenticated user's profile information.</p>

    <div class="endpoint-card">
        <div class="endpoint-header">
            <span class="method get">GET</span>
            <span class="endpoint-url">{{ $baseUrl }}/user</span>
            <span class="auth-badge required" data-translate="auth-required">Auth Required</span>
        </div>

        <div class="endpoint-content">
            <!-- Code Example -->
            <div class="code-example">
                <div class="code-tabs">
                    <button class="tab-btn active" onclick="showCode('profile', 'curl')" data-translate="curl-tab">cURL</button>
                    <button class="tab-btn" onclick="showCode('profile', 'javascript')" data-translate="javascript-tab">JavaScript</button>
                    <button class="tab-btn" onclick="showCode('profile', 'php')" data-translate="php-tab">PHP</button>
                    <button class="tab-btn" onclick="showCode('profile', 'python')" data-translate="python-tab">Python</button>
                </div>

                <div class="code-content">
                    <!-- cURL -->
                    <div id="profile-curl" class="code-block active">
                        <div class="code-header">
                            <span data-translate="curl-example">cURL Example</span>
                            <button class="copy-btn" onclick="copyCode('profile-curl-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="profile-curl-code" class="language-bash">curl -X GET "{{ $baseUrl }}/user"
  -H "Accept: application/json"
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"</code></pre>
                    </div>

                    <!-- JavaScript -->
                    <div id="profile-javascript" class="code-block">
                        <div class="code-header">
                            <span data-translate="js-example">JavaScript Example</span>
                            <button class="copy-btn" onclick="copyCode('profile-js-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="profile-js-code" class="language-javascript">const token = localStorage.getItem('auth_token');

const response = await fetch('{{ $baseUrl }}/user', {
  method: 'GET',
  headers: {
    'Accept': 'application/json',
    'Authorization': `Bearer ${token}`
  }
});

const data = await response.json();</code></pre>
                    </div>

                    <!-- PHP -->
                    <div id="profile-php" class="code-block">
                        <div class="code-header">
                            <span data-translate="php-example">PHP Example</span>
                            <button class="copy-btn" onclick="copyCode('profile-php-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="profile-php-code" class="language-php">$token = session('auth_token');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, '{{ $baseUrl }}/user');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Authorization: Bearer ' . $token
]);

$result = curl_exec($ch);
curl_close($ch);

$data = json_decode($result, true);</code></pre>
                    </div>

                    <!-- Python -->
                    <div id="profile-python" class="code-block">
                        <div class="code-header">
                            <span data-translate="python-example">Python Example</span>
                            <button class="copy-btn" onclick="copyCode('profile-python-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="profile-python-code" class="language-python">import requests

token = "YOUR_ACCESS_TOKEN"
url = '{{ $baseUrl }}/user'

headers = {
    'Accept': 'application/json',
    'Authorization': f'Bearer {token}'
}

response = requests.get(url, headers=headers)
result = response.json()</code></pre>
                    </div>
                </div>
            </div>

            <!-- Response Examples -->
            <div class="response-section">
                <h4 data-translate="response-examples">📥 Response Examples</h4>

                <!-- Success Response -->
                <div class="response-example success">
                    <div class="response-header">
                        <span class="status-code success">200 OK</span>
                        <span data-translate="success-response">Success Response</span>
                    </div>
                    <pre><code class="language-json">{
  "success": true,
  "data": {
    "id": 15,
    "name": "John Doe",
    "email": "john.doe@example.com",
    "email_verified_at": "2025-08-01T10:30:00.000000Z",
    "avatar": "avatars/john-doe-avatar.jpg",
    "created_at": "2025-08-01T10:30:00.000000Z",
    "updated_at": "2025-08-01T10:30:00.000000Z"
  }
}</code></pre>
                </div>

                <!-- Unauthorized -->
                <div class="response-example error">
                    <div class="response-header">
                        <span class="status-code error">401 Unauthorized</span>
                        <span data-translate="unauthorized">Unauthorized</span>
                    </div>
                    <pre><code class="language-json">{
  "success": false,
  "message": "Unauthenticated.",
  "error_code": "TOKEN_INVALID"
}</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Refresh Token Endpoint -->
<div id="refresh-token" class="endpoint-section">
    <h2 data-translate="refresh-title">🔄 Refresh Access Token</h2>
    <p data-translate="refresh-desc">Refresh your access token to extend its validity period.</p>

    <div class="endpoint-card">
        <div class="endpoint-header">
            <span class="method post">POST</span>
            <span class="endpoint-url">{{ $baseUrl }}/refresh</span>
            <span class="auth-badge required" data-translate="auth-required">Auth Required</span>
        </div>

        <div class="endpoint-content">
            <!-- Code Example -->
            <div class="code-example">
                <div class="code-tabs">
                    <button class="tab-btn active" onclick="showCode('refresh', 'curl')" data-translate="curl-tab">cURL</button>
                    <button class="tab-btn" onclick="showCode('refresh', 'javascript')" data-translate="javascript-tab">JavaScript</button>
                    <button class="tab-btn" onclick="showCode('refresh', 'php')" data-translate="php-tab">PHP</button>
                    <button class="tab-btn" onclick="showCode('refresh', 'python')" data-translate="python-tab">Python</button>
                </div>

                <div class="code-content">
                    <!-- cURL -->
                    <div id="refresh-curl" class="code-block active">
                        <div class="code-header">
                            <span data-translate="curl-example">cURL Example</span>
                            <button class="copy-btn" onclick="copyCode('refresh-curl-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="refresh-curl-code" class="language-bash">curl -X POST "{{ $baseUrl }}/refresh"
  -H "Accept: application/json"
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"</code></pre>
                    </div>

                    <!-- JavaScript -->
                    <div id="refresh-javascript" class="code-block">
                        <div class="code-header">
                            <span data-translate="js-example">JavaScript Example</span>
                            <button class="copy-btn" onclick="copyCode('refresh-js-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="refresh-js-code" class="language-javascript">const token = localStorage.getItem('auth_token');

const response = await fetch('{{ $baseUrl }}/refresh', {
  method: 'POST',
  headers: {
    'Accept': 'application/json',
    'Authorization': `Bearer ${token}`
  }
});

const data = await response.json();

// Update stored token
if (data.success) {
  localStorage.setItem('auth_token', data.data.token);
}</code></pre>
                    </div>

                    <!-- PHP -->
                    <div id="refresh-php" class="code-block">
                        <div class="code-header">
                            <span data-translate="php-example">PHP Example</span>
                            <button class="copy-btn" onclick="copyCode('refresh-php-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="refresh-php-code" class="language-php">$token = session('auth_token');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, '{{ $baseUrl }}/refresh');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Authorization: Bearer ' . $token
]);

$result = curl_exec($ch);
curl_close($ch);

$data = json_decode($result, true);

// Update session token
if ($data['success']) {
    session(['auth_token' => $data['data']['token']]);
}</code></pre>
                    </div>

                    <!-- Python -->
                    <div id="refresh-python" class="code-block">
                        <div class="code-header">
                            <span data-translate="python-example">Python Example</span>
                            <button class="copy-btn" onclick="copyCode('refresh-python-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="refresh-python-code" class="language-python">import requests

token = "YOUR_ACCESS_TOKEN"
url = '{{ $baseUrl }}/refresh'

headers = {
    'Accept': 'application/json',
    'Authorization': f'Bearer {token}'
}

response = requests.post(url, headers=headers)
result = response.json()

# Update token
if result['success']:
    new_token = result['data']['token']</code></pre>
                    </div>
                </div>
            </div>

            <!-- Response Examples -->
            <div class="response-section">
                <h4 data-translate="response-examples">📥 Response Examples</h4>

                <!-- Success Response -->
                <div class="response-example success">
                    <div class="response-header">
                        <span class="status-code success">200 OK</span>
                        <span data-translate="success-response">Success Response</span>
                    </div>
                    <pre><code class="language-json">{
  "success": true,
  "message": "Token refreshed successfully",
  "data": {
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}</code></pre>
                </div>

                <!-- Token Expired -->
                <div class="response-example error">
                    <div class="response-header">
                        <span class="status-code error">401 Unauthorized</span>
                        <span data-translate="token-expired">Token Expired</span>
                    </div>
                    <pre><code class="language-json">{
  "success": false,
  "message": "Token has expired",
  "error_code": "TOKEN_EXPIRED"
}</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logout Endpoint -->
<div id="logout" class="endpoint-section">
    <h2 data-translate="logout-title">🚪 User Logout</h2>
    <p data-translate="logout-desc">Invalidate the current access token and log out the user.</p>

    <div class="endpoint-card">
        <div class="endpoint-header">
            <span class="method post">POST</span>
            <span class="endpoint-url">{{ $baseUrl }}/logout</span>
            <span class="auth-badge required" data-translate="auth-required">Auth Required</span>
        </div>

        <div class="endpoint-content">
            <!-- Code Example -->
            <div class="code-example">
                <div class="code-tabs">
                    <button class="tab-btn active" onclick="showCode('logout', 'curl')" data-translate="curl-tab">cURL</button>
                    <button class="tab-btn" onclick="showCode('logout', 'javascript')" data-translate="javascript-tab">JavaScript</button>
                    <button class="tab-btn" onclick="showCode('logout', 'php')" data-translate="php-tab">PHP</button>
                    <button class="tab-btn" onclick="showCode('logout', 'python')" data-translate="python-tab">Python</button>
                </div>

                <div class="code-content">
                    <!-- cURL -->
                    <div id="logout-curl" class="code-block active">
                        <div class="code-header">
                            <span data-translate="curl-example">cURL Example</span>
                            <button class="copy-btn" onclick="copyCode('logout-curl-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="logout-curl-code" class="language-bash">curl -X POST "{{ $baseUrl }}/logout"
  -H "Accept: application/json"
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"</code></pre>
                    </div>

                    <!-- JavaScript -->
                    <div id="logout-javascript" class="code-block">
                        <div class="code-header">
                            <span data-translate="js-example">JavaScript Example</span>
                            <button class="copy-btn" onclick="copyCode('logout-js-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="logout-js-code" class="language-javascript">const token = localStorage.getItem('auth_token');

const response = await fetch('{{ $baseUrl }}/logout', {
  method: 'POST',
  headers: {
    'Accept': 'application/json',
    'Authorization': `Bearer ${token}`
  }
});

const data = await response.json();

// Clear stored token
if (data.success) {
  localStorage.removeItem('auth_token');
  // Redirect to login page
  window.location.href = '/login';
}</code></pre>
                    </div>

                    <!-- PHP -->
                    <div id="logout-php" class="code-block">
                        <div class="code-header">
                            <span data-translate="php-example">PHP Example</span>
                            <button class="copy-btn" onclick="copyCode('logout-php-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="logout-php-code" class="language-php">$token = session('auth_token');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, '{{ $baseUrl }}/logout');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Authorization: Bearer ' . $token
]);

$result = curl_exec($ch);
curl_close($ch);

$data = json_decode($result, true);

// Clear session
if ($data['success']) {
    session()->forget('auth_token');
    return redirect('/login');
}</code></pre>
                    </div>

                    <!-- Python -->
                    <div id="logout-python" class="code-block">
                        <div class="code-header">
                            <span data-translate="python-example">Python Example</span>
                            <button class="copy-btn" onclick="copyCode('logout-python-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="logout-python-code" class="language-python">import requests

token = "YOUR_ACCESS_TOKEN"
url = '{{ $baseUrl }}/logout'

headers = {
    'Accept': 'application/json',
    'Authorization': f'Bearer {token}'
}

response = requests.post(url, headers=headers)
result = response.json()

# Clear token
if result['success']:
    token = None  # Clear token variable</code></pre>
                    </div>
                </div>
            </div>

            <!-- Response Examples -->
            <div class="response-section">
                <h4 data-translate="response-examples">📥 Response Examples</h4>

                <!-- Success Response -->
                <div class="response-example success">
                    <div class="response-header">
                        <span class="status-code success">200 OK</span>
                        <span data-translate="success-response">Success Response</span>
                    </div>
                    <pre><code class="language-json">{
  "success": true,
  "message": "Successfully logged out"
}</code></pre>
                </div>

                <!-- Already Logged Out -->
                <div class="response-example error">
                    <div class="response-header">
                        <span class="status-code error">401 Unauthorized</span>
                        <span data-translate="already-logged-out">Already Logged Out</span>
                    </div>
                    <pre><code class="language-json">{
  "success": false,
  "message": "Token is blacklisted",
  "error_code": "TOKEN_BLACKLISTED"
}</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Endpoint -->
<div id="change-password" class="endpoint-section">
    <h2 data-translate="change-password-title">🔐 Change Password</h2>
    <p data-translate="change-password-desc">Change the authenticated user's password.</p>

    <div class="endpoint-card">
        <div class="endpoint-header">
            <span class="method post">POST</span>
            <span class="endpoint-url">{{ $baseUrl }}/change-password</span>
            <span class="auth-badge required" data-translate="auth-required">Auth Required</span>
        </div>

        <div class="endpoint-content">
            <!-- Request Parameters -->
            <div class="params-section">
                <h4 data-translate="request-parameters">📤 Request Parameters</h4>
                <div class="params-table">
                    <div class="param-header">
                        <span data-translate="param-name">Parameter</span>
                        <span data-translate="param-type">Type</span>
                        <span data-translate="param-required">Required</span>
                        <span data-translate="param-description">Description</span>
                    </div>
                    <div class="param-row">
                        <code>current_password</code>
                        <span class="type-string">string</span>
                        <span class="required">required</span>
                        <span data-translate="current-password-desc">Current password for verification</span>
                    </div>
                    <div class="param-row">
                        <code>new_password</code>
                        <span class="type-string">string</span>
                        <span class="required">required</span>
                        <span data-translate="new-password-desc">New password (minimum 8 characters)</span>
                    </div>
                    <div class="param-row">
                        <code>new_password_confirmation</code>
                        <span class="type-string">string</span>
                        <span class="required">required</span>
                        <span data-translate="new-password-conf-desc">New password confirmation</span>
                    </div>
                </div>
            </div>

            <!-- Code Example -->
            <div class="code-example">
                <div class="code-tabs">
                    <button class="tab-btn active" onclick="showCode('changePassword', 'curl')" data-translate="curl-tab">cURL</button>
                    <button class="tab-btn" onclick="showCode('changePassword', 'javascript')" data-translate="javascript-tab">JavaScript</button>
                    <button class="tab-btn" onclick="showCode('changePassword', 'php')" data-translate="php-tab">PHP</button>
                    <button class="tab-btn" onclick="showCode('changePassword', 'python')" data-translate="python-tab">Python</button>
                </div>

                <div class="code-content">
                    <!-- cURL -->
                    <div id="changePassword-curl" class="code-block active">
                        <div class="code-header">
                            <span data-translate="curl-example">cURL Example</span>
                            <button class="copy-btn" onclick="copyCode('changePassword-curl-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="changePassword-curl-code" class="language-bash">curl -X POST "{{ $baseUrl }}/change-password"
  -H "Content-Type: application/json"
  -H "Accept: application/json"
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
  -d '{
    "current_password": "oldpassword123",
    "new_password": "newpassword123",
    "new_password_confirmation": "newpassword123"
  }'</code></pre>
                    </div>

                    <!-- JavaScript -->
                    <div id="changePassword-javascript" class="code-block">
                        <div class="code-header">
                            <span data-translate="js-example">JavaScript Example</span>
                            <button class="copy-btn" onclick="copyCode('changePassword-js-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="changePassword-js-code" class="language-javascript">const token = localStorage.getItem('auth_token');

const response = await fetch('{{ $baseUrl }}/change-password', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Bearer ${token}`
  },
  body: JSON.stringify({
    current_password: 'oldpassword123',
    new_password: 'newpassword123',
    new_password_confirmation: 'newpassword123'
  })
});

const data = await response.json();</code></pre>
                    </div>

                    <!-- PHP -->
                    <div id="changePassword-php" class="code-block">
                        <div class="code-header">
                            <span data-translate="php-example">PHP Example</span>
                            <button class="copy-btn" onclick="copyCode('changePassword-php-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="changePassword-php-code" class="language-php">$token = session('auth_token');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, '{{ $baseUrl }}/change-password');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'current_password' => 'oldpassword123',
    'new_password' => 'newpassword123',
    'new_password_confirmation' => 'newpassword123'
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer ' . $token
]);

$result = curl_exec($ch);
curl_close($ch);

$data = json_decode($result, true);</code></pre>
                    </div>

                    <!-- Python -->
                    <div id="changePassword-python" class="code-block">
                        <div class="code-header">
                            <span data-translate="python-example">Python Example</span>
                            <button class="copy-btn" onclick="copyCode('changePassword-python-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="changePassword-python-code" class="language-python">import requests

token = "YOUR_ACCESS_TOKEN"
url = '{{ $baseUrl }}/change-password'

data = {
    'current_password': 'oldpassword123',
    'new_password': 'newpassword123',
    'new_password_confirmation': 'newpassword123'
}

headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': f'Bearer {token}'
}

response = requests.post(url, json=data, headers=headers)
result = response.json()</code></pre>
                    </div>
                </div>
            </div>

            <!-- Response Examples -->
            <div class="response-section">
                <h4 data-translate="response-examples">📥 Response Examples</h4>

                <!-- Success Response -->
                <div class="response-example success">
                    <div class="response-header">
                        <span class="status-code success">200 OK</span>
                        <span data-translate="success-response">Success Response</span>
                    </div>
                    <pre><code class="language-json">{
  "success": true,
  "message": "Password changed successfully"
}</code></pre>
                </div>

                <!-- Wrong Current Password -->
                <div class="response-example error">
                    <div class="response-header">
                        <span class="status-code error">400 Bad Request</span>
                        <span data-translate="wrong-current-password">Wrong Current Password</span>
                    </div>
                    <pre><code class="language-json">{
  "success": false,
  "message": "Current password is incorrect",
  "error_code": "WRONG_PASSWORD"
}</code></pre>
                </div>

                <!-- Validation Error -->
                <div class="response-example error">
                    <div class="response-header">
                        <span class="status-code error">422 Unprocessable Entity</span>
                        <span data-translate="validation-error">Validation Error</span>
                    </div>
                    <pre><code class="language-json">{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "new_password": [
      "The new password must be at least 8 characters."
    ],
    "new_password_confirmation": [
      "The new password confirmation does not match."
    ]
  }
}</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .endpoint-section {
        margin: 4rem 0;
        padding: 2rem;
        background: var(--docs-content-bg);
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-lg);
        scroll-margin-top: 100px;
    }

    .endpoint-section h2 {
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-size: 1.875rem;
        border-bottom: 3px solid var(--primary-color);
        padding-bottom: 0.75rem;
    }

    .endpoint-section p {
        color: var(--text-secondary);
        font-size: 1.125rem;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    /* Quick Navigation */
    .quick-nav {
        margin: 3rem 0;
        padding: 2rem;
        background: linear-gradient(135deg, rgba(8, 124, 54, 0.05) 0%, rgba(8, 124, 54, 0.02) 100%);
        border: 1px solid rgba(8, 124, 54, 0.2);
        border-radius: var(--radius-lg);
    }

    .quick-nav h3 {
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        text-align: center;
    }

    .nav-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .nav-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        background: var(--docs-content-bg);
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-md);
        text-decoration: none;
        color: var(--text-primary);
        transition: var(--transition-fast);
        font-weight: 500;
    }

    .nav-card:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(8, 124, 54, 0.15);
    }

    .nav-card i {
        font-size: 1.25rem;
        width: 24px;
        text-align: center;
    }

    /* Endpoint Cards */
    .endpoint-card {
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        margin: 2rem 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .endpoint-header {
        background: var(--docs-bg);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-bottom: 1px solid var(--docs-border);
        flex-wrap: wrap;
    }

    .method {
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-weight: 700;
        font-size: 0.875rem;
        color: white;
        min-width: 70px;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .method.post {
        background: linear-gradient(135deg, #28a745, #20c997);
    }

    .method.get {
        background: linear-gradient(135deg, #007bff, #17a2b8);
    }

    .method.put {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: #000;
    }

    .method.delete {
        background: linear-gradient(135deg, #dc3545, #e83e8c);
    }

    .endpoint-url {
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 1rem;
        color: var(--text-primary);
        font-weight: 600;
        background: rgba(8, 124, 54, 0.1);
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        flex: 1;
        min-width: 200px;
    }

    .auth-badge {
        padding: 0.25rem 0.75rem;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .auth-badge.none {
        background: rgba(108, 117, 125, 0.2);
        color: var(--text-secondary);
        border: 1px solid rgba(108, 117, 125, 0.3);
    }

    .auth-badge.required {
        background: rgba(220, 53, 69, 0.1);
        color: var(--error-color);
        border: 1px solid rgba(220, 53, 69, 0.3);
    }

    .endpoint-content {
        padding: 2rem;
    }

    /* Parameters Section */
    .params-section {
        margin-bottom: 2rem;
    }

    .params-section h4 {
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .params-table {
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        background: var(--docs-content-bg);
    }

    .param-header {
        display: grid;
        grid-template-columns: 1fr 0.8fr 0.8fr 2fr;
        gap: 1rem;
        padding: 1rem 1.5rem;
        background: var(--docs-bg);
        border-bottom: 2px solid var(--docs-border);
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .param-row {
        display: grid;
        grid-template-columns: 1fr 0.8fr 0.8fr 2fr;
        gap: 1rem;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--docs-border);
        align-items: center;
    }

    .param-row:last-child {
        border-bottom: none;
    }

    .param-row:nth-child(even) {
        background: rgba(8, 124, 54, 0.02);
    }

    .param-row code {
        font-family: 'Monaco', 'Courier New', monospace;
        font-weight: 600;
        color: var(--primary-color);
        background: rgba(8, 124, 54, 0.1);
        padding: 0.25rem 0.5rem;
        border-radius: var(--radius-sm);
        font-size: 0.875rem;
    }

    .type-string {
        color: var(--info-color);
        font-weight: 500;
        font-size: 0.875rem;
        background: rgba(23, 162, 184, 0.1);
        padding: 0.25rem 0.5rem;
        border-radius: var(--radius-sm);
    }

    .required {
        color: var(--error-color);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        background: rgba(220, 53, 69, 0.1);
        padding: 0.25rem 0.5rem;
        border-radius: var(--radius-sm);
        text-align: center;
    }

    /* Code Examples */
    .code-example {
        margin: 2rem 0;
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        background: var(--docs-code-bg);
    }

    .code-tabs {
        display: flex;
        background: #2d3748;
        border-bottom: 1px solid #4a5568;
    }

    .tab-btn {
        background: none;
        border: none;
        color: #cbd5e0;
        padding: 1rem 1.5rem;
        cursor: pointer;
        font-weight: 500;
        transition: var(--transition-fast);
        border-bottom: 3px solid transparent;
    }

    .tab-btn:hover {
        background: rgba(203, 213, 224, 0.1);
        color: white;
    }

    .tab-btn.active {
        background: rgba(8, 124, 54, 0.2);
        color: white;
        border-bottom-color: var(--primary-color);
    }

    .code-content {
        position: relative;
    }

    .code-block {
        display: none;
    }

    .code-block.active {
        display: block;
    }

    .code-header {
        background: #1a202c;
        padding: 0.75rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #2d3748;
    }

    .code-header span {
        color: #cbd5e0;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .copy-btn {
        background: none;
        border: none;
        color: #cbd5e0;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: var(--radius-sm);
        transition: var(--transition-fast);
    }

    .copy-btn:hover {
        background: rgba(203, 213, 224, 0.1);
        color: var(--primary-color);
    }

    .code-block pre {
        margin: 0;
        padding: 1.5rem;
        background: #1a202c;
        overflow-x: auto;
    }

    .code-block code {
        color: #e2e8f0;
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 0.875rem;
        line-height: 1.6;
    }

    /* Response Section */
    .response-section {
        margin-top: 2rem;
    }

    .response-section h4 {
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .response-example {
        margin: 1.5rem 0;
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }

    .response-example.success {
        border-color: rgba(40, 167, 69, 0.3);
    }

    .response-example.error {
        border-color: rgba(220, 53, 69, 0.3);
    }

    .response-header {
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-bottom: 1px solid var(--docs-border);
    }

    .response-example.success .response-header {
        background: rgba(40, 167, 69, 0.05);
        border-bottom-color: rgba(40, 167, 69, 0.2);
    }

    .response-example.error .response-header {
        background: rgba(220, 53, 69, 0.05);
        border-bottom-color: rgba(220, 53, 69, 0.2);
    }

    .status-code {
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-weight: 700;
        font-size: 0.875rem;
        color: white;
        text-align: center;
        min-width: 140px;
    }

    .status-code.success {
        background: linear-gradient(135deg, #28a745, #20c997);
    }

    .status-code.error {
        background: linear-gradient(135deg, #dc3545, #e83e8c);
    }

    .response-example pre {
        margin: 0;
        padding: 1.5rem;
        background: var(--docs-code-bg);
        overflow-x: auto;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .endpoint-section {
            padding: 1rem;
        }

        .nav-cards {
            grid-template-columns: 1fr;
        }

        .endpoint-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .endpoint-url {
            width: 100%;
            word-break: break-all;
        }

        .param-header,
        .param-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
            text-align: left;
        }

        .param-header {
            display: none;
        }

        .param-row {
            padding: 1rem;
            border: 1px solid var(--docs-border);
            border-radius: var(--radius-md);
            margin-bottom: 0.5rem;
            background: var(--docs-content-bg);
        }

        .param-row:nth-child(even) {
            background: var(--docs-content-bg);
        }

        .code-tabs {
            flex-wrap: wrap;
        }

        .tab-btn {
            flex: 1;
            min-width: 120px;
        }

        .response-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .status-code {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Code switching functionality
    function showCode(endpoint, language) {
        // Hide all code blocks for this endpoint
        const codeBlocks = document.querySelectorAll(`#${endpoint}-curl, #${endpoint}-javascript, #${endpoint}-php, #${endpoint}-python`);
        codeBlocks.forEach(block => {
            block.classList.remove('active');
        });

        // Show selected code block
        const selectedBlock = document.getElementById(`${endpoint}-${language}`);
        if (selectedBlock) {
            selectedBlock.classList.add('active');
        }

        // Update tab buttons
        const tabContainer = selectedBlock.closest('.code-example');
        const tabButtons = tabContainer.querySelectorAll('.tab-btn');
        tabButtons.forEach(btn => {
            btn.classList.remove('active');
        });

        // Set active tab
        const activeTab = Array.from(tabButtons).find(btn =>
            btn.onclick.toString().includes(language)
        );
        if (activeTab) {
            activeTab.classList.add('active');
        }
    }

    // Copy code functionality
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
        }).catch(function() {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);

            // Show feedback
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

    // Smooth scrolling for navigation links
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.nav-card[href^="#"]');

        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });

    // Additional translations for auth endpoints
    const authEndpointsTranslations = {
        'en': {
            'auth-endpoints': 'Authentication Endpoints',
            'auth-endpoints-title': '🔐 Authentication Endpoints',
            'auth-endpoints-desc': 'Complete reference for all authentication-related API endpoints including registration, login, logout, token refresh, and user profile management.',
            'quick-nav-title': '🚀 Quick Navigation',
            'register-endpoint': 'Register',
            'login-endpoint': 'Login',
            'logout-endpoint': 'Logout',
            'refresh-endpoint': 'Refresh Token',
            'change-password-endpoint': 'Change Password',
            'endpoint-overview': 'Endpoint Overview',
            'request-parameters': 'Request Parameters',
            'successful-response': 'Successful Response',
            'error-responses': 'Error Responses',
            'example-request': 'Example Request',
            'user-registration': 'User Registration',
            'user-registration-desc': 'Creates a new user account and returns authentication token',
            'http-method': 'HTTP Method',
            'endpoint-url': 'Endpoint URL',
            'required-headers': 'Required Headers',
            'required-field': 'Required',
            'optional-field': 'Optional',
            'param-name': 'Parameter Name',
            'param-type': 'Type',
            'param-desc': 'Description',
            'name-param-desc': 'Full name of the user',
            'email-param-desc': 'Valid email address',
            'password-param-desc': 'Password (minimum 8 characters)',
            'password-conf-desc': 'Password confirmation (must match password)',
            'phone-param-desc': 'Phone number (optional)',
            'success-registration': 'Registration successful',
            'user-login': 'User Login',
            'user-login-desc': 'Authenticates user and returns access token',
            'email-or-phone': 'Email or Phone',
            'email-phone-desc': 'User email address or phone number',
            'password-field': 'Password',
            'user-password': 'User password',
            'remember-me': 'Remember Me',
            'remember-desc': 'Keep user logged in for extended period',
            'login-success': 'Login successful',
            'user-logout': 'User Logout',
            'user-logout-desc': 'Logs out user and invalidates current token',
            'auth-required': 'Authentication Required',
            'bearer-token': 'Bearer token in Authorization header',
            'logout-success': 'Logout successful',
            'token-refresh': 'Token Refresh',
            'token-refresh-desc': 'Refreshes access token using refresh token',
            'refresh-success': 'Token refreshed successfully',
            'change-password': 'Change Password',
            'change-password-desc': 'Changes user password (requires authentication)',
            'current-password': 'Current Password',
            'current-password-desc': 'User\'s current password',
            'new-password': 'New Password',
            'new-password-desc': 'New password (minimum 8 characters)',
            'new-password-conf': 'Confirm New Password',
            'new-password-conf-desc': 'Confirmation of new password',
            'password-changed': 'Password changed successfully',
            'validation-error': 'Validation Error',
            'email-already-exists': 'Email already exists',
            'phone-already-exists': 'Phone number already exists',
            'invalid-credentials': 'Invalid credentials',
            'unauthorized': 'Unauthorized',
            'token-expired': 'Token expired',
            'already-logged-out': 'Already logged out',
            'wrong-current-password': 'Wrong current password'
        },
        'uz': {
            'auth-endpoints': 'Autentifikatsiya Endpointlari',
            'auth-endpoints-title': '🔐 Autentifikatsiya Endpointlari',
            'auth-endpoints-desc': 'Ro\'yxatdan o\'tish, kirish, chiqish, token yangilash va foydalanuvchi profili boshqaruvi uchun barcha autentifikatsiya API endpointlarining to\'liq ma\'lumotnomasi.',
            'quick-nav-title': '🚀 Tezkor Navigatsiya',
            'register-endpoint': 'Ro\'yxatdan o\'tish',
            'login-endpoint': 'Kirish',
            'logout-endpoint': 'Chiqish',
            'refresh-endpoint': 'Tokenni yangilash',
            'change-password-endpoint': 'Parolni o\'zgartirish',
            'endpoint-overview': 'Endpoint ko\'rinishi',
            'request-parameters': 'So\'rov parametrlari',
            'successful-response': 'Muvaffaqiyatli javob',
            'error-responses': 'Xato javoblari',
            'example-request': 'So\'rov namunasi',
            'user-registration': 'Foydalanuvchi ro\'yxatdan o\'tkazish',
            'user-registration-desc': 'Yangi foydalanuvchi hisobini yaratadi va autentifikatsiya tokenini qaytaradi',
            'http-method': 'HTTP Metod',
            'endpoint-url': 'Endpoint URL',
            'required-headers': 'Majburiy sarlavhalar',
            'required-field': 'Majburiy',
            'optional-field': 'Ixtiyoriy',
            'param-name': 'Parametr nomi',
            'param-type': 'Turi',
            'param-desc': 'Tavsif',
            'name-param-desc': 'Foydalanuvchining to\'liq ismi',
            'email-param-desc': 'Haqiqiy email manzil',
            'password-param-desc': 'Parol (kamida 8 belgi)',
            'password-conf-desc': 'Parol tasdiqi (parol bilan mos kelishi kerak)',
            'phone-param-desc': 'Telefon raqami (ixtiyoriy)',
            'success-registration': 'Ro\'yxatdan o\'tish muvaffaqiyatli',
            'user-login': 'Foydalanuvchi kirishi',
            'user-login-desc': 'Foydalanuvchini autentifikatsiya qiladi va kirish tokenini qaytaradi',
            'email-or-phone': 'Email yoki Telefon',
            'email-phone-desc': 'Foydalanuvchi email manzili yoki telefon raqami',
            'password-field': 'Parol',
            'user-password': 'Foydalanuvchi paroli',
            'remember-me': 'Meni eslab qol',
            'remember-desc': 'Foydalanuvchini uzoq muddat davomida tizimda saqlash',
            'login-success': 'Kirish muvaffaqiyatli',
            'user-logout': 'Foydalanuvchi chiqishi',
            'user-logout-desc': 'Foydalanuvchini tizimdan chiqaradi va joriy tokenni bekor qiladi',
            'auth-required': 'Autentifikatsiya talab qilinadi',
            'bearer-token': 'Authorization sarlavhasida Bearer token',
            'logout-success': 'Chiqish muvaffaqiyatli',
            'token-refresh': 'Token yangilash',
            'token-refresh-desc': 'Yangilash tokenini ishlatib kirish tokenini yangilaydi',
            'refresh-success': 'Token muvaffaqiyatli yangilandi',
            'change-password': 'Parolni o\'zgartirish',
            'change-password-desc': 'Foydalanuvchi parolini o\'zgartiradi (autentifikatsiya talab qilinadi)',
            'current-password': 'Joriy parol',
            'current-password-desc': 'Foydalanuvchining joriy paroli',
            'new-password': 'Yangi parol',
            'new-password-desc': 'Yangi parol (kamida 8 belgi)',
            'new-password-conf': 'Yangi parolni tasdiqlash',
            'new-password-conf-desc': 'Yangi parolning tasdiqi',
            'password-changed': 'Parol muvaffaqiyatli o\'zgartirildi',
            'validation-error': 'Tekshirish xatosi',
            'email-already-exists': 'Email allaqachon mavjud',
            'phone-already-exists': 'Telefon raqami allaqachon mavjud',
            'invalid-credentials': 'Noto\'g\'ri ma\'lumotlar',
            'unauthorized': 'Ruxsat berilmagan',
            'token-expired': 'Token muddati tugagan',
            'already-logged-out': 'Allaqachon tizimdan chiqgan',
            'wrong-current-password': 'Noto\'g\'ri joriy parol'
        },
        'ru': {
            'auth-endpoints': 'Эндпоинты аутентификации',
            'auth-endpoints-title': '🔐 Эндпоинты аутентификации',
            'auth-endpoints-desc': 'Полная справка по всем API эндпоинтам аутентификации, включая регистрацию, вход, выход, обновление токена и управление профилем пользователя.',
            'quick-nav-title': '🚀 Быстрая навигация',
            'register-endpoint': 'Регистрация',
            'login-endpoint': 'Вход',
            'logout-endpoint': 'Выход',
            'refresh-endpoint': 'Обновить токен',
            'change-password-endpoint': 'Изменить пароль',
            'endpoint-overview': 'Обзор эндпоинта',
            'request-parameters': 'Параметры запроса',
            'successful-response': 'Успешный ответ',
            'error-responses': 'Ответы с ошибками',
            'example-request': 'Пример запроса',
            'user-registration': 'Регистрация пользователя',
            'user-registration-desc': 'Создает новую учетную запись пользователя и возвращает токен аутентификации',
            'http-method': 'HTTP метод',
            'endpoint-url': 'URL эндпоинта',
            'required-headers': 'Обязательные заголовки',
            'required-field': 'Обязательно',
            'optional-field': 'Необязательно',
            'param-name': 'Название параметра',
            'param-type': 'Тип',
            'param-desc': 'Описание',
            'name-param-desc': 'Полное имя пользователя',
            'email-param-desc': 'Действительный email адрес',
            'password-param-desc': 'Пароль (минимум 8 символов)',
            'password-conf-desc': 'Подтверждение пароля (должно совпадать с паролем)',
            'phone-param-desc': 'Номер телефона (необязательно)',
            'success-registration': 'Регистрация успешна',
            'user-login': 'Вход пользователя',
            'user-login-desc': 'Аутентифицирует пользователя и возвращает токен доступа',
            'email-or-phone': 'Email или Телефон',
            'email-phone-desc': 'Email адрес или номер телефона пользователя',
            'password-field': 'Пароль',
            'user-password': 'Пароль пользователя',
            'remember-me': 'Запомнить меня',
            'remember-desc': 'Оставить пользователя в системе на длительный период',
            'login-success': 'Вход выполнен успешно',
            'user-logout': 'Выход пользователя',
            'user-logout-desc': 'Выводит пользователя из системы и делает текущий токен недействительным',
            'auth-required': 'Требуется аутентификация',
            'bearer-token': 'Bearer токен в заголовке Authorization',
            'logout-success': 'Выход выполнен успешно',
            'token-refresh': 'Обновление токена',
            'token-refresh-desc': 'Обновляет токен доступа с использованием токена обновления',
            'refresh-success': 'Токен успешно обновлен',
            'change-password': 'Изменение пароля',
            'change-password-desc': 'Изменяет пароль пользователя (требуется аутентификация)',
            'current-password': 'Текущий пароль',
            'current-password-desc': 'Текущий пароль пользователя',
            'new-password': 'Новый пароль',
            'new-password-desc': 'Новый пароль (минимум 8 символов)',
            'new-password-conf': 'Подтвердить новый пароль',
            'new-password-conf-desc': 'Подтверждение нового пароля',
            'password-changed': 'Пароль успешно изменен',
            'validation-error': 'Ошибка валидации',
            'email-already-exists': 'Email уже существует',
            'phone-already-exists': 'Номер телефона уже существует',
            'invalid-credentials': 'Неверные учетные данные',
            'unauthorized': 'Неавторизован',
            'token-expired': 'Токен истек',
            'already-logged-out': 'Уже вышел из системы',
            'wrong-current-password': 'Неверный текущий пароль'
        }
    };

    // Merge with existing translations
    Object.keys(authEndpointsTranslations).forEach(lang => {
        if (translations[lang]) {
            Object.assign(translations[lang], authEndpointsTranslations[lang]);
        }
    });
</script>
@endpush

<!-- Navigation Component -->
@include('docs.components.page-navigation', $navigation)

@endsection

{{--
@push('scripts')
<script>

</script>
@endpush --}}
