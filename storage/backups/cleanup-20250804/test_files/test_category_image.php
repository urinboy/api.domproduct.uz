<?php

// Category Image Upload Test
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
        'device_name' => 'category_image_test'
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

function createTestImage($filename) {
    // Create a simple test image
    $image = imagecreate(300, 200);
    $bg = imagecolorallocate($image, 200, 200, 200);
    $text_color = imagecolorallocate($image, 50, 50, 50);

    imagestring($image, 5, 50, 90, 'Test Category Image', $text_color);

    imagejpeg($image, $filename, 90);
    imagedestroy($image);

    return file_exists($filename);
}

function uploadFile($url, $filePath, $token) {
    $ch = curl_init();

    $file = new CURLFile($filePath, 'image/jpeg', 'test_category.jpg');

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ['image' => $file]);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['code' => $http_code, 'response' => $response];
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

echo "=== CATEGORY IMAGE UPLOAD TEST ===\n\n";

// Get token
echo "1. Getting admin token...\n";
$token = getToken();
if (!$token) {
    echo "❌ Failed to get token\n";
    exit;
}
echo "✅ Token received\n\n";

// Create test category first
echo "2. Creating test category...\n";
$newCategory = [
    'translations' => [
        [
            'language_code' => 'uz',
            'name' => 'Image Test Category',
            'description' => 'Category for image testing'
        ]
    ],
    'is_active' => true
];

$result = apiRequest('POST', '/admin/categories', $newCategory, $token);
echo "HTTP Code: {$result['code']}\n";
$created = json_decode($result['response'], true);
if ($created['success']) {
    echo "✅ Test category created\n";
    $categoryId = $created['data']['id'];
    echo "Category ID: $categoryId\n\n";
} else {
    echo "❌ Category creation failed\n";
    exit;
}

// Create test image
echo "3. Creating test image...\n";
$imagePath = 'test_category_image.jpg';
if (createTestImage($imagePath)) {
    echo "✅ Test image created: $imagePath\n\n";
} else {
    echo "❌ Failed to create test image\n";
    exit;
}

// Upload category image
echo "4. Uploading category image...\n";
$result = uploadFile("$base_url/admin/categories/$categoryId/image", $imagePath, $token);
echo "HTTP Code: {$result['code']}\n";
$upload = json_decode($result['response'], true);
if ($upload['success']) {
    echo "✅ Category image uploaded successfully!\n";
    echo "Image URL: " . $upload['data']['image'] . "\n";
    echo "Available sizes:\n";
    foreach ($upload['data']['image_sizes'] as $size => $url) {
        echo "  - $size: $url\n";
    }
    echo "\n";
} else {
    echo "❌ Category image upload failed\n";
    echo "Error: " . ($upload['message'] ?? 'Unknown error') . "\n\n";
}

// Get updated category
echo "5. Getting updated category...\n";
$result = apiRequest('GET', "/admin/categories/$categoryId", null, $token);
$category = json_decode($result['response'], true);
if ($category['success']) {
    echo "✅ Updated category image: " . $category['data']['image'] . "\n";
    if (isset($category['data']['image_sizes'])) {
        echo "Image sizes available: " . count($category['data']['image_sizes']) . "\n\n";
    }
} else {
    echo "❌ Failed to get updated category\n\n";
}

// Delete category image
echo "6. Deleting category image...\n";
$result = apiRequest('DELETE', "/admin/categories/$categoryId/image", null, $token);
echo "HTTP Code: {$result['code']}\n";
$delete = json_decode($result['response'], true);
if ($delete['success']) {
    echo "✅ Category image deleted successfully!\n";
    echo "Default image: " . $delete['data']['image'] . "\n\n";
} else {
    echo "❌ Category image deletion failed\n\n";
}

// Cleanup: Delete test category
echo "7. Cleaning up test category...\n";
$result = apiRequest('DELETE', "/admin/categories/$categoryId", null, $token);
if (json_decode($result['response'], true)['success']) {
    echo "✅ Test category deleted\n";
} else {
    echo "❌ Failed to delete test category\n";
}

// Cleanup: Delete test image
if (file_exists($imagePath)) {
    unlink($imagePath);
    echo "✅ Test image cleaned up\n";
}

echo "=== TEST COMPLETED ===\n";
