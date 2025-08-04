@extends('docs.layout')

@section('title', $title)

@section('breadcrumb')
<div class="breadcrumb">
    <a href="{{ route('docs.index') }}" data-translate="api-documentation">API Documentation</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('docs.endpoints', 'categories') }}" data-translate="categories-endpoints">Categories API</a>
</div>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title" data-translate="categories-endpoints-title">📂 Categories API Endpoints</h1>
    <p class="page-description" data-translate="categories-endpoints-desc">
        Complete reference for category management API endpoints including listing,
        creating, updating, deleting categories and managing product categorization.
    </p>
</div>

<!-- Quick Navigation -->
<div class="quick-nav">
    <h3 data-translate="quick-nav-title">🚀 Quick Navigation</h3>
    <div class="nav-cards">
        <a href="#get-categories" class="nav-card">
            <i class="fas fa-list"></i>
            <span data-translate="get-categories-nav">Get Categories</span>
        </a>
        <a href="#get-category" class="nav-card">
            <i class="fas fa-eye"></i>
            <span data-translate="get-category-nav">Get Category</span>
        </a>
        <a href="#create-category" class="nav-card">
            <i class="fas fa-plus"></i>
            <span data-translate="create-category-nav">Create Category</span>
        </a>
        <a href="#update-category" class="nav-card">
            <i class="fas fa-edit"></i>
            <span data-translate="update-category-nav">Update Category</span>
        </a>
        <a href="#delete-category" class="nav-card">
            <i class="fas fa-trash"></i>
            <span data-translate="delete-category-nav">Delete Category</span>
        </a>
    </div>
</div>

<!-- Get Categories List -->
<div class="endpoint-section" id="get-categories">
    <h2 data-translate="get-categories-title">📋 Get Categories List</h2>
    <p data-translate="get-categories-desc">
        Retrieve a list of all product categories with hierarchical structure support.
    </p>

    <div class="endpoint-overview">
        <h3 data-translate="endpoint-overview">🎯 Endpoint Overview</h3>
        <div class="overview-grid">
            <div class="overview-item">
                <span class="label" data-translate="http-method">HTTP Method:</span>
                <span class="badge badge-get">GET</span>
            </div>
            <div class="overview-item">
                <span class="label" data-translate="endpoint-url">Endpoint URL:</span>
                <code>{{ $baseUrl }}/categories</code>
            </div>
            <div class="overview-item">
                <span class="label" data-translate="required-headers">Required Headers:</span>
                <span data-translate="content-type-json">Content-Type: application/json</span>
            </div>
        </div>
    </div>

    <!-- Query Parameters -->
    <div class="parameters-section">
        <h3 data-translate="query-parameters">📝 Query Parameters</h3>
        <div class="parameters-table">
            <table>
                <thead>
                    <tr>
                        <th data-translate="param-name">Parameter Name</th>
                        <th data-translate="param-type">Type</th>
                        <th data-translate="param-required">Required</th>
                        <th data-translate="param-desc">Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>parent_id</code></td>
                        <td>integer</td>
                        <td><span class="badge badge-optional" data-translate="optional-field">Optional</span></td>
                        <td data-translate="parent-filter-desc">Filter by parent category ID (null for root categories)</td>
                    </tr>
                    <tr>
                        <td><code>include_children</code></td>
                        <td>boolean</td>
                        <td><span class="badge badge-optional" data-translate="optional-field">Optional</span></td>
                        <td data-translate="include-children-desc">Include child categories in response</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Code Examples -->
    <div class="code-examples">
        <h3 data-translate="example-request">📋 Example Request</h3>

        <div class="code-tabs">
            <div class="tab-buttons">
                <button class="tab-btn active" onclick="showCodeExample('get-categories', 'curl')">cURL</button>
                <button class="tab-btn" onclick="showCodeExample('get-categories', 'js')">JavaScript</button>
                <button class="tab-btn" onclick="showCodeExample('get-categories', 'php')">PHP</button>
                <button class="tab-btn" onclick="showCodeExample('get-categories', 'python')">Python</button>
            </div>

            <div class="code-example" id="get-categories-curl">
                <button class="copy-btn" onclick="copyCode('get-categories-curl-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-categories-curl-code" class="language-bash">curl -X GET "{{ $baseUrl }}/categories?include_children=true" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"</code></pre>
            </div>

            <div class="code-example" id="get-categories-js" style="display: none;">
                <button class="copy-btn" onclick="copyCode('get-categories-js-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-categories-js-code" class="language-javascript">const response = await fetch('{{ $baseUrl }}/categories?include_children=true', {
    method: 'GET',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

const data = await response.json();
console.log(data);</code></pre>
            </div>

            <div class="code-example" id="get-categories-php" style="display: none;">
                <button class="copy-btn" onclick="copyCode('get-categories-php-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-categories-php-code" class="language-php">$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => '{{ $baseUrl }}/categories?include_children=true',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json'
    ],
]);

