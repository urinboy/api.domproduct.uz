@extends('docs.layout')

@section('title', $title)

@section('breadcrumb')
<div class="breadcrumb">
    <a href="{{ route('docs.index') }}" data-translate="api-documentation">API Documentation</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('docs.endpoints', 'orders') }}" data-translate="orders-endpoints">Orders API</a>
</div>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title" data-translate="orders-endpoints-title">🛒 Orders API Endpoints</h1>
    <p class="page-description" data-translate="orders-endpoints-desc">
        Complete reference for order management API endpoints including creating orders,
        tracking status, managing order items, and processing order lifecycle.
    </p>
</div>

<!-- Quick Navigation -->
<div class="quick-nav">
    <h3 data-translate="quick-nav-title">🚀 Quick Navigation</h3>
    <div class="nav-cards">
        <a href="#get-orders" class="nav-card">
            <i class="fas fa-list"></i>
            <span data-translate="get-orders-nav">Get Orders</span>
        </a>
        <a href="#get-order" class="nav-card">
            <i class="fas fa-eye"></i>
            <span data-translate="get-order-nav">Get Order</span>
        </a>
        <a href="#create-order" class="nav-card">
            <i class="fas fa-plus"></i>
            <span data-translate="create-order-nav">Create Order</span>
        </a>
        <a href="#update-order" class="nav-card">
            <i class="fas fa-edit"></i>
            <span data-translate="update-order-nav">Update Order</span>
        </a>
        <a href="#cancel-order" class="nav-card">
            <i class="fas fa-times-circle"></i>
            <span data-translate="cancel-order-nav">Cancel Order</span>
        </a>
    </div>
</div>

<!-- Get Orders List -->
<div class="endpoint-section" id="get-orders">
    <h2 data-translate="get-orders-title">📋 Get Orders List</h2>
    <p data-translate="get-orders-desc">
        Retrieve a paginated list of orders for the authenticated user with filtering options.
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
                <code>{{ $baseUrl }}/orders</code>
            </div>
            <div class="overview-item">
                <span class="label" data-translate="required-headers">Required Headers:</span>
                <span data-translate="auth-required">Authentication Required</span>
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
                        <td><code>status</code></td>
                        <td>string</td>
                        <td><span class="badge badge-optional" data-translate="optional-field">Optional</span></td>
                        <td data-translate="status-filter-desc">Filter by order status (pending, confirmed, shipped, delivered, cancelled)</td>
                    </tr>
                    <tr>
                        <td><code>date_from</code></td>
                        <td>date</td>
                        <td><span class="badge badge-optional" data-translate="optional-field">Optional</span></td>
                        <td data-translate="date-from-desc">Filter orders from this date (YYYY-MM-DD)</td>
                    </tr>
                    <tr>
                        <td><code>date_to</code></td>
                        <td>date</td>
                        <td><span class="badge badge-optional" data-translate="optional-field">Optional</span></td>
                        <td data-translate="date-to-desc">Filter orders up to this date (YYYY-MM-DD)</td>
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
                <button class="tab-btn active" onclick="showCodeExample('get-orders', 'curl')">cURL</button>
                <button class="tab-btn" onclick="showCodeExample('get-orders', 'js')">JavaScript</button>
                <button class="tab-btn" onclick="showCodeExample('get-orders', 'php')">PHP</button>
                <button class="tab-btn" onclick="showCodeExample('get-orders', 'python')">Python</button>
            </div>

            <div class="code-example" id="get-orders-curl">
                <button class="copy-btn" onclick="copyCode('get-orders-curl-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-orders-curl-code" class="language-bash">curl -X GET "{{ $baseUrl }}/orders?page=1&status=pending" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"</code></pre>
            </div>

            <div class="code-example" id="get-orders-js" style="display: none;">
                <button class="copy-btn" onclick="copyCode('get-orders-js-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-orders-js-code" class="language-javascript">const response = await fetch('{{ $baseUrl }}/orders?page=1&status=pending', {
    method: 'GET',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer ' + token
    }
});

const data = await response.json();
console.log(data);</code></pre>
            </div>

            <div class="code-example" id="get-orders-php" style="display: none;">
                <button class="copy-btn" onclick="copyCode('get-orders-php-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-orders-php-code" class="language-php">$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => '{{ $baseUrl }}/orders?page=1&status=pending',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $token
    ],
]);

