@extends('docs.layout')

@section('title', $title)

@section('breadcrumb')
<div class="breadcrumb">
    <a href="{{ route('docs.index') }}" data-translate="api-documentation">API Documentation</a>
    <i class="fas fa-chevron-right"></i>
    <span data-translate="api-tester">API Tester</span>
</div>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title" data-translate="api-tester-title">🧪 API Tester</h1>
    <p class="page-description" data-translate="api-tester-desc">
        Interactive API testing tool to test all available endpoints directly from your browser.
        Configure requests, set headers, and view responses in real-time.
    </p>
</div>

<!-- API Configuration -->
<div class="api-config-section">
    <h2 data-translate="api-config-title">⚙️ API Configuration</h2>

    <div class="config-form">
        <div class="form-group">
            <label for="base-url" data-translate="base-url-label">Base URL:</label>
            <input type="text" id="base-url" value="{{ $baseUrl }}" placeholder="Enter API base URL">
        </div>

        <div class="form-group">
            <label for="auth-token" data-translate="auth-token-label">Authorization Token:</label>
            <div class="token-input-group">
                <input type="password" id="auth-token" placeholder="Enter your Bearer token">
                <button type="button" class="toggle-token" onclick="toggleTokenVisibility()">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <small class="form-hint" data-translate="token-hint">
                Get your token by calling the login endpoint first
            </small>
        </div>
    </div>
</div>