$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);
print_r($data);</code></pre>
            </div>

            <div class="code-example" id="get-categories-python" style="display: none;">
                <button class="copy-btn" onclick="copyCode('get-categories-python-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-categories-python-code" class="language-python">import requests

url = '{{ $baseUrl }}/categories'
params = {
    'include_children': True
}

headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
}

response = requests.get(url, params=params, headers=headers)
data = response.json()
print(data)</code></pre>
            </div>
        </div>
    </div>

    <!-- Response Examples -->
    <div class="response-examples">
        <h3 data-translate="response-examples">📥 Response Examples</h3>

        <div class="response-section">
            <h4 data-translate="success-response">✅ Success Response (200 OK)</h4>
            <div class="code-example">
                <button class="copy-btn" onclick="copyCode('get-categories-success')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-categories-success" class="language-json">{
    "data": [
        {
            "id": 1,
            "name": "Electronics",
            "description": "Electronic devices and gadgets",
            "parent_id": null,
            "image_url": "https://example.com/images/electronics.jpg",
            "is_active": true,
            "products_count": 25,
            "children": [
                {
                    "id": 2,
                    "name": "Laptops",
                    "description": "Portable computers",
                    "parent_id": 1,
                    "image_url": "https://example.com/images/laptops.jpg",
                    "is_active": true,
                    "products_count": 12,
                    "children": []
                },
                {
                    "id": 3,
                    "name": "Smartphones",
                    "description": "Mobile phones",
                    "parent_id": 1,
                    "image_url": "https://example.com/images/phones.jpg",
                    "is_active": true,
                    "products_count": 13,
                    "children": []
                }
            ],
            "created_at": "2024-01-15T10:30:00.000000Z",
            "updated_at": "2024-01-15T10:30:00.000000Z"
        }
    ]
}</code></pre>
            </div>
        </div>
    </div>
</div>

