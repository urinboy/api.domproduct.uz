@extends('docs.layout')

@section('title', $title)

@section('breadcrumb')
<div class="breadcrumb">
    <i class="fas fa-home"></i>
    <span data-translate="api-documentation">API Documentation</span>
</div>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title" data-translate="main-title">🚀 DOM Product API Documentation</h1>
    <p class="page-description" data-translate="main-subtitle">
        Welcome to the comprehensive API documentation for DOM Product Project.
        This guide will help you integrate with our e-commerce platform quickly and efficiently.
    </p>
</div><!-- API Overview -->
<div class="api-overview">
    <div class="overview-cards">
        <div class="overview-card">
            <div class="card-icon">
                <i class="fas fa-server"></i>
            </div>
            <div class="card-content">
                <h3 data-translate="base-url">Base URL</h3>
                <code class="base-url">{{ $baseUrl }}</code>
                <p data-translate="base-url-desc">All API endpoints are relative to this base URL</p>
            </div>
        </div>

        <div class="overview-card">
            <div class="card-icon">
                <i class="fas fa-code-branch"></i>
            </div>
            <div class="card-content">
                <h3 data-translate="version">Version</h3>
                <span class="version-badge">v{{ $version }}</span>
                <p data-translate="version-desc">Current API version with full backward compatibility</p>
            </div>
        </div>

        <div class="overview-card">
            <div class="card-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="card-content">
                <h3 data-translate="auth-title">Authentication</h3>
                <span class="auth-type">Bearer Token</span>
                <p data-translate="auth-desc">Secure authentication using JSON Web Tokens</p>
            </div>
        </div>

        <div class="overview-card">
            <div class="card-icon">
                <i class="fas fa-file-code"></i>
            </div>
            <div class="card-content">
                <h3 data-translate="response-format">Response Format</h3>
                <span class="format-type">JSON</span>
                <p data-translate="response-format-desc">All responses are returned in JSON format</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Start -->
<div class="quick-start-section">
    <h2 data-translate="quick-start-title">🏃‍♂️ Quick Start</h2>
    <p data-translate="quick-start-desc">Get started with DOM Product API in just a few steps:</p>

    <div class="quick-steps">
        <div class="step">
            <div class="step-number">1</div>
            <div class="step-content">
                <h4 data-translate="step-1-title">Register an Account</h4>
                <p data-translate="step-1-desc">Create a new user account or use existing credentials</p>
                <a href="{{ route('docs.authentication') }}" class="step-link" data-translate="learn-auth">Learn about Authentication →</a>
            </div>
        </div>

        <div class="step">
            <div class="step-number">2</div>
            <div class="step-content">
                <h4 data-translate="step-2-title">Get Access Token</h4>
                <p data-translate="step-2-desc">Authenticate and receive your API access token</p>
                <a href="{{ route('docs.getting-started') }}" class="step-link" data-translate="see-guide">See Getting Started Guide →</a>
            </div>
        </div>

        <div class="step">
            <div class="step-number">3</div>
            <div class="step-content">
                <h4 data-translate="step-3-title">Make API Calls</h4>
                <p data-translate="step-3-desc">Start making requests to our API endpoints</p>
                <a href="{{ route('docs.endpoints', 'products') }}" class="step-link" data-translate="explore-endpoints">Explore API Endpoints →</a>
            </div>
        </div>
    </div>
</div><!-- API Features -->
<div class="api-features">
    <h2 data-translate="api-features">✨ API Features</h2>
    <div class="features-grid">
        <div class="feature">
            <div class="feature-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3 data-translate="user-management">User Management</h3>
            <p data-translate="user-management-desc">Complete user registration, authentication, and profile management</p>
        </div>

        <div class="feature">
            <div class="feature-icon">
                <i class="fas fa-box"></i>
            </div>
            <h3 data-translate="product-catalog">Product Catalog</h3>
            <p data-translate="product-catalog-desc">Manage products, categories, inventory, and pricing</p>
        </div>

        <div class="feature">
            <div class="feature-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3 data-translate="order-processing">Order Processing</h3>
            <p data-translate="order-processing-desc">Handle orders, payments, and order status management</p>
        </div>

        <div class="feature">
            <div class="feature-icon">
                <i class="fas fa-heart"></i>
            </div>
            <h3 data-translate="wishlist-favorites">Wishlist & Favorites</h3>
            <p data-translate="wishlist-favorites-desc">User wishlist and favorite products functionality</p>
        </div>

        <div class="feature">
            <div class="feature-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <h3 data-translate="address-management">Address Management</h3>
            <p data-translate="address-management-desc">User addresses, shipping locations, and delivery management</p>
        </div>

        <div class="feature">
            <div class="feature-icon">
                <i class="fas fa-bell"></i>
            </div>
            <h3 data-translate="notifications">Notifications</h3>
            <p data-translate="notifications-desc">Real-time notifications and messaging system</p>
        </div>
    </div>
