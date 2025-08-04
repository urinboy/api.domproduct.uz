@extends('docs.layout')

@section('title', $title)

@section('breadcrumb')
<div class="breadcrumb">
    <a href="{{ route('docs.index') }}" data-translate="api-documentation">API Documentation</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('docs.endpoints', 'users') }}" data-translate="users-endpoints">User Management</a>
</div>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title" data-translate="users-endpoints-title">👥 User Management Endpoints</h1>
    <p class="page-description" data-translate="users-endpoints-desc">
        Comprehensive API endpoints for user profile management, avatar uploads,
        account settings, and user data operations.
    </p>
</div>

<!-- Quick Navigation -->
<div class="quick-nav">
    <h3 data-translate="quick-nav-title">🚀 Quick Navigation</h3>
    <div class="nav-cards">
        <a href="#get-profile" class="nav-card">
            <i class="fas fa-user"></i>
            <span data-translate="get-profile-nav">Get Profile</span>
        </a>
        <a href="#update-profile" class="nav-card">
            <i class="fas fa-user-edit"></i>
            <span data-translate="update-profile-nav">Update Profile</span>
        </a>
        <a href="#upload-avatar" class="nav-card">
            <i class="fas fa-camera"></i>
            <span data-translate="upload-avatar-nav">Upload Avatar</span>
        </a>
        <a href="#delete-avatar" class="nav-card">
            <i class="fas fa-trash-alt"></i>
            <span data-translate="delete-avatar-nav">Delete Avatar</span>
        </a>
        <a href="#change-email" class="nav-card">
            <i class="fas fa-envelope"></i>
            <span data-translate="change-email-nav">Change Email</span>
        </a>
        <a href="#delete-account" class="nav-card">
            <i class="fas fa-user-slash"></i>
            <span data-translate="delete-account-nav">Delete Account</span>
        </a>
    </div>
</div>

<!-- Get User Profile -->
<div id="get-profile" class="endpoint-section">
    <h2 data-translate="get-profile-title">👤 Get User Profile</h2>
    <p data-translate="get-profile-desc">Retrieve the authenticated user's complete profile information including personal details and preferences.</p>

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
                    <button class="tab-btn active" onclick="showCode('getProfile', 'curl')" data-translate="curl-tab">cURL</button>
                    <button class="tab-btn" onclick="showCode('getProfile', 'javascript')" data-translate="javascript-tab">JavaScript</button>
                    <button class="tab-btn" onclick="showCode('getProfile', 'php')" data-translate="php-tab">PHP</button>
                    <button class="tab-btn" onclick="showCode('getProfile', 'python')" data-translate="python-tab">Python</button>
                </div>

                <div class="code-content">
                    <!-- cURL -->
                    <div id="getProfile-curl" class="code-block active">
                        <div class="code-header">
                            <span data-translate="curl-example">cURL Example</span>
                            <button class="copy-btn" onclick="copyCode('getProfile-curl-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="getProfile-curl-code" class="language-bash">curl -X GET "{{ $baseUrl }}/user" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"</code></pre>
                    </div>

                    <!-- JavaScript -->
                    <div id="getProfile-javascript" class="code-block">
                        <div class="code-header">
                            <span data-translate="js-example">JavaScript Example</span>
                            <button class="copy-btn" onclick="copyCode('getProfile-js-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="getProfile-js-code" class="language-javascript">const token = localStorage.getItem('auth_token');

const response = await fetch('{{ $baseUrl }}/user', {
  method: 'GET',
  headers: {
    'Accept': 'application/json',
    'Authorization': `Bearer ${token}`
  }
});

const userData = await response.json();
console.log('User Profile:', userData.data);</code></pre>
                    </div>

                    <!-- PHP -->
                    <div id="getProfile-php" class="code-block">
                        <div class="code-header">
                            <span data-translate="php-example">PHP Example</span>
                            <button class="copy-btn" onclick="copyCode('getProfile-php-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="getProfile-php-code" class="language-php">$token = session('auth_token');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, '{{ $baseUrl }}/user');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Authorization: Bearer ' . $token
]);

$result = curl_exec($ch);
curl_close($ch);

$userData = json_decode($result, true);
echo 'User ID: ' . $userData['data']['id'];</code></pre>
                    </div>

                    <!-- Python -->
                    <div id="getProfile-python" class="code-block">
                        <div class="code-header">
                            <span data-translate="python-example">Python Example</span>
                            <button class="copy-btn" onclick="copyCode('getProfile-python-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="getProfile-python-code" class="language-python">import requests