<!-- Get Single Category -->
<div class="endpoint-section" id="get-category">
    <h2 data-translate="get-category-title">👁️ Get Single Category</h2>
    <p data-translate="get-category-desc">
        Retrieve detailed information about a specific category by ID.
    </p>

    <div class="endpoint-overview">
        <h3 data-translate="endpoint-overview">🎯 Endpoint Overview</h3>
        <div class="overview-grid">
            <div class="overview-item">
                <span class="label" data-translate="http-method">HTTP Method:</span>
                <span class="badge badge-get">GET</span>
            </div>
            <div class="overview-item">
                <span class="label" data-translate="endpoint-url">Endpoint URL:</span>
                <code>{{ $baseUrl }}/categories/{id}</code>
            </div>
            <div class="overview-item">
                <span class="label" data-translate="required-headers">Required Headers:</span>
                <span data-translate="content-type-json">Content-Type: application/json</span>
            </div>
        </div>
    </div>

    <!-- Path Parameters -->
    <div class="parameters-section">
        <h3 data-translate="path-parameters">📝 Path Parameters</h3>
        <div class="parameters-table">
            <table>
                <thead>
                    <tr>
                        <th data-translate="param-name">Parameter Name</th>
                        <th data-translate="param-type">Type</th>
                        <th data-translate="param-required">Required</th>
                        <th data-translate="param-desc">Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>id</code></td>
                        <td>integer</td>
                        <td><span class="badge badge-required" data-translate="required-field">Required</span></td>
                        <td data-translate="category-id-desc">The unique identifier of the category</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Code Examples -->
    <div class="code-examples">
        <h3 data-translate="example-request">📋 Example Request</h3>

        <div class="code-tabs">
            <div class="tab-buttons">
                <button class="tab-btn active" onclick="showCodeExample('get-category', 'curl')">cURL</button>
                <button class="tab-btn" onclick="showCodeExample('get-category', 'js')">JavaScript</button>
                <button class="tab-btn" onclick="showCodeExample('get-category', 'php')">PHP</button>
                <button class="tab-btn" onclick="showCodeExample('get-category', 'python')">Python</button>
            </div>

            <div class="code-example" id="get-category-curl">
                <button class="copy-btn" onclick="copyCode('get-category-curl-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-category-curl-code" class="language-bash">curl -X GET "{{ $baseUrl }}/categories/1" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"</code></pre>
            </div>

            <div class="code-example" id="get-category-js" style="display: none;">
                <button class="copy-btn" onclick="copyCode('get-category-js-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-category-js-code" class="language-javascript">const response = await fetch('{{ $baseUrl }}/categories/1', {
    method: 'GET',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

const data = await response.json();
console.log(data);</code></pre>
            </div>

            <div class="code-example" id="get-category-php" style="display: none;">
                <button class="copy-btn" onclick="copyCode('get-category-php-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-category-php-code" class="language-php">$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => '{{ $baseUrl }}/categories/1',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json'
    ],
]);

$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);
print_r($data);</code></pre>
            </div>

            <div class="code-example" id="get-category-python" style="display: none;">
                <button class="copy-btn" onclick="copyCode('get-category-python-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-category-python-code" class="language-python">import requests

url = '{{ $baseUrl }}/categories/1'

headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
}

