@extends('docs.layout')

@section('title', $title)

@section('breadcrumb')
<div class="breadcrumb">
    <a href="{{ route('docs.index') }}" data-translate="api-documentation">API Documentation</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('docs.endpoints', 'products') }}" data-translate="products-endpoints">Products API</a>
</div>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title" data-translate="products-endpoints-title">🛍️ Products API Endpoints</h1>
    <p class="page-description" data-translate="products-endpoints-desc">
        Complete reference for product management API endpoints including listing,
        creating, updating, deleting products and product search functionality.
    </p>
</div>

<!-- Quick Navigation -->
<div class="quick-nav">
    <h3 data-translate="quick-nav-title">🚀 Quick Navigation</h3>
    <div class="nav-cards">
        <a href="#get-products" class="nav-card">
            <i class="fas fa-list"></i>
            <span data-translate="get-products-nav">Get Products</span>
        </a>
        <a href="#get-product" class="nav-card">
            <i class="fas fa-eye"></i>
            <span data-translate="get-product-nav">Get Product</span>
        </a>
        <a href="#create-product" class="nav-card">
            <i class="fas fa-plus"></i>
            <span data-translate="create-product-nav">Create Product</span>
        </a>
        <a href="#update-product" class="nav-card">
            <i class="fas fa-edit"></i>
            <span data-translate="update-product-nav">Update Product</span>
        </a>
        <a href="#delete-product" class="nav-card">
            <i class="fas fa-trash"></i>
            <span data-translate="delete-product-nav">Delete Product</span>
        </a>
        <a href="#search-products" class="nav-card">
            <i class="fas fa-search"></i>
            <span data-translate="search-products-nav">Search Products</span>
        </a>
    </div>
</div>