token = "YOUR_ACCESS_TOKEN"
url = '{{ $baseUrl }}/user'

headers = {
    'Accept': 'application/json',
    'Authorization': f'Bearer {token}'
}

response = requests.get(url, headers=headers)
user_data = response.json()

print(f"User Name: {user_data['data']['name']}")</code></pre>
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
    "avatar": "avatars/john-doe-1722514200.jpg",
    "phone": "+1234567890",
    "address": "123 Main Street, City, Country",
    "date_of_birth": "1990-05-15",
    "gender": "male",
    "preferences": {
      "language": "en",
      "timezone": "UTC",
      "notifications": {
        "email": true,
        "push": true,
        "sms": false
      }
    },
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

<!-- Update User Profile -->
<div id="update-profile" class="endpoint-section">
    <h2 data-translate="update-profile-title">✏️ Update User Profile</h2>
    <p data-translate="update-profile-desc">Update the authenticated user's profile information including personal details and preferences.</p>

    <div class="endpoint-card">
        <div class="endpoint-header">
            <span class="method put">PUT</span>
            <span class="endpoint-url">{{ $baseUrl }}/user</span>
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
                        <code>name</code>
                        <span class="type-string">string</span>
                        <span class="optional">optional</span>
                        <span data-translate="name-param-desc">User's full name (2-255 characters)</span>
                    </div>
                    <div class="param-row">
                        <code>phone</code>
                        <span class="type-string">string</span>
                        <span class="optional">optional</span>
                        <span data-translate="phone-param-desc">User's phone number</span>
                    </div>
                    <div class="param-row">
                        <code>address</code>
                        <span class="type-string">string</span>
                        <span class="optional">optional</span>
                        <span data-translate="address-param-desc">User's address</span>
                    </div>
                    <div class="param-row">
                        <code>date_of_birth</code>
                        <span class="type-date">date</span>
                        <span class="optional">optional</span>
                        <span data-translate="dob-param-desc">Date of birth (YYYY-MM-DD format)</span>
                    </div>
                    <div class="param-row">
                        <code>gender</code>
                        <span class="type-string">string</span>
                        <span class="optional">optional</span>
                        <span data-translate="gender-param-desc">Gender (male, female, other)</span>
                    </div>
                </div>
            </div>

            <!-- Code Example -->
            <div class="code-example">
                <div class="code-tabs">
                    <button class="tab-btn active" onclick="showCode('updateProfile', 'curl')" data-translate="curl-tab">cURL</button>
                    <button class="tab-btn" onclick="showCode('updateProfile', 'javascript')" data-translate="javascript-tab">JavaScript</button>
                    <button class="tab-btn" onclick="showCode('updateProfile', 'php')" data-translate="php-tab">PHP</button>
                    <button class="tab-btn" onclick="showCode('updateProfile', 'python')" data-translate="python-tab">Python</button>
                </div>

                <div class="code-content">
                    <!-- cURL -->
                    <div id="updateProfile-curl" class="code-block active">
                        <div class="code-header">
                            <span data-translate="curl-example">cURL Example</span>
                            <button class="copy-btn" onclick="copyCode('updateProfile-curl-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="updateProfile-curl-code" class="language-bash">curl -X PUT "{{ $baseUrl }}/user" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -d '{
    "name": "John Smith",
    "phone": "+1234567890",
    "address": "456 Oak Avenue, New City, Country",
    "date_of_birth": "1990-05-15",
    "gender": "male"
  }'</code></pre>
                    </div>

                    <!-- JavaScript -->
                    <div id="updateProfile-javascript" class="code-block">
                        <div class="code-header">
                            <span data-translate="js-example">JavaScript Example</span>
                            <button class="copy-btn" onclick="copyCode('updateProfile-js-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="updateProfile-js-code" class="language-javascript">const token = localStorage.getItem('auth_token');

const profileData = {
  name: 'John Smith',
  phone: '+1234567890',
  address: '456 Oak Avenue, New City, Country',
  date_of_birth: '1990-05-15',
  gender: 'male'
};