response = requests.get(url, headers=headers)
data = response.json()
print(data)</code></pre>
            </div>
        </div>
    </div>

    <!-- Response Examples -->
    <div class="response-examples">
        <h3 data-translate="response-examples">📥 Response Examples</h3>

        <div class="response-section">
            <h4 data-translate="success-response">✅ Success Response (200 OK)</h4>
            <div class="code-example">
                <button class="copy-btn" onclick="copyCode('get-category-success')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-category-success" class="language-json">{
    "data": {
        "id": 1,
        "name": "Electronics",
        "description": "Electronic devices and gadgets",
        "parent_id": null,
        "image_url": "https://example.com/images/electronics.jpg",
        "is_active": true,
        "products_count": 25,
        "parent": null,
        "children": [
            {
                "id": 2,
                "name": "Laptops",
                "description": "Portable computers",
                "parent_id": 1,
                "image_url": "https://example.com/images/laptops.jpg",
                "is_active": true,
                "products_count": 12
            },
            {
                "id": 3,
                "name": "Smartphones",
                "description": "Mobile phones",
                "parent_id": 1,
                "image_url": "https://example.com/images/phones.jpg",
                "is_active": true,
                "products_count": 13
            }
        ],
        "created_at": "2024-01-15T10:30:00.000000Z",
        "updated_at": "2024-01-15T10:30:00.000000Z"
    }
}</code></pre>
            </div>
        </div>

        <div class="response-section">
            <h4 data-translate="not-found-response">❌ Not Found Response (404)</h4>
            <div class="code-example">
                <button class="copy-btn" onclick="copyCode('get-category-not-found')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-category-not-found" class="language-json">{
    "message": "Category not found"
}</code></pre>
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

    /* Endpoint Overview */
    .endpoint-overview {
        margin: 2rem 0;
        padding: 1.5rem;
        background: rgba(8, 124, 54, 0.05);
        border: 1px solid rgba(8, 124, 54, 0.2);
        border-radius: var(--radius-lg);
    }

    .endpoint-overview h3 {
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }

    .overview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .overview-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .overview-item .label {
        font-weight: 600;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .overview-item code {
        background: rgba(8, 124, 54, 0.1);
        color: var(--primary-color);
        padding: 0.5rem;
        border-radius: var(--radius-sm);
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 0.875rem;
    }

    /* Badges */
    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-get {
        background: linear-gradient(135deg, #007bff, #17a2b8);
        color: white;
    }

    .badge-required {
        background: rgba(220, 53, 69, 0.1);
        color: var(--error-color);
        border: 1px solid rgba(220, 53, 69, 0.3);
    }

    .badge-optional {
        background: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
        border: 1px solid rgba(40, 167, 69, 0.3);
    }

    /* Parameters Section */
    .parameters-section {
        margin: 2rem 0;
    }

    .parameters-section h3 {
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }

    .parameters-table {
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        background: var(--docs-content-bg);
    }

    .parameters-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .parameters-table th {
        background: var(--docs-bg);
        padding: 1rem 1.5rem;
        text-align: left;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.875rem;
        border-bottom: 2px solid var(--docs-border);
    }

    .parameters-table td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--docs-border);
        vertical-align: top;
    }

    .parameters-table tr:last-child td {
        border-bottom: none;
    }

    .parameters-table tr:nth-child(even) {
        background: rgba(8, 124, 54, 0.02);
    }

    .parameters-table code {
        background: rgba(8, 124, 54, 0.1);
        color: var(--primary-color);
        padding: 0.25rem 0.5rem;
        border-radius: var(--radius-sm);
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 0.875rem;
    }

    /* Code Examples */
    .code-examples {
        margin: 2rem 0;
    }

    .code-examples h3 {
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }

    .code-tabs {
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        background: var(--docs-code-bg);
    }

    .tab-buttons {
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

    .code-example {
        position: relative;
        background: #1a202c;
    }

    .copy-btn {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: none;
        border: none;
        color: #cbd5e0;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: var(--radius-sm);
        transition: var(--transition-fast);
        z-index: 10;
    }

    .copy-btn:hover {
        background: rgba(203, 213, 224, 0.1);
        color: var(--primary-color);
    }

    .code-example pre {
        margin: 0;
        padding: 1.5rem;
        overflow-x: auto;
    }

    .code-example code {
        color: #e2e8f0;
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 0.875rem;
        line-height: 1.6;
    }

    /* Response Examples */
    .response-examples {
        margin: 2rem 0;
    }

    .response-examples h3 {
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        font-size: 1.25rem;
    }

    .response-section {
        margin: 1.5rem 0;
    }

    .response-section h4 {
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-size: 1.125rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Smooth scrolling for anchors */
    html {
        scroll-behavior: smooth;
    }

    /* Enhanced visual hierarchy */
    .page-header {
        text-align: center;
        margin-bottom: 3rem;
        padding: 2rem 0;
        background: linear-gradient(135deg, rgba(8, 124, 54, 0.05) 0%, rgba(8, 124, 54, 0.02) 100%);
        border-radius: var(--radius-lg);
        border: 1px solid rgba(8, 124, 54, 0.1);
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1rem;
        background: linear-gradient(135deg, var(--primary-color), #22c55e);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .page-description {
        font-size: 1.25rem;
        color: var(--text-secondary);
        max-width: 800px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .endpoint-section {
            padding: 1rem;
            margin: 2rem 0;
        }

        .quick-nav {
            padding: 1rem;
        }

        .nav-cards {
            grid-template-columns: 1fr;
        }

        .overview-grid {
            grid-template-columns: 1fr;
        }

        .parameters-table {
            overflow-x: auto;
        }

        .tab-buttons {
            flex-wrap: wrap;
        }

        .tab-btn {
            flex: 1;
            min-width: 120px;
        }

        .page-title {
            font-size: 2rem;
        }

        .page-description {
            font-size: 1.125rem;
        }
    }

    /* Focus states for accessibility */
    .nav-card:focus,
    .tab-btn:focus,
    .copy-btn:focus {
        outline: 2px solid var(--primary-color);
        outline-offset: 2px;
    }

    /* Enhanced animations */
    .endpoint-section {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Copy feedback animation */
    .copy-btn.copied {
        animation: copyFeedback 0.3s ease-out;
    }

    @keyframes copyFeedback {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
</style>
@endpush

@push('scripts')
<script>
    function showCodeExample(endpoint, language) {
        // Hide all examples for this endpoint
        const examples = document.querySelectorAll(`[id^="${endpoint}-"]:not([id$="-code"])`);
        examples.forEach(example => {
            example.style.display = 'none';
        });

        // Show selected example
        document.getElementById(`${endpoint}-${language}`).style.display = 'block';

        // Update tab buttons
        const tabButtons = document.querySelectorAll('.tab-btn');
        tabButtons.forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
    }

    function copyCode(elementId) {
        const element = document.getElementById(elementId);
        const text = element.textContent;

        navigator.clipboard.writeText(text).then(function() {
            // Show success feedback
            const btn = event.target.closest('.copy-btn');
            const icon = btn.querySelector('i');
            const originalClass = icon.className;

            // Add copy feedback animation
            btn.classList.add('copied');
            icon.className = 'fas fa-check';
            btn.style.color = '#28a745';

            setTimeout(() => {
                btn.classList.remove('copied');
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

            btn.classList.add('copied');
            icon.className = 'fas fa-check';
            btn.style.color = '#28a745';

            setTimeout(() => {
                btn.classList.remove('copied');
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

        // Initialize first tab as active for each endpoint
        const firstTabs = document.querySelectorAll('.tab-buttons .tab-btn:first-child');
        firstTabs.forEach(tab => {
            tab.classList.add('active');
        });
    });

    // Additional translations for categories endpoints
    const categoriesEndpointsTranslations = {
        'en': {
            'categories-endpoints': 'Categories API',
            'categories-endpoints-title': '📂 Categories API Endpoints',
            'categories-endpoints-desc': 'Complete reference for category management API endpoints including listing, creating, updating, deleting categories and managing product categorization.',
            'quick-nav-title': '🚀 Quick Navigation',
            'get-categories-nav': 'Get Categories',
            'get-category-nav': 'Get Category',
            'create-category-nav': 'Create Category',
            'update-category-nav': 'Update Category',
            'delete-category-nav': 'Delete Category',
            'get-categories-title': '📋 Get Categories List',
            'get-categories-desc': 'Retrieve a list of all product categories with hierarchical structure support.',
            'get-category-title': '👁️ Get Single Category',
            'get-category-desc': 'Retrieve detailed information about a specific category by ID.',
            'endpoint-overview': 'Endpoint Overview',
            'http-method': 'HTTP Method',
            'endpoint-url': 'Endpoint URL',
            'required-headers': 'Required Headers',
            'content-type-json': 'Content-Type: application/json',
            'query-parameters': 'Query Parameters',
            'path-parameters': 'Path Parameters',
            'param-name': 'Parameter Name',
            'param-type': 'Type',
            'param-required': 'Required',
            'param-desc': 'Description',
            'required-field': 'Required',
            'optional-field': 'Optional',
            'parent-filter-desc': 'Filter by parent category ID (null for root categories)',
            'include-children-desc': 'Include child categories in response',
            'category-id-desc': 'The unique identifier of the category',
            'example-request': 'Example Request',
            'response-examples': 'Response Examples',
            'success-response': 'Success Response',
            'not-found-response': 'Not Found Response'
        },
        'uz': {
            'categories-endpoints': 'Kategoriyalar API',
            'categories-endpoints-title': '📂 Kategoriyalar API Endpointlari',
            'categories-endpoints-desc': 'Kategoriyalarni boshqarish uchun to\'liq API endpointlari ma\'lumotnomasi: ro\'yxat olish, yaratish, yangilash, o\'chirish va mahsulotlarni kategoriyalash.',
            'quick-nav-title': '🚀 Tezkor navigatsiya',
            'get-categories-nav': 'Kategoriyalar ro\'yxati',
            'get-category-nav': 'Kategoriya ma\'lumoti',
            'create-category-nav': 'Kategoriya yaratish',
            'update-category-nav': 'Kategoriyani yangilash',
            'delete-category-nav': 'Kategoriyani o\'chirish',
            'get-categories-title': '📋 Kategoriyalar ro\'yxati',
            'get-categories-desc': 'Ierarxik tuzilma qo\'llab-quvvatlashi bilan barcha mahsulot kategoriyalarining ro\'yxatini olish.',
            'get-category-title': '👁️ Bitta kategoriya ma\'lumoti',
            'get-category-desc': 'ID bo\'yicha muayyan kategoriya haqida batafsil ma\'lumot olish.',
            'endpoint-overview': 'Endpoint ko\'rinishi',
            'http-method': 'HTTP metod',
            'endpoint-url': 'Endpoint URL',
            'required-headers': 'Majburiy sarlavhalar',
            'content-type-json': 'Content-Type: application/json',
            'query-parameters': 'So\'rov parametrlari',
            'path-parameters': 'Yo\'l parametrlari',
            'param-name': 'Parametr nomi',
            'param-type': 'Turi',
            'param-required': 'Majburiyligi',
            'param-desc': 'Tavsif',
            'required-field': 'Majburiy',
            'optional-field': 'Ixtiyoriy',
            'parent-filter-desc': 'Ota kategoriya ID bo\'yicha filterlash (ildiz kategoriyalar uchun null)',
            'include-children-desc': 'Javobga bola kategoriyalarni qo\'shish',
            'category-id-desc': 'Kategoriyaning noyob identifikatori',
            'example-request': 'So\'rov namunasi',
            'response-examples': 'Javob namunalari',
            'success-response': 'Muvaffaqiyatli javob',
            'not-found-response': 'Topilmadi javobi'
        },
        'ru': {
            'categories-endpoints': 'API категорий',
            'categories-endpoints-title': '📂 Эндпоинты API категорий',
            'categories-endpoints-desc': 'Полная справка по API эндпоинтам управления категориями, включая получение списка, создание, обновление, удаление категорий и управление категоризацией товаров.',
            'quick-nav-title': '🚀 Быстрая навигация',
            'get-categories-nav': 'Список категорий',
            'get-category-nav': 'Информация о категории',
            'create-category-nav': 'Создать категорию',
            'update-category-nav': 'Обновить категорию',
            'delete-category-nav': 'Удалить категорию',
            'get-categories-title': '📋 Список категорий',
            'get-categories-desc': 'Получить список всех категорий товаров с поддержкой иерархической структуры.',
            'get-category-title': '👁️ Информация о категории',
            'get-category-desc': 'Получить подробную информацию о конкретной категории по ID.',
            'endpoint-overview': 'Обзор эндпоинта',
            'http-method': 'HTTP метод',
            'endpoint-url': 'URL эндпоинта',
            'required-headers': 'Обязательные заголовки',
            'content-type-json': 'Content-Type: application/json',
            'query-parameters': 'Параметры запроса',
            'path-parameters': 'Параметры пути',
            'param-name': 'Название параметра',
            'param-type': 'Тип',
            'param-required': 'Обязательность',
            'param-desc': 'Описание',
            'required-field': 'Обязательно',
            'optional-field': 'Необязательно',
            'parent-filter-desc': 'Фильтрация по ID родительской категории (null для корневых категорий)',
            'include-children-desc': 'Включить дочерние категории в ответ',
            'category-id-desc': 'Уникальный идентификатор категории',
            'example-request': 'Пример запроса',
            'response-examples': 'Примеры ответов',
            'success-response': 'Успешный ответ',
            'not-found-response': 'Ответ "Не найдено"'
        }
    };

    // Merge with existing translations
    Object.keys(categoriesEndpointsTranslations).forEach(lang => {
        if (translations[lang]) {
            Object.assign(translations[lang], categoriesEndpointsTranslations[lang]);
        }
    });
</script>
@endpush

<!-- Navigation Component -->
@include('docs.components.page-navigation', $navigation)

@endsection
