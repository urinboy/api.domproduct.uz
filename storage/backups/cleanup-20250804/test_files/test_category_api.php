<?php

// Category Management API Test
$base_url = 'http://localhost:8080';

// Login va token olish
function getToken() {
    global $base_url;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$base_url/auth/login");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'email' => 'admin@domproduct.uz',
        'password' => 'admin123',
        'device_name' => 'category_test'
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    return $data['data']['token'] ?? null;
}

function apiRequest($method, $endpoint, $data = null, $token = null) {
    global $base_url;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$base_url$endpoint");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $headers = ['Accept: application/json'];
    if ($token) {
        $headers[] = "Authorization: Bearer $token";
    }
    if ($data) {
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['code' => $http_code, 'response' => $response];
}

echo "=== CATEGORY MANAGEMENT API TEST ===\n\n";

// Get token
echo "1. Getting admin token...\n";
$token = getToken();
if (!$token) {
    echo "❌ Failed to get token\n";
    exit;
}
echo "✅ Token received: " . substr($token, 0, 20) . "...\n\n";

// Test 1: Get public categories
echo "2. Testing public categories list...\n";
$result = apiRequest('GET', '/v1/categories');
echo "HTTP Code: {$result['code']}\n";
$categories = json_decode($result['response'], true);
if ($categories['success']) {
    echo "✅ Public categories retrieved successfully\n";
    echo "Found " . count($categories['data']) . " categories\n\n";
} else {
    echo "❌ Public categories failed\n\n";
}

// Test 2: Get category tree
echo "3. Testing category tree...\n";
$result = apiRequest('GET', '/v1/categories/tree');
echo "HTTP Code: {$result['code']}\n";
$tree = json_decode($result['response'], true);
if ($tree['success']) {
    echo "✅ Category tree retrieved successfully\n";
    echo "Root categories: " . count($tree['data']) . "\n\n";
} else {
    echo "❌ Category tree failed\n\n";
}

// Test 3: Admin - Get all categories
echo "4. Testing admin categories list...\n";
$result = apiRequest('GET', '/admin/categories', null, $token);
echo "HTTP Code: {$result['code']}\n";
$adminCategories = json_decode($result['response'], true);
if ($adminCategories['success']) {
    echo "✅ Admin categories retrieved successfully\n";
    echo "Total categories: " . count($adminCategories['data']['data']) . "\n\n";
} else {
    echo "❌ Admin categories failed\n\n";
}

// Test 4: Create new category
echo "5. Testing category creation...\n";
$newCategory = [
    'translations' => [
        [
            'language_code' => 'uz',
            'name' => 'Test Kategoriya',
            'description' => 'Test kategoriya tavsiflari',
            'meta_title' => 'Test Kategoriya'
        ],
        [
            'language_code' => 'en',
            'name' => 'Test Category',
            'description' => 'Test category description',
            'meta_title' => 'Test Category'
        ]
    ],
    'sort_order' => 1,
    'is_active' => true
];

$result = apiRequest('POST', '/admin/categories', $newCategory, $token);
echo "HTTP Code: {$result['code']}\n";
$created = json_decode($result['response'], true);
if ($created['success']) {
    echo "✅ Category created successfully\n";
    $categoryId = $created['data']['id'];
    echo "Category ID: $categoryId\n\n";
} else {
    echo "❌ Category creation failed\n";
    echo "Error: " . ($created['message'] ?? 'Unknown error') . "\n\n";
    $categoryId = null;
}

// Test 5: Get category by ID
if ($categoryId) {
    echo "6. Testing get category by ID...\n";
    $result = apiRequest('GET', "/admin/categories/$categoryId", null, $token);
    echo "HTTP Code: {$result['code']}\n";
    $category = json_decode($result['response'], true);
    if ($category['success']) {
        echo "✅ Category retrieved by ID successfully\n";
        echo "Category name: " . $category['data']['name'] . "\n\n";
    } else {
        echo "❌ Get category by ID failed\n\n";
    }

    // Test 6: Update category
    echo "7. Testing category update...\n";
    $updateData = [
        'translations' => [
            [
                'language_code' => 'uz',
                'name' => 'Yangilangan Test Kategoriya',
                'description' => 'Yangilangan tavsiflari'
            ]
        ],
        'sort_order' => 2
    ];

    $result = apiRequest('PUT', "/admin/categories/$categoryId", $updateData, $token);
    echo "HTTP Code: {$result['code']}\n";
    $updated = json_decode($result['response'], true);
    if ($updated['success']) {
        echo "✅ Category updated successfully\n";
        echo "Updated name: " . $updated['data']['name'] . "\n\n";
    } else {
        echo "❌ Category update failed\n\n";
    }

    // Test 7: Delete category
    echo "8. Testing category deletion...\n";
    $result = apiRequest('DELETE', "/admin/categories/$categoryId", null, $token);
    echo "HTTP Code: {$result['code']}\n";
    $deleted = json_decode($result['response'], true);
    if ($deleted['success']) {
        echo "✅ Category deleted successfully\n\n";
    } else {
        echo "❌ Category deletion failed\n\n";
    }
}

// Test 8: Get category statistics
echo "9. Testing category statistics...\n";
$result = apiRequest('GET', '/admin/categories/statistics', null, $token);
echo "HTTP Code: {$result['code']}\n";
$stats = json_decode($result['response'], true);
if ($stats['success']) {
    echo "✅ Category statistics retrieved successfully\n";
    echo "Total categories: " . $stats['data']['total_categories'] . "\n";
    echo "Active categories: " . $stats['data']['active_categories'] . "\n\n";
} else {
    echo "❌ Category statistics failed\n\n";
}

echo "=== CATEGORY TEST COMPLETED ===\n";