<!-- Endpoint Testing Interface -->
<div class="api-tester-interface">
    <h2 data-translate="test-endpoint-title">🎯 Test Endpoint</h2>

    <div class="tester-form">
        <!-- Method and URL -->
        <div class="request-line">
            <div class="method-selector">
                <select id="http-method">
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                    <option value="PUT">PUT</option>
                    <option value="PATCH">PATCH</option>
                    <option value="DELETE">DELETE</option>
                </select>
            </div>
            <div class="url-input">
                <input type="text" id="endpoint-url" placeholder="/api/endpoint" data-translate-placeholder="endpoint-placeholder">
            </div>
            <button class="send-btn" onclick="sendRequest()" data-translate="send-request">
                <i class="fas fa-paper-plane"></i>
                Send Request
            </button>
        </div>

        <!-- Quick Endpoints -->
        <div class="quick-endpoints">
            <h3 data-translate="quick-endpoints-title">⚡ Quick Endpoints</h3>
            <div class="endpoint-buttons">
                <button class="endpoint-btn" onclick="setEndpoint('POST', '/auth/register')" data-translate="register-btn">
                    Register
                </button>
                <button class="endpoint-btn" onclick="setEndpoint('POST', '/auth/login')" data-translate="login-btn">
                    Login
                </button>
                <button class="endpoint-btn" onclick="setEndpoint('GET', '/user/profile')" data-translate="profile-btn">
                    Profile
                </button>
                <button class="endpoint-btn" onclick="setEndpoint('GET', '/products')" data-translate="products-btn">
                    Products
                </button>
                <button class="endpoint-btn" onclick="setEndpoint('GET', '/categories')" data-translate="categories-btn">
                    Categories
                </button>
                <button class="endpoint-btn" onclick="setEndpoint('GET', '/orders')" data-translate="orders-btn">
                    Orders
                </button>
            </div>
        </div>

        <!-- Headers -->
        <div class="headers-section">
            <h3 data-translate="headers-title">📋 Headers</h3>
            <div class="headers-container" id="headers-container">
                <div class="header-row">
                    <input type="text" class="header-key" placeholder="Header Name" data-translate-placeholder="header-name-placeholder">
                    <input type="text" class="header-value" placeholder="Header Value" data-translate-placeholder="header-value-placeholder">
                    <button class="remove-header" onclick="removeHeader(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <button class="add-header-btn" onclick="addHeader()" data-translate="add-header">
                <i class="fas fa-plus"></i>
                Add Header
            </button>
        </div>

        <!-- Query Parameters -->
        <div class="params-section">
            <h3 data-translate="params-title">🔍 Query Parameters</h3>
            <div class="params-container" id="params-container">
                <div class="param-row">
                    <input type="text" class="param-key" placeholder="Parameter Name" data-translate-placeholder="param-name-placeholder">
                    <input type="text" class="param-value" placeholder="Parameter Value" data-translate-placeholder="param-value-placeholder">
                    <button class="remove-param" onclick="removeParam(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <button class="add-param-btn" onclick="addParam()" data-translate="add-param">
                <i class="fas fa-plus"></i>
                Add Parameter
            </button>
        </div>

        <!-- Request Body -->
        <div class="body-section">
            <h3 data-translate="request-body-title">📝 Request Body</h3>
            <div class="body-type-selector">
                <label>
                    <input type="radio" name="body-type" value="none" checked onchange="toggleBodyType()">
                    <span data-translate="no-body">No Body</span>
                </label>
                <label>
                    <input type="radio" name="body-type" value="json" onchange="toggleBodyType()">
                    <span data-translate="json-body">JSON</span>
                </label>
                <label>
                    <input type="radio" name="body-type" value="form" onchange="toggleBodyType()">
                    <span data-translate="form-body">Form Data</span>
                </label>
            </div>

            <div id="json-body" class="body-input" style="display: none;">
                <textarea id="json-textarea" rows="10" placeholder='{"key": "value"}' data-translate-placeholder="json-placeholder"></textarea>
            </div>

            <div id="form-body" class="body-input" style="display: none;">
                <div class="form-data-container" id="form-data-container">
                    <div class="form-data-row">
                        <input type="text" class="form-key" placeholder="Key" data-translate-placeholder="form-key-placeholder">
                        <input type="text" class="form-value" placeholder="Value" data-translate-placeholder="form-value-placeholder">
                        <button class="remove-form-data" onclick="removeFormData(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <button class="add-form-data-btn" onclick="addFormData()" data-translate="add-form-data">
                    <i class="fas fa-plus"></i>
                    Add Form Data
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Response Section -->
<div class="response-section" id="response-section" style="display: none;">
    <h2 data-translate="response-title">📥 Response</h2>

    <div class="response-meta">
        <div class="response-status">
            <span data-translate="status-label">Status:</span>
            <span id="response-status" class="status-badge"></span>
        </div>
        <div class="response-time">
            <span data-translate="time-label">Time:</span>
            <span id="response-time"></span>
        </div>
        <div class="response-size">
            <span data-translate="size-label">Size:</span>
            <span id="response-size"></span>
        </div>
    </div>

    <div class="response-tabs">
        <button class="response-tab active" onclick="showResponseTab('body')" data-translate="response-body">Body</button>
        <button class="response-tab" onclick="showResponseTab('headers')" data-translate="response-headers">Headers</button>
    </div>

    <div class="response-content">
        <div id="response-body" class="response-tab-content">
            <div class="response-actions">
                <button class="copy-response-btn" onclick="copyResponse()" data-translate="copy-response">
                    <i class="fas fa-copy"></i>
                    Copy Response
                </button>
            </div>
            <pre id="response-body-content"></pre>
        </div>

        <div id="response-headers" class="response-tab-content" style="display: none;">
            <pre id="response-headers-content"></pre>
        </div>
    </div>
</div>

<!-- Loading Indicator -->
<div class="loading-indicator" id="loading-indicator" style="display: none;">
    <div class="spinner"></div>
    <span data-translate="sending-request">Sending request...</span>
</div>

<!-- Navigation Component -->
@include('docs.components.page-navigation', $navigation)

@endsection