const response = await fetch('{{ $baseUrl }}/user', {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Bearer ${token}`
  },
  body: JSON.stringify(profileData)
});

const result = await response.json();
console.log('Profile updated:', result);</code></pre>
                    </div>

                    <!-- PHP -->
                    <div id="updateProfile-php" class="code-block">
                        <div class="code-header">
                            <span data-translate="php-example">PHP Example</span>
                            <button class="copy-btn" onclick="copyCode('updateProfile-php-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="updateProfile-php-code" class="language-php">$token = session('auth_token');

$profileData = [
    'name' => 'John Smith',
    'phone' => '+1234567890',
    'address' => '456 Oak Avenue, New City, Country',
    'date_of_birth' => '1990-05-15',
    'gender' => 'male'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, '{{ $baseUrl }}/user');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($profileData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer ' . $token
]);

$result = curl_exec($ch);
curl_close($ch);

$response = json_decode($result, true);</code></pre>
                    </div>

                    <!-- Python -->
                    <div id="updateProfile-python" class="code-block">
                        <div class="code-header">
                            <span data-translate="python-example">Python Example</span>
                            <button class="copy-btn" onclick="copyCode('updateProfile-python-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="updateProfile-python-code" class="language-python">import requests

token = "YOUR_ACCESS_TOKEN"
url = '{{ $baseUrl }}/user'

profile_data = {
    'name': 'John Smith',
    'phone': '+1234567890',
    'address': '456 Oak Avenue, New City, Country',
    'date_of_birth': '1990-05-15',
    'gender': 'male'
}

headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': f'Bearer {token}'
}

response = requests.put(url, json=profile_data, headers=headers)
result = response.json()

print(f"Status: {result['success']}")</code></pre>
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
  "message": "Profile updated successfully",
  "data": {
    "id": 15,
    "name": "John Smith",
    "email": "john.doe@example.com",
    "phone": "+1234567890",
    "address": "456 Oak Avenue, New City, Country",
    "date_of_birth": "1990-05-15",
    "gender": "male",
    "updated_at": "2025-08-01T11:00:00.000000Z"
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
    "name": [
      "The name must be at least 2 characters."
    ],
    "date_of_birth": [
      "The date of birth does not match the format Y-m-d."
    ],
    "gender": [
      "The selected gender is invalid."
    ]
  }
}</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Avatar -->
<div id="upload-avatar" class="endpoint-section">
    <h2 data-translate="upload-avatar-title">📸 Upload User Avatar</h2>
    <p data-translate="upload-avatar-desc">Upload or update the authenticated user's profile avatar image.</p>

    <div class="endpoint-card">
        <div class="endpoint-header">
            <span class="method post">POST</span>
            <span class="endpoint-url">{{ $baseUrl }}/user/avatar</span>
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
                        <code>avatar</code>
                        <span class="type-file">file</span>
                        <span class="required">required</span>
                        <span data-translate="avatar-param-desc">Image file (jpg, jpeg, png, gif | max: 2MB)</span>
                    </div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="notes-section">
                <h4 data-translate="important-notes">⚠️ Important Notes</h4>
                <ul class="notes-list">
                    <li data-translate="avatar-note1">Content-Type must be <code>multipart/form-data</code></li>
                    <li data-translate="avatar-note2">Maximum file size: 2MB</li>
                    <li data-translate="avatar-note3">Supported formats: JPG, JPEG, PNG, GIF</li>
                    <li data-translate="avatar-note4">Image will be automatically resized to 300x300px</li>
                </ul>
            </div>

            <!-- Code Example -->
            <div class="code-example">
                <div class="code-tabs">
                    <button class="tab-btn active" onclick="showCode('uploadAvatar', 'curl')" data-translate="curl-tab">cURL</button>
                    <button class="tab-btn" onclick="showCode('uploadAvatar', 'javascript')" data-translate="javascript-tab">JavaScript</button>
                    <button class="tab-btn" onclick="showCode('uploadAvatar', 'php')" data-translate="php-tab">PHP</button>
                    <button class="tab-btn" onclick="showCode('uploadAvatar', 'python')" data-translate="python-tab">Python</button>
                </div>

                <div class="code-content">
                    <!-- cURL -->
                    <div id="uploadAvatar-curl" class="code-block active">
                        <div class="code-header">
                            <span data-translate="curl-example">cURL Example</span>
                            <button class="copy-btn" onclick="copyCode('uploadAvatar-curl-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="uploadAvatar-curl-code" class="language-bash">curl -X POST "{{ $baseUrl }}/user/avatar" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -F "avatar=@/path/to/your/avatar.jpg"</code></pre>
                    </div>

                    <!-- JavaScript -->
                    <div id="uploadAvatar-javascript" class="code-block">
                        <div class="code-header">
                            <span data-translate="js-example">JavaScript Example</span>
                            <button class="copy-btn" onclick="copyCode('uploadAvatar-js-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="uploadAvatar-js-code" class="language-javascript">const token = localStorage.getItem('auth_token');
const fileInput = document.getElementById('avatar-input');
const file = fileInput.files[0];

const formData = new FormData();
formData.append('avatar', file);

const response = await fetch('{{ $baseUrl }}/user/avatar', {
  method: 'POST',
  headers: {
    'Accept': 'application/json',
    'Authorization': `Bearer ${token}`
  },
  body: formData
});

const result = await response.json();
console.log('Avatar uploaded:', result);</code></pre>
                    </div>

                    <!-- PHP -->
                    <div id="uploadAvatar-php" class="code-block">
                        <div class="code-header">
                            <span data-translate="php-example">PHP Example</span>
                            <button class="copy-btn" onclick="copyCode('uploadAvatar-php-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="uploadAvatar-php-code" class="language-php">$token = session('auth_token');
$avatarPath = '/path/to/avatar.jpg';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, '{{ $baseUrl }}/user/avatar');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'avatar' => new CURLFile($avatarPath)
]);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Authorization: Bearer ' . $token
]);

$result = curl_exec($ch);
curl_close($ch);

$response = json_decode($result, true);</code></pre>
                    </div>

                    <!-- Python -->
                    <div id="uploadAvatar-python" class="code-block">
                        <div class="code-header">
                            <span data-translate="python-example">Python Example</span>
                            <button class="copy-btn" onclick="copyCode('uploadAvatar-python-code')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <pre><code id="uploadAvatar-python-code" class="language-python">import requests

token = "YOUR_ACCESS_TOKEN"
url = '{{ $baseUrl }}/user/avatar'

headers = {
    'Accept': 'application/json',
    'Authorization': f'Bearer {token}'
}

# Open the image file
with open('/path/to/avatar.jpg', 'rb') as f:
    files = {'avatar': f}
    response = requests.post(url, files=files, headers=headers)

result = response.json()
print(f"Avatar URL: {result['data']['avatar_url']}")</code></pre>
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
  "message": "Avatar uploaded successfully",
  "data": {
    "avatar": "avatars/john-doe-1722514800.jpg",
    "avatar_url": "{{ config('app.url') }}/storage/avatars/john-doe-1722514800.jpg"
  }
}</code></pre>
                </div>

                <!-- File Validation Error -->
                <div class="response-example error">
                    <div class="response-header">
                        <span class="status-code error">422 Unprocessable Entity</span>
                        <span data-translate="validation-error">Validation Error</span>
                    </div>
                    <pre><code class="language-json">{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "avatar": [
      "The avatar must be an image.",
      "The avatar may not be greater than 2048 kilobytes."
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
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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

    .method.get {
        background: linear-gradient(135deg, #007bff, #17a2b8);
    }

    .method.post {
        background: linear-gradient(135deg, #28a745, #20c997);
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

    .type-string, .type-file, .type-date {
        font-weight: 500;
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
        border-radius: var(--radius-sm);
    }

    .type-string {
        color: var(--info-color);
        background: rgba(23, 162, 184, 0.1);
    }

    .type-file {
        color: #6f42c1;
        background: rgba(111, 66, 193, 0.1);
    }

    .type-date {
        color: #fd7e14;
        background: rgba(253, 126, 20, 0.1);
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

    .optional {
        color: var(--success-color);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        background: rgba(40, 167, 69, 0.1);
        padding: 0.25rem 0.5rem;
        border-radius: var(--radius-sm);
        text-align: center;
    }

    /* Notes Section */
    .notes-section {
        margin: 2rem 0;
        padding: 1.5rem;
        background: rgba(255, 193, 7, 0.1);
        border: 1px solid rgba(255, 193, 7, 0.3);
        border-left: 4px solid #ffc107;
        border-radius: var(--radius-md);
    }

    .notes-section h4 {
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-size: 1.125rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .notes-list {
        margin: 0;
        padding-left: 1.5rem;
        color: var(--text-secondary);
    }

    .notes-list li {
        margin-bottom: 0.5rem;
        line-height: 1.6;
    }

    .notes-list code {
        background: rgba(8, 124, 54, 0.1);
        color: var(--primary-color);
        padding: 0.25rem 0.5rem;
        border-radius: var(--radius-sm);
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 0.875rem;
    }

    /* Code Examples - Same as auth.blade.php */
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

    /* Response Section - Same as auth.blade.php */
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

    // Add translations for Users Endpoints page
    const usersEndpointsTranslations = {
        'en': {
            'users-endpoints': 'User Management',
            'users-endpoints-title': '👥 User Management Endpoints',
            'users-endpoints-desc': 'Comprehensive API endpoints for user profile management, avatar uploads, account settings, and user data operations.',
            'quick-nav-title': '🚀 Quick Navigation',
            'get-profile-nav': 'Get Profile',
            'update-profile-nav': 'Update Profile',
            'upload-avatar-nav': 'Upload Avatar',
            'delete-avatar-nav': 'Delete Avatar',
            'auth-required': 'Auth Required',
            'get-profile-title': '👤 Get User Profile',
            'get-profile-desc': 'Retrieve the authenticated user\'s complete profile information including personal details and preferences.',
            'update-profile-title': '✏️ Update User Profile',
            'update-profile-desc': 'Update the authenticated user\'s profile information including personal details and preferences.',
            'upload-avatar-title': '📸 Upload User Avatar',
            'upload-avatar-desc': 'Upload or update the authenticated user\'s profile avatar image.',
            'request-parameters': '📤 Request Parameters',
            'param-name': 'Parameter',
            'param-type': 'Type',
            'param-required': 'Required',
            'param-description': 'Description',
            'name-param-desc': 'User\'s full name (2-255 characters)',
            'phone-param-desc': 'User\'s phone number',
            'address-param-desc': 'User\'s address',
            'dob-param-desc': 'Date of birth (YYYY-MM-DD format)',
            'gender-param-desc': 'Gender (male, female, other)',
            'avatar-param-desc': 'Image file (jpg, jpeg, png, gif | max: 2MB)',
            'important-notes': '⚠️ Important Notes',
            'avatar-note1': 'Content-Type must be <code>multipart/form-data</code>',
            'avatar-note2': 'Maximum file size: 2MB',
            'avatar-note3': 'Supported formats: JPG, JPEG, PNG, GIF',
            'avatar-note4': 'Image will be automatically resized to 300x300px',
            'curl-tab': 'cURL',
            'javascript-tab': 'JavaScript',
            'php-tab': 'PHP',
            'python-tab': 'Python',
            'curl-example': 'cURL Example',
            'js-example': 'JavaScript Example',
            'php-example': 'PHP Example',
            'python-example': 'Python Example',
            'response-examples': '📥 Response Examples',
            'success-response': 'Success Response',
            'validation-error': 'Validation Error',
            'unauthorized': 'Unauthorized'
        },
        'uz': {
            'users-endpoints': 'Foydalanuvchi boshqaruvi',
            'users-endpoints-title': '👥 Foydalanuvchi boshqaruvi endpointlari',
            'users-endpoints-desc': 'Foydalanuvchi profili boshqaruvi, avatar yuklash, hisob sozlamalari va foydalanuvchi ma\'lumotlari operatsiyalari uchun keng API endpointlari.',
            'quick-nav-title': '🚀 Tezkor navigatsiya',
            'get-profile-nav': 'Profilni olish',
            'update-profile-nav': 'Profilni yangilash',
            'upload-avatar-nav': 'Avatar yuklash',
            'delete-avatar-nav': 'Avatarni o\'chirish',
            'auth-required': 'Autentifikatsiya talab',
            'get-profile-title': '👤 Foydalanuvchi profilini olish',
            'get-profile-desc': 'Autentifikatsiya qilingan foydalanuvchining to\'liq profil ma\'lumotlarini olish, shaxsiy tafsilotlar va afzalliklarni o\'z ichiga oladi.',
            'update-profile-title': '✏️ Foydalanuvchi profilini yangilash',
            'update-profile-desc': 'Autentifikatsiya qilingan foydalanuvchining profil ma\'lumotlarini yangilash, shaxsiy tafsilotlar va afzalliklarni o\'z ichiga oladi.',
            'upload-avatar-title': '📸 Foydalanuvchi avatarini yuklash',
            'upload-avatar-desc': 'Autentifikatsiya qilingan foydalanuvchining profil avatar rasmini yuklash yoki yangilash.',
            'request-parameters': '📤 So\'rov parametrlari',
            'param-name': 'Parametr',
            'param-type': 'Turi',
            'param-required': 'Majburiy',
            'param-description': 'Tavsif',
            'name-param-desc': 'Foydalanuvchining to\'liq ismi (2-255 ta belgi)',
            'phone-param-desc': 'Foydalanuvchining telefon raqami',
            'address-param-desc': 'Foydalanuvchining manzili',
            'dob-param-desc': 'Tug\'ilgan sana (YYYY-MM-DD formati)',
            'gender-param-desc': 'Jinsi (erkak, ayol, boshqa)',
            'avatar-param-desc': 'Rasm fayli (jpg, jpeg, png, gif | max: 2MB)',
            'important-notes': '⚠️ Muhim eslatmalar',
            'avatar-note1': 'Content-Type <code>multipart/form-data</code> bo\'lishi kerak',
            'avatar-note2': 'Maksimal fayl o\'lchami: 2MB',
            'avatar-note3': 'Qo\'llab-quvvatlanadigan formatlar: JPG, JPEG, PNG, GIF',
            'avatar-note4': 'Rasm avtomatik ravishda 300x300px ga o\'lchamlandi',
            'curl-tab': 'cURL',
            'javascript-tab': 'JavaScript',
            'php-tab': 'PHP',
            'python-tab': 'Python',
            'curl-example': 'cURL misoli',
            'js-example': 'JavaScript misoli',
            'php-example': 'PHP misoli',
            'python-example': 'Python misoli',
            'response-examples': '📥 Javob misollari',
            'success-response': 'Muvaffaqiyatli javob',
            'validation-error': 'Validatsiya xatosi',
            'unauthorized': 'Ruxsat berilmagan'
        },
        'ru': {
            'users-endpoints': 'Управление пользователями',
            'users-endpoints-title': '👥 Эндпоинты управления пользователями',
            'users-endpoints-desc': 'Комплексные эндпоинты API для управления профилем пользователя, загрузки аватаров, настроек аккаунта и операций с данными пользователя.',
            'quick-nav-title': '🚀 Быстрая навигация',
            'get-profile-nav': 'Получить профиль',
            'update-profile-nav': 'Обновить профиль',
            'upload-avatar-nav': 'Загрузить аватар',
            'delete-avatar-nav': 'Удалить аватар',
            'auth-required': 'Требуется аутентификация',
            'get-profile-title': '👤 Получить профиль пользователя',
            'get-profile-desc': 'Получить полную информацию о профиле аутентифицированного пользователя, включая личные данные и настройки.',
            'update-profile-title': '✏️ Обновить профиль пользователя',
            'update-profile-desc': 'Обновить информацию профиля аутентифицированного пользователя, включая личные данные и настройки.',
            'upload-avatar-title': '📸 Загрузить аватар пользователя',
            'upload-avatar-desc': 'Загрузить или обновить изображение аватара профиля аутентифицированного пользователя.',
            'request-parameters': '📤 Параметры запроса',
            'param-name': 'Параметр',
            'param-type': 'Тип',
            'param-required': 'Обязательный',
            'param-description': 'Описание',
            'name-param-desc': 'Полное имя пользователя (2-255 символов)',
            'phone-param-desc': 'Номер телефона пользователя',
            'address-param-desc': 'Адрес пользователя',
            'dob-param-desc': 'Дата рождения (формат YYYY-MM-DD)',
            'gender-param-desc': 'Пол (мужской, женский, другой)',
            'avatar-param-desc': 'Файл изображения (jpg, jpeg, png, gif | макс: 2MB)',
            'important-notes': '⚠️ Важные заметки',
            'avatar-note1': 'Content-Type должен быть <code>multipart/form-data</code>',
            'avatar-note2': 'Максимальный размер файла: 2MB',
            'avatar-note3': 'Поддерживаемые форматы: JPG, JPEG, PNG, GIF',
            'avatar-note4': 'Изображение будет автоматически изменено до 300x300px',
            'curl-tab': 'cURL',
            'javascript-tab': 'JavaScript',
            'php-tab': 'PHP',
            'python-tab': 'Python',
            'curl-example': 'Пример cURL',
            'js-example': 'Пример JavaScript',
            'php-example': 'Пример PHP',
            'python-example': 'Пример Python',
            'response-examples': '📥 Примеры ответов',
            'success-response': 'Успешный ответ',
            'validation-error': 'Ошибка валидации',
            'unauthorized': 'Неавторизован'
        }
    };

    // Merge with existing translations
    Object.keys(usersEndpointsTranslations).forEach(lang => {
        if (translations[lang]) {
            Object.assign(translations[lang], usersEndpointsTranslations[lang]);
        }
    });
</script>
@endpush

<!-- Navigation Component -->
@include('docs.components.page-navigation', $navigation)

@endsection