</div>

<!-- Example Request -->
<div class="example-section">
    <h2 data-translate="example-request">📋 Example Request</h2>
    <p data-translate="example-request-desc">Here's a simple example of how to make an API request:</p>

    <div class="code-example">
        <div class="code-header">
            <span class="code-title">GET /api/products</span>
            <button class="copy-btn" onclick="copyCode('example-curl')">
                <i class="fas fa-copy"></i>
            </button>
        </div>
        <pre><code id="example-curl" class="language-bash">curl -X GET "{{ $baseUrl }}/products" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"</code></pre>
    </div>

    <div class="response-example">
        <h4 data-translate="response">Response:</h4>
        <pre><code class="language-json">{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Sample Product",
      "price": 29.99,
      "category_id": 1,
      "created_at": "2025-08-01T10:00:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 1
  }
}</code></pre>
    </div>
</div>@push('styles')
<style>
    /* API Overview Cards */
    .api-overview {
        margin: 3rem 0;
    }

    .overview-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .overview-card {
        background: var(--docs-content-bg);
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        transition: var(--transition-normal);
        box-shadow: var(--shadow-sm);
    }

    .overview-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .card-icon {
        width: 50px;
        height: 50px;
        background: rgba(8, 124, 54, 0.1);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .card-icon i {
        font-size: 1.5rem;
        color: var(--primary-color);
    }

    .card-content h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .base-url {
        background: var(--docs-code-bg);
        color: #61dafb;
        padding: 0.25rem 0.5rem;
        border-radius: var(--radius-sm);
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 0.875rem;
        display: inline-block;
        margin-bottom: 0.5rem;
    }

    .version-badge, .auth-type, .format-type {
        background: var(--primary-color);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: var(--radius-sm);
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-block;
        margin-bottom: 0.5rem;
    }

    .card-content p {
        color: var(--text-secondary);
        font-size: 0.875rem;
        line-height: 1.5;
    }

    /* Quick Start Section */
    .quick-start-section {
        margin: 3rem 0;
        padding: 2rem;
        background: rgba(8, 124, 54, 0.05);
        border-radius: var(--radius-lg);
        border: 1px solid rgba(8, 124, 54, 0.1);
    }

    .quick-start-section h2 {
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-size: 1.75rem;
    }

    .quick-steps {
        display: grid;
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .step {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.5rem;
        background: var(--docs-content-bg);
        border-radius: var(--radius-md);
        border: 1px solid var(--docs-border);
    }

    .step-number {
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

    .step-content h4 {
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 1.125rem;
    }

    .step-content p {
        color: var(--text-secondary);
        margin-bottom: 0.75rem;
        line-height: 1.6;
    }

    .step-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .step-link:hover {
        text-decoration: underline;
    }

    /* API Features */
    .api-features {
        margin: 3rem 0;
    }

    .api-features h2 {
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        font-size: 1.75rem;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .feature {
        padding: 1.5rem;
        background: var(--docs-content-bg);
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-lg);
        transition: var(--transition-normal);
    }

    .feature:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .feature-icon {
        width: 45px;
        height: 45px;
        background: rgba(8, 124, 54, 0.1);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .feature-icon i {
        font-size: 1.25rem;
        color: var(--primary-color);
    }

    .feature h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .feature p {
        color: var(--text-secondary);
        line-height: 1.6;
        font-size: 0.875rem;
    }

    /* Example Section */
    .example-section {
        margin: 3rem 0;
    }

    .example-section h2 {
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-size: 1.75rem;
    }

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

    /* Responsive */
    @media (max-width: 768px) {
        .overview-cards {
            grid-template-columns: 1fr;
        }

        .features-grid {
            grid-template-columns: 1fr;
        }

        .step {
            flex-direction: column;
            text-align: center;
        }

        .step-number {
            align-self: center;
        }
    }
</style>
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