@push('styles')
<style>
    /* Match the professional styling from other endpoint pages */

    /* Page Header - matching users.blade.php */
    .page-header {
        text-align: center;
        padding: 3rem 0 2rem;
        background: var(--docs-bg);
        color: white;
        border-radius: var(--radius-lg);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }

    .page-title {
        font-size: 3rem;
        font-weight: 800;
        margin: 0 0 1rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        position: relative;
        z-index: 1;
    }

    .page-description {
        font-size: 1.2rem;
        margin: 0;
        opacity: 0.95;
        max-width: 800px;
        margin: 0 auto;
        line-height: 1.6;
        position: relative;
        z-index: 1;
    }

    /* Main Cards - matching endpoint cards style */
    .api-config-section,
    .api-tester-interface,
    .response-section {
        margin: 4rem 0;
        padding: 2rem;
        background: var(--docs-content-bg);
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-lg);
        scroll-margin-top: 100px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .api-config-section h2,
    .api-tester-interface h2,
    .response-section h2 {
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        font-size: 1.875rem;
        border-bottom: 3px solid var(--primary-color);
        padding-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Form Styling - matching endpoint parameters */
    .config-form .form-group {
        margin-bottom: 1.5rem;
    }

    .config-form label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.95rem;
    }

    .config-form input[type="text"],
    .config-form input[type="password"] {
        width: 100%;
        padding: 0.875rem;
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-md);
        background: var(--docs-content-bg);
        color: var(--text-primary);
        font-size: 0.95rem;
        transition: var(--transition-fast);
    }

    .config-form input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(8, 124, 54, 0.1);
    }

    .token-input-group {
        position: relative;
    }

    .toggle-token {
        position: absolute;
        right: 0.875rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-secondary);
        cursor: pointer;
        padding: 0.5rem;
        border-radius: var(--radius-sm);
        transition: var(--transition-fast);
    }

    .toggle-token:hover {
        color: var(--primary-color);
        background: rgba(8, 124, 54, 0.1);
    }

    .form-hint {
        display: block;
        margin-top: 0.5rem;
        color: var(--text-secondary);
        font-size: 0.85rem;
        font-style: italic;
    }

    /* Request Line - matching endpoint header style */
    .request-line {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        align-items: stretch;
        padding: 1.5rem;
        background: var(--docs-bg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--docs-border);
    }

    .method-selector select {
        padding: 0.875rem;
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-md);
        background: var(--docs-content-bg);
        color: var(--text-primary);
        font-weight: 600;
        min-width: 120px;
        font-size: 0.9rem;
        transition: var(--transition-fast);
    }

    .method-selector select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(8, 124, 54, 0.1);
    }

    .url-input {
        flex: 1;
    }

    .url-input input {
        width: 100%;
        padding: 0.875rem;
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-md);
        background: var(--docs-content-bg);
        color: var(--text-primary);
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 0.9rem;
        transition: var(--transition-fast);
    }

    .url-input input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(8, 124, 54, 0.1);
    }

    .send-btn {
        background: linear-gradient(135deg, var(--primary-color), #20c997);
        color: white;
        border: none;
        padding: 0.875rem 2rem;
        border-radius: var(--radius-md);
        cursor: pointer;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: var(--transition-fast);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        font-size: 0.95rem;
    }

    .send-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(8, 124, 54, 0.15);
    }

    /* Quick Endpoints - matching nav-cards */
    .quick-endpoints {
        margin-bottom: 2rem;
        padding: 2rem;
        background: linear-gradient(135deg, rgba(8, 124, 54, 0.05) 0%, rgba(8, 124, 54, 0.02) 100%);
        border: 1px solid rgba(8, 124, 54, 0.2);
        border-radius: var(--radius-lg);
    }

    .quick-endpoints h3 {
        color: var(--text-primary);
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .endpoint-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
    }

    .endpoint-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem 1.5rem;
        background: var(--docs-content-bg);
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-md);
        cursor: pointer;
        font-weight: 500;
        transition: var(--transition-fast);
        color: var(--text-primary);
        text-decoration: none;
    }

    .endpoint-btn:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(8, 124, 54, 0.15);
    }

    /* Form Sections - matching params section */
    .headers-section,
    .params-section,
    .body-section {
        margin-bottom: 2rem;
        padding: 2rem;
        background: var(--docs-content-bg);
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-lg);
    }

    .headers-section h3,
    .params-section h3,
    .body-section h3 {
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 2px solid var(--docs-border);
        padding-bottom: 0.5rem;
    }

    .header-row,
    .param-row,
    .form-data-row {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 1rem;
        margin-bottom: 0.75rem;
        align-items: center;
        padding: 1rem;
        background: rgba(8, 124, 54, 0.02);
        border-radius: var(--radius-md);
        border: 1px solid var(--docs-border);
    }

    .header-key,
    .header-value,
    .param-key,
    .param-value,
    .form-key,
    .form-value {
        padding: 0.75rem;
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-md);
        background: var(--docs-content-bg);
        color: var(--text-primary);
        font-size: 0.9rem;
        transition: var(--transition-fast);
    }

    .header-key:focus,
    .header-value:focus,
    .param-key:focus,
    .param-value:focus,
    .form-key:focus,
    .form-value:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(8, 124, 54, 0.1);
    }

    .remove-header,
    .remove-param,
    .remove-form-data {
        background: linear-gradient(135deg, #dc3545, #e83e8c);
        color: white;
        border: none;
        padding: 0.75rem;
        border-radius: var(--radius-md);
        cursor: pointer;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition-fast);
    }

    .remove-header:hover,
    .remove-param:hover,
    .remove-form-data:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    .add-header-btn,
    .add-param-btn,
    .add-form-data-btn {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-md);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9rem;
        font-weight: 500;
        transition: var(--transition-fast);
        margin-top: 1rem;
    }

    .add-header-btn:hover,
    .add-param-btn:hover,
    .add-form-data-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    /* Body Type Selector */
    .body-type-selector {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .body-type-selector label {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        padding: 0.75rem 1rem;
        border-radius: var(--radius-md);
        transition: var(--transition-fast);
        font-weight: 500;
        border: 1px solid var(--docs-border);
        background: var(--docs-content-bg);
    }

    .body-type-selector label:hover {
        background: rgba(8, 124, 54, 0.1);
        border-color: var(--primary-color);
    }

    .body-type-selector input[type="radio"] {
        accent-color: var(--primary-color);
        transform: scale(1.1);
    }

    .body-input textarea {
        width: 100%;
        padding: 1rem;
        border: 1px solid var(--docs-border);
        border-radius: var(--radius-md);
        background: var(--docs-content-bg);
        color: var(--text-primary);
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 0.9rem;
        resize: vertical;
        min-height: 200px;
        transition: var(--transition-fast);
    }

    .body-input textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(8, 124, 54, 0.1);
    }

    /* Response Section - matching response styling */
    .response-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        padding: 1.5rem;
        background: var(--docs-bg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--docs-border);
    }

    .response-meta > div {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }

    .response-meta > div > span:first-child {
        font-weight: 600;
        color: var(--text-secondary);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: var(--radius-md);
        font-weight: 700;
        font-size: 0.875rem;
        color: white;
        text-align: center;
        min-width: 140px;
    }

    .status-badge.success {
        background: linear-gradient(135deg, #28a745, #20c997);
    }

    .status-badge.error {
        background: linear-gradient(135deg, #dc3545, #e83e8c);
    }

    .status-badge.warning {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: #000;
    }

    .response-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid var(--docs-border);
    }

    .response-tab {
        background: none;
        border: none;
        padding: 1rem 1.5rem;
        cursor: pointer;
        color: var(--text-secondary);
        border-bottom: 3px solid transparent;
        transition: var(--transition-fast);
        font-weight: 500;
        border-radius: var(--radius-md) var(--radius-md) 0 0;
    }

    .response-tab:hover {
        color: var(--primary-color);
        background: rgba(8, 124, 54, 0.05);
    }

    .response-tab.active {
        color: var(--primary-color);
        border-bottom-color: var(--primary-color);
        background: rgba(8, 124, 54, 0.1);
    }

    .response-actions {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 1rem;
    }

    .copy-response-btn {
        background: linear-gradient(135deg, #17a2b8, #6f42c1);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-md);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9rem;
        font-weight: 500;
        transition: var(--transition-fast);
    }

    .copy-response-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
    }

    .response-tab-content pre {
        background: var(--docs-code-bg);
        padding: 1.5rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--docs-border);
        overflow-x: auto;
        font-family: 'Monaco', 'Courier New', monospace;
        font-size: 0.9rem;
        color: var(--text-primary);
        white-space: pre-wrap;
        word-wrap: break-word;
        max-height: 500px;
        overflow-y: auto;
        line-height: 1.6;
    }

    /* Loading Indicator */
    .loading-indicator {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        background: var(--docs-content-bg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--docs-border);
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid var(--docs-border);
        border-top: 4px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 1.5rem;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loading-indicator span {
        color: var(--text-secondary);
        font-weight: 500;
        font-size: 1.1rem;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .page-title {
            font-size: 2.5rem;
        }

        .page-description {
            font-size: 1.1rem;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 2rem 1rem;
        }

        .page-title {
            font-size: 2rem;
        }

        .page-description {
            font-size: 1rem;
        }

        .api-config-section,
        .api-tester-interface,
        .response-section {
            padding: 1.5rem;
            margin: 2rem 0;
        }

        .request-line {
            flex-direction: column;
            align-items: stretch;
        }

        .response-meta {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .endpoint-buttons {
            grid-template-columns: 1fr;
        }

        .body-type-selector {
            flex-direction: column;
            gap: 1rem;
        }

        .header-row,
        .param-row,
        .form-data-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        .remove-header,
        .remove-param,
        .remove-form-data {
            align-self: flex-end;
            width: auto;
            padding: 0.5rem 1rem;
        }
    }

    @media (max-width: 480px) {
        .page-title {
            font-size: 1.75rem;
        }

        .api-config-section,
        .api-tester-interface,
        .response-section {
            padding: 1rem;
        }
    }

    /* Copy Feedback Animation */
    .copy-feedback {
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--primary-color);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: var(--radius-md);
        box-shadow: 0 4px 12px rgba(8, 124, 54, 0.3);
        z-index: 1000;
        animation: slideInRight 0.3s ease;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Focus indicators for accessibility */
    button:focus-visible,
    input:focus-visible,
    select:focus-visible,
    textarea:focus-visible {
        outline: 2px solid var(--primary-color);
        outline-offset: 2px;
    }
</style>
@endpush

@push('scripts')
<script>
    let requestStartTime;

    function toggleTokenVisibility() {
        const tokenInput = document.getElementById('auth-token');
        const toggleBtn = tokenInput.nextElementSibling.querySelector('i');

        if (tokenInput.type === 'password') {
            tokenInput.type = 'text';
            toggleBtn.className = 'fas fa-eye-slash';
        } else {
            tokenInput.type = 'password';
            toggleBtn.className = 'fas fa-eye';
        }
    }

    function setEndpoint(method, url) {
        document.getElementById('http-method').value = method;
        document.getElementById('endpoint-url').value = url;

        // Set common headers based on endpoint
        clearHeaders();
        addHeaderRow('Content-Type', 'application/json');
        addHeaderRow('Accept', 'application/json');

        // Add auth header if token is set and endpoint needs auth
        const token = document.getElementById('auth-token').value;
        if (token && (url.includes('/user') || url.includes('/orders') || url.includes('/auth/logout'))) {
            addHeaderRow('Authorization', `Bearer ${token}`);
        }

        // Set sample body for POST endpoints
        if (method === 'POST') {
            setSampleBody(url);
        }
    }

    function setSampleBody(url) {
        const jsonRadio = document.querySelector('input[name="body-type"][value="json"]');
        jsonRadio.checked = true;
        toggleBodyType();

        const jsonTextarea = document.getElementById('json-textarea');

        if (url.includes('/auth/register')) {
            jsonTextarea.value = JSON.stringify({
                name: "John Doe",
                email: "john@example.com",
                password: "password123",
                password_confirmation: "password123",
                phone: "+1234567890"
            }, null, 2);
        } else if (url.includes('/auth/login')) {
            jsonTextarea.value = JSON.stringify({
                email: "john@example.com",
                password: "password123",
                remember: true
            }, null, 2);
        }
    }

    function addHeader() {
        const container = document.getElementById('headers-container');
        addHeaderRow('', '');
    }

    function addHeaderRow(key = '', value = '') {
        const container = document.getElementById('headers-container');
        const headerRow = document.createElement('div');
        headerRow.className = 'header-row';
        headerRow.innerHTML = `
            <input type="text" class="header-key" placeholder="Header Name" value="${key}">
            <input type="text" class="header-value" placeholder="Header Value" value="${value}">
            <button class="remove-header" onclick="removeHeader(this)">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(headerRow);
    }

    function removeHeader(btn) {
        btn.parentElement.remove();
    }

    function clearHeaders() {
        document.getElementById('headers-container').innerHTML = '';
    }

    function addParam() {
        const container = document.getElementById('params-container');
        const paramRow = document.createElement('div');
        paramRow.className = 'param-row';
        paramRow.innerHTML = `
            <input type="text" class="param-key" placeholder="Parameter Name">
            <input type="text" class="param-value" placeholder="Parameter Value">
            <button class="remove-param" onclick="removeParam(this)">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(paramRow);
    }

    function removeParam(btn) {
        btn.parentElement.remove();
    }

    function addFormData() {
        const container = document.getElementById('form-data-container');
        const formDataRow = document.createElement('div');
        formDataRow.className = 'form-data-row';
        formDataRow.innerHTML = `
            <input type="text" class="form-key" placeholder="Key">
            <input type="text" class="form-value" placeholder="Value">
            <button class="remove-form-data" onclick="removeFormData(this)">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(formDataRow);
    }

    function removeFormData(btn) {
        btn.parentElement.remove();
    }

    function toggleBodyType() {
        const selectedType = document.querySelector('input[name="body-type"]:checked').value;

        document.getElementById('json-body').style.display = selectedType === 'json' ? 'block' : 'none';
        document.getElementById('form-body').style.display = selectedType === 'form' ? 'block' : 'none';
    }

    function sendRequest() {
        const method = document.getElementById('http-method').value;
        const baseUrl = document.getElementById('base-url').value.replace(/\/$/, '');
        const endpoint = document.getElementById('endpoint-url').value;
        const url = baseUrl + endpoint;

        // Show loading
        document.getElementById('loading-indicator').style.display = 'flex';
        document.getElementById('response-section').style.display = 'none';

        requestStartTime = Date.now();

        // Collect headers
        const headers = {};
        document.querySelectorAll('#headers-container .header-row').forEach(row => {
            const key = row.querySelector('.header-key').value.trim();
            const value = row.querySelector('.header-value').value.trim();
            if (key && value) {
                headers[key] = value;
            }
        });

        // Collect query parameters
        const params = new URLSearchParams();
        document.querySelectorAll('#params-container .param-row').forEach(row => {
            const key = row.querySelector('.param-key').value.trim();
            const value = row.querySelector('.param-value').value.trim();
            if (key && value) {
                params.append(key, value);
            }
        });

        // Build final URL with query parameters
        const finalUrl = params.toString() ? `${url}?${params.toString()}` : url;

        // Prepare request options
        const requestOptions = {
            method: method,
            headers: headers
        };

        // Add body if needed
        const bodyType = document.querySelector('input[name="body-type"]:checked').value;
        if (method !== 'GET' && bodyType !== 'none') {
            if (bodyType === 'json') {
                const jsonBody = document.getElementById('json-textarea').value.trim();
                if (jsonBody) {
                    try {
                        JSON.parse(jsonBody); // Validate JSON
                        requestOptions.body = jsonBody;
                    } catch (e) {
                        alert('Invalid JSON format');
                        document.getElementById('loading-indicator').style.display = 'none';
                        return;
                    }
                }
            } else if (bodyType === 'form') {
                const formData = new FormData();
                document.querySelectorAll('#form-data-container .form-data-row').forEach(row => {
                    const key = row.querySelector('.form-key').value.trim();
                    const value = row.querySelector('.form-value').value.trim();
                    if (key && value) {
                        formData.append(key, value);
                    }
                });
                requestOptions.body = formData;
                delete headers['Content-Type']; // Let browser set it for FormData
            }
        }

        // Send request
        fetch(finalUrl, requestOptions)
            .then(response => {
                const responseTime = Date.now() - requestStartTime;

                // Get response headers
                const responseHeaders = {};
                response.headers.forEach((value, key) => {
                    responseHeaders[key] = value;
                });

                return response.text().then(text => {
                    let jsonResponse;
                    try {
                        jsonResponse = JSON.parse(text);
                    } catch (e) {
                        jsonResponse = text;
                    }

                    return {
                        status: response.status,
                        statusText: response.statusText,
                        headers: responseHeaders,
                        data: jsonResponse,
                        size: new Blob([text]).size,
                        time: responseTime
                    };
                });
            })
            .then(response => {
                displayResponse(response);
            })
            .catch(error => {
                displayError(error);
            })
            .finally(() => {
                document.getElementById('loading-indicator').style.display = 'none';
            });
    }

    function displayResponse(response) {
        document.getElementById('response-section').style.display = 'block';

        // Update status
        const statusElement = document.getElementById('response-status');
        statusElement.textContent = `${response.status} ${response.statusText}`;
        statusElement.className = 'status-badge';

        if (response.status >= 200 && response.status < 300) {
            statusElement.classList.add('success');
        } else if (response.status >= 400 && response.status < 500) {
            statusElement.classList.add('warning');
        } else {
            statusElement.classList.add('error');
        }

        // Update time and size
        document.getElementById('response-time').textContent = `${response.time}ms`;
        document.getElementById('response-size').textContent = `${(response.size / 1024).toFixed(2)} KB`;

        // Update body
        const bodyContent = document.getElementById('response-body-content');
        if (typeof response.data === 'object') {
            bodyContent.textContent = JSON.stringify(response.data, null, 2);
        } else {
            bodyContent.textContent = response.data;
        }

        // Update headers
        const headersContent = document.getElementById('response-headers-content');
        headersContent.textContent = JSON.stringify(response.headers, null, 2);

        // Show body tab by default
        showResponseTab('body');
    }

    function displayError(error) {
        document.getElementById('response-section').style.display = 'block';

        // Update status
        const statusElement = document.getElementById('response-status');
        statusElement.textContent = 'Network Error';
        statusElement.className = 'status-badge error';

        // Update time and size
        document.getElementById('response-time').textContent = 'N/A';
        document.getElementById('response-size').textContent = 'N/A';

        // Update body
        const bodyContent = document.getElementById('response-body-content');
        bodyContent.textContent = `Error: ${error.message}`;

        // Clear headers
        const headersContent = document.getElementById('response-headers-content');
        headersContent.textContent = '{}';
    }

    function showResponseTab(tab) {
        // Update tab buttons
        document.querySelectorAll('.response-tab').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');

        // Show/hide content
        document.querySelectorAll('.response-tab-content').forEach(content => {
            content.style.display = 'none';
        });
        document.getElementById(`response-${tab}`).style.display = 'block';
    }

    function copyResponse() {
        const content = document.getElementById('response-body-content').textContent;
        navigator.clipboard.writeText(content).then(() => {
            const btn = document.querySelector('.copy-response-btn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
            btn.style.background = 'var(--success-color)';

            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.style.background = 'var(--info-color)';
            }, 2000);
        });
    }

    // Initialize with default header
    document.addEventListener('DOMContentLoaded', function() {
        addHeaderRow('Content-Type', 'application/json');
        addHeaderRow('Accept', 'application/json');
    });

    // Additional translations for API tester
    const apiTesterTranslations = {
        'en': {
            'api-tester': 'API Tester',
            'api-tester-title': '🧪 API Tester',
            'api-tester-desc': 'Interactive API testing tool to test all available endpoints directly from your browser. Configure requests, set headers, and view responses in real-time.',
            'api-config-title': '⚙️ API Configuration',
            'base-url-label': 'Base URL:',
            'auth-token-label': 'Authorization Token:',
            'token-hint': 'Get your token by calling the login endpoint first',
            'test-endpoint-title': '🎯 Test Endpoint',
            'endpoint-placeholder': '/api/endpoint',
            'send-request': 'Send Request',
            'quick-endpoints-title': '⚡ Quick Endpoints',
            'register-btn': 'Register',
            'login-btn': 'Login',
            'profile-btn': 'Profile',
            'products-btn': 'Products',
            'categories-btn': 'Categories',
            'orders-btn': 'Orders',
            'headers-title': '📋 Headers',
            'header-name-placeholder': 'Header Name',
            'header-value-placeholder': 'Header Value',
            'add-header': 'Add Header',
            'params-title': '🔍 Query Parameters',
            'param-name-placeholder': 'Parameter Name',
            'param-value-placeholder': 'Parameter Value',
            'add-param': 'Add Parameter',
            'request-body-title': '📝 Request Body',
            'no-body': 'No Body',
            'json-body': 'JSON',
            'form-body': 'Form Data',
            'json-placeholder': '{"key": "value"}',
            'form-key-placeholder': 'Key',
            'form-value-placeholder': 'Value',
            'add-form-data': 'Add Form Data',
            'response-title': '📥 Response',
            'status-label': 'Status:',
            'time-label': 'Time:',
            'size-label': 'Size:',
            'response-body': 'Body',
            'response-headers': 'Headers',
            'copy-response': 'Copy Response',
            'sending-request': 'Sending request...'
        },
        'uz': {
            'api-tester': 'API Sinchikovi',
            'api-tester-title': '🧪 API Sinchikovi',
            'api-tester-desc': 'Barcha mavjud endpointlarni bevosita brauzeringizdan sinash uchun interaktiv API sinash vositasi. So\'rovlarni sozlang, sarlavhalarni belgilang va javoblarni real vaqtda ko\'ring.',
            'api-config-title': '⚙️ API Konfiguratsiyasi',
            'base-url-label': 'Asosiy URL:',
            'auth-token-label': 'Avtorizatsiya Tokeni:',
            'token-hint': 'Avval login endpointini chaqirib tokeningizni oling',
            'test-endpoint-title': '🎯 Endpointni Sinash',
            'endpoint-placeholder': '/api/endpoint',
            'send-request': 'So\'rov Yuborish',
            'quick-endpoints-title': '⚡ Tezkor Endpointlar',
            'register-btn': 'Ro\'yxatdan o\'tish',
            'login-btn': 'Kirish',
            'profile-btn': 'Profil',
            'products-btn': 'Mahsulotlar',
            'categories-btn': 'Kategoriyalar',
            'orders-btn': 'Buyurtmalar',
            'headers-title': '📋 Sarlavhalar',
            'header-name-placeholder': 'Sarlavha Nomi',
            'header-value-placeholder': 'Sarlavha Qiymati',
            'add-header': 'Sarlavha Qo\'shish',
            'params-title': '🔍 So\'rov Parametrlari',
            'param-name-placeholder': 'Parametr Nomi',
            'param-value-placeholder': 'Parametr Qiymati',
            'add-param': 'Parametr Qo\'shish',
            'request-body-title': '📝 So\'rov Tanasi',
            'no-body': 'Tana Yo\'q',
            'json-body': 'JSON',
            'form-body': 'Form Ma\'lumotlari',
            'json-placeholder': '{"kalit": "qiymat"}',
            'form-key-placeholder': 'Kalit',
            'form-value-placeholder': 'Qiymat',
            'add-form-data': 'Form Ma\'lumoti Qo\'shish',
            'response-title': '📥 Javob',
            'status-label': 'Holat:',
            'time-label': 'Vaqt:',
            'size-label': 'Hajm:',
            'response-body': 'Tana',
            'response-headers': 'Sarlavhalar',
            'copy-response': 'Javobni Nusxalash',
            'sending-request': 'So\'rov yuborilmoqda...'
        },
        'ru': {
            'api-tester': 'Тестер API',
            'api-tester-title': '🧪 Тестер API',
            'api-tester-desc': 'Интерактивный инструмент тестирования API для проверки всех доступных эндпоинтов прямо из вашего браузера. Настраивайте запросы, устанавливайте заголовки и просматривайте ответы в реальном времени.',
            'api-config-title': '⚙️ Конфигурация API',
            'base-url-label': 'Базовый URL:',
            'auth-token-label': 'Токен авторизации:',
            'token-hint': 'Получите токен, сначала вызвав эндпоинт входа',
            'test-endpoint-title': '🎯 Тестировать эндпоинт',
            'endpoint-placeholder': '/api/endpoint',
            'send-request': 'Отправить запрос',
            'quick-endpoints-title': '⚡ Быстрые эндпоинты',
            'register-btn': 'Регистрация',
            'login-btn': 'Вход',
            'profile-btn': 'Профиль',
            'products-btn': 'Товары',
            'categories-btn': 'Категории',
            'orders-btn': 'Заказы',
            'headers-title': '📋 Заголовки',
            'header-name-placeholder': 'Название заголовка',
            'header-value-placeholder': 'Значение заголовка',
            'add-header': 'Добавить заголовок',
            'params-title': '🔍 Параметры запроса',
            'param-name-placeholder': 'Название параметра',
            'param-value-placeholder': 'Значение параметра',
            'add-param': 'Добавить параметр',
            'request-body-title': '📝 Тело запроса',
            'no-body': 'Без тела',
            'json-body': 'JSON',
            'form-body': 'Данные формы',
            'json-placeholder': '{"ключ": "значение"}',
            'form-key-placeholder': 'Ключ',
            'form-value-placeholder': 'Значение',
            'add-form-data': 'Добавить данные формы',
            'response-title': '📥 Ответ',
            'status-label': 'Статус:',
            'time-label': 'Время:',
            'size-label': 'Размер:',
            'response-body': 'Тело',
            'response-headers': 'Заголовки',
            'copy-response': 'Копировать ответ',
            'sending-request': 'Отправка запроса...'
        }
    };

    // Merge with existing translations
    Object.keys(apiTesterTranslations).forEach(lang => {
        if (translations[lang]) {
            Object.assign(translations[lang], apiTesterTranslations[lang]);
        }
    });
</script>
@endpush
