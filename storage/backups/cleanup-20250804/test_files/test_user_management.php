<?php

// User Management API Test
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
        'device_name' => 'api_test'
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

echo "=== USER MANAGEMENT API TEST ===\n\n";

// Get token
echo "1. Getting admin token...\n";
$token = getToken();
if (!$token) {
    echo "❌ Failed to get token\n";
    exit;
}
echo "✅ Token received: " . substr($token, 0, 20) . "...\n\n";

// Test 1: Get user profile
echo "2. Testing user profile...\n";
$result = apiRequest('GET', '/user/profile', null, $token);
echo "HTTP Code: {$result['code']}\n";
$profile = json_decode($result['response'], true);
echo $profile['success'] ? "✅ Profile retrieved successfully\n" : "❌ Profile failed\n";
echo "User: {$profile['data']['name']} ({$profile['data']['role']})\n\n";

// Test 2: Update user profile
echo "3. Testing profile update...\n";
$result = apiRequest('PUT', '/user/profile', [
    'first_name' => 'Updated Admin',
    'last_name' => 'Test User',
    'phone' => '+998901111111'
], $token);
echo "HTTP Code: {$result['code']}\n";
$update = json_decode($result['response'], true);
echo $update['success'] ? "✅ Profile updated successfully\n" : "❌ Profile update failed\n\n";

// Test 3: Update location
echo "4. Testing location update...\n";
$result = apiRequest('PUT', '/user/location', [
    'latitude' => 41.2995,
    'longitude' => 69.2401,
    'address' => 'Tashkent, Uzbekistan'
], $token);
echo "HTTP Code: {$result['code']}\n";
$location = json_decode($result['response'], true);
echo $location['success'] ? "✅ Location updated successfully\n" : "❌ Location update failed\n\n";

// Test 4: Admin - Get all users
echo "5. Testing admin user list...\n";
$result = apiRequest('GET', '/admin/users', null, $token);
echo "HTTP Code: {$result['code']}\n";
$users = json_decode($result['response'], true);
if ($users['success']) {
    echo "✅ Users list retrieved successfully\n";
    echo "Total users: " . count($users['data']['data']) . "\n";
    echo "Current page: {$users['data']['current_page']}/{$users['data']['last_page']}\n\n";
} else {
    echo "❌ Users list failed\n\n";
}

// Test 5: Admin - Get user statistics
echo "6. Testing user statistics...\n";
$result = apiRequest('GET', '/admin/users/statistics', null, $token);
echo "HTTP Code: {$result['code']}\n";
$stats = json_decode($result['response'], true);
if ($stats['success']) {
    echo "✅ Statistics retrieved successfully\n";
    echo "Total users: {$stats['data']['total_users']}\n";
    echo "Active users: {$stats['data']['active_users']}\n";
    echo "Admin users: {$stats['data']['by_role']['admin']}\n\n";
} else {
    echo "❌ Statistics failed\n\n";
}

// Test 6: Admin - Search users
echo "7. Testing user search...\n";
$result = apiRequest('GET', '/admin/users?search=admin&role=admin', null, $token);
echo "HTTP Code: {$result['code']}\n";
$search = json_decode($result['response'], true);
if ($search['success']) {
    echo "✅ User search successful\n";
    echo "Found " . count($search['data']['data']) . " admin users\n\n";
} else {
    echo "❌ User search failed\n\n";
}

// Test 7: Admin - Get specific user
echo "8. Testing get specific user...\n";
$result = apiRequest('GET', '/admin/users/2', null, $token);
echo "HTTP Code: {$result['code']}\n";
$user = json_decode($result['response'], true);
if ($user['success']) {
    echo "✅ User details retrieved successfully\n";
    echo "User: {$user['data']['name']} ({$user['data']['email']})\n\n";
} else {
    echo "❌ User details failed\n\n";
}

echo "=== TEST COMPLETED ===\n";