<!-- Get Products List -->
<div class="endpoint-section" id="get-products">
    <h2 data-translate="get-products-title">📋 Get Products List</h2>
    <p data-translate="get-products-desc">
        Retrieve a paginated list of all products with filtering and sorting options.
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
                <code>{{ $baseUrl }}/products</code>
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
                        <td><code>page</code></td>
                        <td>integer</td>
                        <td><span class="badge badge-optional" data-translate="optional-field">Optional</span></td>
                        <td data-translate="page-param-desc">Page number for pagination (default: 1)</td>
                    </tr>
                    <tr>
                        <td><code>per_page</code></td>
                        <td>integer</td>
                        <td><span class="badge badge-optional" data-translate="optional-field">Optional</span></td>
                        <td data-translate="per-page-param-desc">Number of items per page (default: 15, max: 100)</td>
                    </tr>
                    <tr>
                        <td><code>category_id</code></td>
                        <td>integer</td>
                        <td><span class="badge badge-optional" data-translate="optional-field">Optional</span></td>
                        <td data-translate="category-filter-desc">Filter products by category ID</td>
                    </tr>
                    <tr>
                        <td><code>sort</code></td>
                        <td>string</td>
                        <td><span class="badge badge-optional" data-translate="optional-field">Optional</span></td>
                        <td data-translate="sort-param-desc">Sort field (name, price, created_at)</td>
                    </tr>
                    <tr>
                        <td><code>order</code></td>
                        <td>string</td>
                        <td><span class="badge badge-optional" data-translate="optional-field">Optional</span></td>
                        <td data-translate="order-param-desc">Sort direction (asc, desc)</td>
                    </tr>
                    <tr>
                        <td><code>search</code></td>
                        <td>string</td>
                        <td><span class="badge badge-optional" data-translate="optional-field">Optional</span></td>
                        <td data-translate="search-param-desc">Search products by name or description</td>
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
                <button class="tab-btn active" onclick="showCodeExample('get-products', 'curl')">cURL</button>
                <button class="tab-btn" onclick="showCodeExample('get-products', 'js')">JavaScript</button>
                <button class="tab-btn" onclick="showCodeExample('get-products', 'php')">PHP</button>
                <button class="tab-btn" onclick="showCodeExample('get-products', 'python')">Python</button>
            </div>

            <div class="code-example" id="get-products-curl">
                <button class="copy-btn" onclick="copyCode('get-products-curl-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-products-curl-code" class="language-bash">curl -X GET "{{ $baseUrl }}/products?page=1&per_page=10&category_id=1&sort=name&order=asc" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"</code></pre>
            </div>

            <div class="code-example" id="get-products-js" style="display: none;">
                <button class="copy-btn" onclick="copyCode('get-products-js-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-products-js-code" class="language-javascript">const response = await fetch('{{ $baseUrl }}/products?page=1&per_page=10&category_id=1', {
    method: 'GET',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

const data = await response.json();
console.log(data);</code></pre>
            </div>

            <div class="code-example" id="get-products-php" style="display: none;">
                <button class="copy-btn" onclick="copyCode('get-products-php-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-products-php-code" class="language-php">$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => '{{ $baseUrl }}/products?page=1&per_page=10&category_id=1',
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

            <div class="code-example" id="get-products-python" style="display: none;">
                <button class="copy-btn" onclick="copyCode('get-products-python-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-products-python-code" class="language-python">import requests

url = '{{ $baseUrl }}/products'
params = {
    'page': 1,
    'per_page': 10,
    'category_id': 1,
    'sort': 'name',
    'order': 'asc'
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
                <button class="copy-btn" onclick="copyCode('get-products-success')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-products-success" class="language-json">{
    "data": [
        {
            "id": 1,
            "name": "Premium Laptop",
            "description": "High-performance laptop for professionals",
            "price": 1299.99,
            "category_id": 1,
            "category": {
                "id": 1,
                "name": "Electronics"
            },
            "images": [
                {
                    "id": 1,
                    "url": "https://example.com/images/laptop-1.jpg",
                    "is_primary": true
                }
            ],
            "stock_quantity": 50,
            "is_active": true,
            "created_at": "2024-01-15T10:30:00.000000Z",
            "updated_at": "2024-01-15T10:30:00.000000Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 5,
        "per_page": 10,
        "to": 10,
        "total": 50
    },
    "links": {
        "first": "{{ $baseUrl }}/products?page=1",
        "last": "{{ $baseUrl }}/products?page=5",
        "prev": null,
        "next": "{{ $baseUrl }}/products?page=2"
    }
}</code></pre>
            </div>
        </div>
    </div>
</div>

<!-- Get Single Product -->
<div class="endpoint-section" id="get-product">
    <h2 data-translate="get-product-title">👁️ Get Single Product</h2>
    <p data-translate="get-product-desc">
        Retrieve detailed information about a specific product by ID.
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
                <code>{{ $baseUrl }}/products/{id}</code>
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
                        <td data-translate="product-id-desc">The unique identifier of the product</td>
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
                <button class="tab-btn active" onclick="showCodeExample('get-product', 'curl')">cURL</button>
                <button class="tab-btn" onclick="showCodeExample('get-product', 'js')">JavaScript</button>
                <button class="tab-btn" onclick="showCodeExample('get-product', 'php')">PHP</button>
                <button class="tab-btn" onclick="showCodeExample('get-product', 'python')">Python</button>
            </div>

            <div class="code-example" id="get-product-curl">
                <button class="copy-btn" onclick="copyCode('get-product-curl-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-product-curl-code" class="language-bash">curl -X GET "{{ $baseUrl }}/products/1" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"</code></pre>
            </div>

            <div class="code-example" id="get-product-js" style="display: none;">
                <button class="copy-btn" onclick="copyCode('get-product-js-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-product-js-code" class="language-javascript">const response = await fetch('{{ $baseUrl }}/products/1', {
    method: 'GET',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

const data = await response.json();
console.log(data);</code></pre>
            </div>

            <div class="code-example" id="get-product-php" style="display: none;">
                <button class="copy-btn" onclick="copyCode('get-product-php-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-product-php-code" class="language-php">$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => '{{ $baseUrl }}/products/1',
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

            <div class="code-example" id="get-product-python" style="display: none;">
                <button class="copy-btn" onclick="copyCode('get-product-python-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-product-python-code" class="language-python">import requests

url = '{{ $baseUrl }}/products/1'

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
                <button class="copy-btn" onclick="copyCode('get-product-success')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-product-success" class="language-json">{
    "data": {
        "id": 1,
        "name": "Premium Laptop",
        "description": "High-performance laptop for professionals with latest technology",
        "price": 1299.99,
        "category_id": 1,
        "category": {
            "id": 1,
            "name": "Electronics",
            "description": "Electronic devices and gadgets"
        },
        "images": [
            {
                "id": 1,
                "url": "https://example.com/images/laptop-1.jpg",
                "is_primary": true
            },
            {
                "id": 2,
                "url": "https://example.com/images/laptop-2.jpg",
                "is_primary": false
            }
        ],
        "stock_quantity": 50,
        "is_active": true,
        "created_at": "2024-01-15T10:30:00.000000Z",
        "updated_at": "2024-01-15T10:30:00.000000Z"
    }
}</code></pre>
            </div>
        </div>

        <div class="response-section">
            <h4 data-translate="not-found-response">❌ Not Found Response (404)</h4>
            <div class="code-example">
                <button class="copy-btn" onclick="copyCode('get-product-not-found')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-product-not-found" class="language-json">{
    "message": "Product not found"
}</code></pre>
            </div>
        </div>
    </div>
</div>


<!-- Navigation Component -->
@include('docs.components.page-navigation', $navigation)


@endsection

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

    /* Responsive */
    @media (max-width: 768px) {
        .endpoint-section {
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
    }
</style>
@endpush

@push('scripts')
<script>
    // Code switching functionality
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

    // Additional translations for products endpoints
    const productsEndpointsTranslations = {
        'en': {
            'products-endpoints': 'Products API',
            'products-endpoints-title': '🛍️ Products API Endpoints',
            'products-endpoints-desc': 'Complete reference for product management API endpoints including listing, creating, updating, deleting products and product search functionality.',
            'quick-nav-title': '🚀 Quick Navigation',
            'get-products-nav': 'Get Products',
            'get-product-nav': 'Get Product',
            'create-product-nav': 'Create Product',
            'update-product-nav': 'Update Product',
            'delete-product-nav': 'Delete Product',
            'search-products-nav': 'Search Products',
            'get-products-title': '📋 Get Products List',
            'get-products-desc': 'Retrieve a paginated list of all products with filtering and sorting options.',
            'get-product-title': '👁️ Get Single Product',
            'get-product-desc': 'Retrieve detailed information about a specific product by ID.',
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
            'page-param-desc': 'Page number for pagination (default: 1)',
            'per-page-param-desc': 'Number of items per page (default: 15, max: 100)',
            'category-filter-desc': 'Filter products by category ID',
            'sort-param-desc': 'Sort field (name, price, created_at)',
            'order-param-desc': 'Sort direction (asc, desc)',
            'search-param-desc': 'Search products by name or description',
            'product-id-desc': 'The unique identifier of the product',
            'example-request': 'Example Request',
            'response-examples': 'Response Examples',
            'success-response': 'Success Response',
            'not-found-response': 'Not Found Response'
        },
        'uz': {
            'products-endpoints': 'Mahsulotlar API',
            'products-endpoints-title': '🛍️ Mahsulotlar API Endpointlari',
            'products-endpoints-desc': 'Mahsulotlarni boshqarish uchun to\'liq API endpointlari ma\'lumotnomasi: ro\'yxat olish, yaratish, yangilash, o\'chirish va qidirish funksiyalari.',
            'quick-nav-title': '🚀 Tezkor navigatsiya',
            'get-products-nav': 'Mahsulotlar ro\'yxati',
            'get-product-nav': 'Mahsulot ma\'lumoti',
            'create-product-nav': 'Mahsulot yaratish',
            'update-product-nav': 'Mahsulotni yangilash',
            'delete-product-nav': 'Mahsulotni o\'chirish',
            'search-products-nav': 'Mahsulot qidirish',
            'get-products-title': '📋 Mahsulotlar ro\'yxati',
            'get-products-desc': 'Filterlash va saralash imkoniyatlari bilan barcha mahsulotlarning sahifalangan ro\'yxatini olish.',
            'get-product-title': '👁️ Bitta mahsulot ma\'lumoti',
            'get-product-desc': 'ID bo\'yicha muayyan mahsulot haqida batafsil ma\'lumot olish.',
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
            'page-param-desc': 'Sahifalash uchun sahifa raqami (standart: 1)',
            'per-page-param-desc': 'Har sahifadagi elementlar soni (standart: 15, maks: 100)',
            'category-filter-desc': 'Mahsulotlarni kategoriya ID bo\'yicha filterlash',
            'sort-param-desc': 'Saralash maydoni (name, price, created_at)',
            'order-param-desc': 'Saralash yo\'nalishi (asc, desc)',
            'search-param-desc': 'Mahsulotlarni nom yoki tavsif bo\'yicha qidirish',
            'product-id-desc': 'Mahsulotning noyob identifikatori',
            'example-request': 'So\'rov namunasi',
            'response-examples': 'Javob namunalari',
            'success-response': 'Muvaffaqiyatli javob',
            'not-found-response': 'Topilmadi javobi'
        },
        'ru': {
            'products-endpoints': 'API товаров',
            'products-endpoints-title': '🛍️ Эндпоинты API товаров',
            'products-endpoints-desc': 'Полная справка по API эндпоинтам управления товарами, включая получение списка, создание, обновление, удаление товаров и функцию поиска.',
            'quick-nav-title': '🚀 Быстрая навигация',
            'get-products-nav': 'Список товаров',
            'get-product-nav': 'Информация о товаре',
            'create-product-nav': 'Создать товар',
            'update-product-nav': 'Обновить товар',
            'delete-product-nav': 'Удалить товар',
            'search-products-nav': 'Поиск товаров',
            'get-products-title': '📋 Список товаров',
            'get-products-desc': 'Получить пагинированный список всех товаров с возможностями фильтрации и сортировки.',
            'get-product-title': '👁️ Информация о товаре',
            'get-product-desc': 'Получить подробную информацию о конкретном товаре по ID.',
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
            'page-param-desc': 'Номер страницы для пагинации (по умолчанию: 1)',
            'per-page-param-desc': 'Количество элементов на странице (по умолчанию: 15, макс: 100)',
            'category-filter-desc': 'Фильтрация товаров по ID категории',
            'sort-param-desc': 'Поле сортировки (name, price, created_at)',
            'order-param-desc': 'Направление сортировки (asc, desc)',
            'search-param-desc': 'Поиск товаров по названию или описанию',
            'product-id-desc': 'Уникальный идентификатор товара',
            'example-request': 'Пример запроса',
            'response-examples': 'Примеры ответов',
            'success-response': 'Успешный ответ',
            'not-found-response': 'Ответ "Не найдено"'
        }
    };

    // Merge with existing translations
    Object.keys(productsEndpointsTranslations).forEach(lang => {
        if (translations[lang]) {
            Object.assign(translations[lang], productsEndpointsTranslations[lang]);
        }
    });
</script>
@endpush
