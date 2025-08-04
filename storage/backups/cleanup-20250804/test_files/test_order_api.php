<?php

// Simple test script for Order API
require_once 'vendor/autoload.php';

// Set base URL
$baseUrl = 'http://127.0.0.1:8000';

function makeRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    $defaultHeaders = [
        'Accept: application/json',
        'Content-Type: application/json'
    ];

    $headers = array_merge($defaultHeaders, $headers);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'code' => $httpCode,
        'data' => json_decode($response, true),
        'raw' => $response
    ];
}

echo "=== Testing Order API ===\n\n";

// First ensure we have a cart with items
echo "0. Setting up test cart...\n";
$response = makeRequest("$baseUrl/test-order", 'GET');
echo "Test cart setup - Code: " . $response['code'] . "\n";
if ($response['data']) {
    echo "Cart total: " . $response['data']['data']['cart_total'] . " UZS\n";
    echo "Items count: " . $response['data']['data']['items_count'] . "\n\n";
}

// Step 1: Login to get auth token
echo "1. Logging in...\n";
$loginData = [
    'email' => 'testorder@test.com',
    'password' => 'password123'
];

$response = makeRequest("$baseUrl/auth/login", 'POST', $loginData);
echo "Login Response Code: " . $response['code'] . "\n";

if ($response['data'] && isset($response['data']['data']['token'])) {
    $token = $response['data']['data']['token'];
    echo "✅ Login successful! Token: " . substr($token, 0, 20) . "...\n\n";

    $authHeaders = ['Authorization: Bearer ' . $token];

    // Step 2: Add items to cart
    echo "2. Adding items to cart...\n";
    $cartItems = [
        ['product_id' => 1, 'quantity' => 2],
        ['product_id' => 2, 'quantity' => 1]
    ];

    foreach ($cartItems as $item) {
        $response = makeRequest("$baseUrl/cart/add", 'POST', $item, $authHeaders);
        echo "Add to cart: Product {$item['product_id']} - Code: {$response['code']}\n";
        if ($response['code'] !== 201 && isset($response['data'])) {
            echo "Error: " . json_encode($response['data']) . "\n";
        }
    }

    // Step 3: Check cart summary
    echo "\n3. Checking cart summary...\n";
    $response = makeRequest("$baseUrl/cart/summary", 'GET', null, $authHeaders);
    echo "Cart summary - Code: " . $response['code'] . "\n";
    if ($response['data']) {
        echo "Cart total: " . ($response['data']['data']['total_amount'] ?? 'N/A') . " UZS\n";
        echo "Cart items count: " . ($response['data']['data']['total_items'] ?? 'N/A') . "\n";
        echo "Full response: " . json_encode($response['data'], JSON_PRETTY_PRINT) . "\n";
    }

    // Step 4: Create order
    echo "\n4. Creating order...\n";
    $orderData = [
        'delivery_method' => 'standard',
        'delivery_address' => [
            'first_name' => 'Test',
            'last_name' => 'User',
            'phone' => '+998901234567',
            'street_address' => 'Test Street 123',
            'city' => 'Tashkent',
            'region' => 'Tashkent',
            'country' => 'Uzbekistan'
        ],
        'payment_method' => 'cash',
        'special_instructions' => 'Test order from API'
    ];

    $response = makeRequest("$baseUrl/orders", 'POST', $orderData, $authHeaders);
    echo "Create order - Code: " . $response['code'] . "\n";

    if ($response['data']) {
        if (isset($response['data']['data']['order']['id'])) {
            $orderId = $response['data']['data']['order']['id'];
            echo "✅ Order created successfully! ID: $orderId\n";
            echo "Order Number: " . $response['data']['data']['order']['order_number'] . "\n";
            echo "Total Amount: " . $response['data']['data']['order']['total_amount'] . " UZS\n";

            // Step 5: Get order details
            echo "\n5. Getting order details...\n";
            $response = makeRequest("$baseUrl/orders/$orderId", 'GET', null, $authHeaders);
            echo "Get order details - Code: " . $response['code'] . "\n";

            if ($response['data']) {
                echo "Order status: " . $response['data']['data']['order']['status'] . "\n";
                echo "Items count: " . count($response['data']['data']['items']) . "\n";
            }

            // Step 6: Get user orders
            echo "\n6. Getting user orders list...\n";
            $response = makeRequest("$baseUrl/orders", 'GET', null, $authHeaders);
            echo "Get orders list - Code: " . $response['code'] . "\n";

            if ($response['data']) {
                echo "Total orders: " . count($response['data']['data']['orders']) . "\n";
            }

        } else {
            echo "❌ Order creation failed:\n";
            echo json_encode($response['data'], JSON_PRETTY_PRINT) . "\n";
        }
    } else {
        echo "❌ No response data\n";
        echo "Raw response: " . $response['raw'] . "\n";
    }

} else {
    echo "❌ Login failed!\n";
    echo "Response: " . json_encode($response['data'], JSON_PRETTY_PRINT) . "\n";
}

echo "\n=== Test Complete ===\n";