$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);
print_r($data);</code></pre>
            </div>

            <div class="code-example" id="get-orders-python" style="display: none;">
                <button class="copy-btn" onclick="copyCode('get-orders-python-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-orders-python-code" class="language-python">import requests

url = '{{ $baseUrl }}/orders'
params = {
    'page': 1,
    'status': 'pending'
}

headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': f'Bearer {token}'
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
                <button class="copy-btn" onclick="copyCode('get-orders-success')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-orders-success" class="language-json">{
    "data": [
        {
            "id": 1,
            "order_number": "ORD-2024-001",
            "status": "pending",
            "total_amount": 1599.98,
            "currency": "USD",
            "user": {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com"
            },
            "shipping_address": {
                "street": "123 Main St",
                "city": "New York",
                "state": "NY",
                "postal_code": "10001",
                "country": "USA"
            },
            "items": [
                {
                    "id": 1,
                    "product_id": 1,
                    "product_name": "Premium Laptop",
                    "quantity": 1,
                    "price": 1299.99,
                    "total": 1299.99
                },
                {
                    "id": 2,
                    "product_id": 2,
                    "product_name": "Wireless Mouse",
                    "quantity": 2,
                    "price": 149.99,
                    "total": 299.99
                }
            ],
            "created_at": "2024-01-15T10:30:00.000000Z",
            "updated_at": "2024-01-15T10:30:00.000000Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 3,
        "per_page": 15,
        "to": 15,
        "total": 45
    },
    "links": {
        "first": "{{ $baseUrl }}/orders?page=1",
        "last": "{{ $baseUrl }}/orders?page=3",
        "prev": null,
        "next": "{{ $baseUrl }}/orders?page=2"
    }
}</code></pre>
            </div>
        </div>
    </div>
</div>

<!-- Get Single Order -->
<div class="endpoint-section" id="get-order">
    <h2 data-translate="get-order-title">👁️ Get Single Order</h2>
    <p data-translate="get-order-desc">
        Retrieve detailed information about a specific order by ID.
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
                <code>{{ $baseUrl }}/orders/{id}</code>
            </div>
            <div class="overview-item">
                <span class="label" data-translate="required-headers">Required Headers:</span>
                <span data-translate="auth-required">Authentication Required</span>
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
                        <td data-translate="order-id-desc">The unique identifier of the order</td>
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
                <button class="tab-btn active" onclick="showCodeExample('get-order', 'curl')">cURL</button>
                <button class="tab-btn" onclick="showCodeExample('get-order', 'js')">JavaScript</button>
                <button class="tab-btn" onclick="showCodeExample('get-order', 'php')">PHP</button>
                <button class="tab-btn" onclick="showCodeExample('get-order', 'python')">Python</button>
            </div>

            <div class="code-example" id="get-order-curl">
                <button class="copy-btn" onclick="copyCode('get-order-curl-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-order-curl-code" class="language-bash">curl -X GET "{{ $baseUrl }}/orders/1" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"</code></pre>
            </div>

            <div class="code-example" id="get-order-js" style="display: none;">
                <button class="copy-btn" onclick="copyCode('get-order-js-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-order-js-code" class="language-javascript">const response = await fetch('{{ $baseUrl }}/orders/1', {
    method: 'GET',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer ' + token
    }
});

const data = await response.json();
console.log(data);</code></pre>
            </div>

            <div class="code-example" id="get-order-php" style="display: none;">
                <button class="copy-btn" onclick="copyCode('get-order-php-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-order-php-code" class="language-php">$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => '{{ $baseUrl }}/orders/1',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $token
    ],
]);

$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);
print_r($data);</code></pre>
            </div>

            <div class="code-example" id="get-order-python" style="display: none;">
                <button class="copy-btn" onclick="copyCode('get-order-python-code')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-order-python-code" class="language-python">import requests

url = '{{ $baseUrl }}/orders/1'

headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': f'Bearer {token}'
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
                <button class="copy-btn" onclick="copyCode('get-order-success')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-order-success" class="language-json">{
    "data": {
        "id": 1,
        "order_number": "ORD-2024-001",
        "status": "pending",
        "total_amount": 1599.98,
        "currency": "USD",
        "payment_status": "pending",
        "payment_method": "credit_card",
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "+1234567890"
        },
        "shipping_address": {
            "street": "123 Main St",
            "city": "New York",
            "state": "NY",
            "postal_code": "10001",
            "country": "USA"
        },
        "billing_address": {
            "street": "123 Main St",
            "city": "New York",
            "state": "NY",
            "postal_code": "10001",
            "country": "USA"
        },
        "items": [
            {
                "id": 1,
                "product_id": 1,
                "product": {
                    "id": 1,
                    "name": "Premium Laptop",
                    "image_url": "https://example.com/images/laptop.jpg"
                },
                "quantity": 1,
                "price": 1299.99,
                "total": 1299.99
            },
            {
                "id": 2,
                "product_id": 2,
                "product": {
                    "id": 2,
                    "name": "Wireless Mouse",
                    "image_url": "https://example.com/images/mouse.jpg"
                },
                "quantity": 2,
                "price": 149.99,
                "total": 299.99
            }
        ],
        "order_history": [
            {
                "id": 1,
                "status": "pending",
                "description": "Order placed successfully",
                "created_at": "2024-01-15T10:30:00.000000Z"
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
                <button class="copy-btn" onclick="copyCode('get-order-not-found')">
                    <i class="fas fa-copy"></i>
                </button>
                <pre><code id="get-order-not-found" class="language-json">{
    "message": "Order not found"
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

    /* Enhanced page header for orders */
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

    /* Order-specific styling */
    .order-status-badge {
        padding: 0.5rem 1rem;
        border-radius: var(--radius-full);
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background: rgba(255, 193, 7, 0.1);
        color: #856404;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .status-confirmed {
        background: rgba(0, 123, 255, 0.1);
        color: #004085;
        border: 1px solid rgba(0, 123, 255, 0.3);
    }

    .status-shipped {
        background: rgba(40, 167, 69, 0.1);
        color: #155724;
        border: 1px solid rgba(40, 167, 69, 0.3);
    }

    .status-delivered {
        background: rgba(40, 167, 69, 0.2);
        color: #155724;
        border: 1px solid rgba(40, 167, 69, 0.4);
    }

    .status-cancelled {
        background: rgba(220, 53, 69, 0.1);
        color: #721c24;
        border: 1px solid rgba(220, 53, 69, 0.3);
    }

    /* Authentication required indicator */
    .auth-required {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 193, 7, 0.1);
        color: #856404;
        padding: 0.25rem 0.75rem;
        border-radius: var(--radius-sm);
        font-size: 0.875rem;
        font-weight: 600;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .auth-required::before {
        content: '🔐';
        font-size: 1rem;
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

    // Additional translations for orders endpoints
    const ordersEndpointsTranslations = {
        'en': {
            'orders-endpoints': 'Orders API',
            'orders-endpoints-title': '🛒 Orders API Endpoints',
            'orders-endpoints-desc': 'Complete reference for order management API endpoints including creating orders, tracking status, managing order items, and processing order lifecycle.',
            'quick-nav-title': '🚀 Quick Navigation',
            'get-orders-nav': 'Get Orders',
            'get-order-nav': 'Get Order',
            'create-order-nav': 'Create Order',
            'update-order-nav': 'Update Order',
            'cancel-order-nav': 'Cancel Order',
            'get-orders-title': '📋 Get Orders List',
            'get-orders-desc': 'Retrieve a paginated list of orders for the authenticated user with filtering options.',
            'get-order-title': '👁️ Get Single Order',
            'get-order-desc': 'Retrieve detailed information about a specific order by ID.',
            'endpoint-overview': 'Endpoint Overview',
            'http-method': 'HTTP Method',
            'endpoint-url': 'Endpoint URL',
            'required-headers': 'Required Headers',
            'auth-required': 'Authentication Required',
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
            'status-filter-desc': 'Filter by order status (pending, confirmed, shipped, delivered, cancelled)',
            'date-from-desc': 'Filter orders from this date (YYYY-MM-DD)',
            'date-to-desc': 'Filter orders up to this date (YYYY-MM-DD)',
            'order-id-desc': 'The unique identifier of the order',
            'example-request': 'Example Request',
            'response-examples': 'Response Examples',
            'success-response': 'Success Response',
            'not-found-response': 'Not Found Response'
        },
        'uz': {
            'orders-endpoints': 'Buyurtmalar API',
            'orders-endpoints-title': '🛒 Buyurtmalar API Endpointlari',
            'orders-endpoints-desc': 'Buyurtmalarni boshqarish uchun to\'liq API endpointlari ma\'lumotnomasi: buyurtma yaratish, holat kuzatish, buyurtma elementlarini boshqarish va buyurtma hayot tsiklini boshqarish.',
            'quick-nav-title': '🚀 Tezkor navigatsiya',
            'get-orders-nav': 'Buyurtmalar ro\'yxati',
            'get-order-nav': 'Buyurtma ma\'lumoti',
            'create-order-nav': 'Buyurtma yaratish',
            'update-order-nav': 'Buyurtmani yangilash',
            'cancel-order-nav': 'Buyurtmani bekor qilish',
            'get-orders-title': '📋 Buyurtmalar ro\'yxati',
            'get-orders-desc': 'Autentifikatsiya qilingan foydalanuvchi uchun filterlash imkoniyatlari bilan buyurtmalarning sahifalangan ro\'yxatini olish.',
            'get-order-title': '👁️ Bitta buyurtma ma\'lumoti',
            'get-order-desc': 'ID bo\'yicha muayyan buyurtma haqida batafsil ma\'lumot olish.',
            'endpoint-overview': 'Endpoint ko\'rinishi',
            'http-method': 'HTTP metod',
            'endpoint-url': 'Endpoint URL',
            'required-headers': 'Majburiy sarlavhalar',
            'auth-required': 'Autentifikatsiya talab qilinadi',
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
            'status-filter-desc': 'Buyurtma holati bo\'yicha filterlash (pending, confirmed, shipped, delivered, cancelled)',
            'date-from-desc': 'Ushbu sanadan buyurtmalarni filterlash (YYYY-MM-DD)',
            'date-to-desc': 'Ushbu sanagacha buyurtmalarni filterlash (YYYY-MM-DD)',
            'order-id-desc': 'Buyurtmaning noyob identifikatori',
            'example-request': 'So\'rov namunasi',
            'response-examples': 'Javob namunalari',
            'success-response': 'Muvaffaqiyatli javob',
            'not-found-response': 'Topilmadi javobi'
        },
        'ru': {
            'orders-endpoints': 'API заказов',
            'orders-endpoints-title': '🛒 Эндпоинты API заказов',
            'orders-endpoints-desc': 'Полная справка по API эндпоинтам управления заказами, включая создание заказов, отслеживание статуса, управление позициями заказа и обработку жизненного цикла заказа.',
            'quick-nav-title': '🚀 Быстрая навигация',
            'get-orders-nav': 'Список заказов',
            'get-order-nav': 'Информация о заказе',
            'create-order-nav': 'Создать заказ',
            'update-order-nav': 'Обновить заказ',
            'cancel-order-nav': 'Отменить заказ',
            'get-orders-title': '📋 Список заказов',
            'get-orders-desc': 'Получить пагинированный список заказов для аутентифицированного пользователя с возможностями фильтрации.',
            'get-order-title': '👁️ Информация о заказе',
            'get-order-desc': 'Получить подробную информацию о конкретном заказе по ID.',
            'endpoint-overview': 'Обзор эндпоинта',
            'http-method': 'HTTP метод',
            'endpoint-url': 'URL эндпоинта',
            'required-headers': 'Обязательные заголовки',
            'auth-required': 'Требуется аутентификация',
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
            'status-filter-desc': 'Фильтрация по статусу заказа (pending, confirmed, shipped, delivered, cancelled)',
            'date-from-desc': 'Фильтрация заказов с этой даты (YYYY-MM-DD)',
            'date-to-desc': 'Фильтрация заказов до этой даты (YYYY-MM-DD)',
            'order-id-desc': 'Уникальный идентификатор заказа',
            'example-request': 'Пример запроса',
            'response-examples': 'Примеры ответов',
            'success-response': 'Успешный ответ',
            'not-found-response': 'Ответ "Не найдено"'
        }
    };

    // Merge with existing translations
    Object.keys(ordersEndpointsTranslations).forEach(lang => {
        if (translations[lang]) {
            Object.assign(translations[lang], ordersEndpointsTranslations[lang]);
        }
    });
</script>
@endpush
