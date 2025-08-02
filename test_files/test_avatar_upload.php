<?php

// Avatar Upload Test
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
        'device_name' => 'avatar_test'
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

function apiRequest($method, $endpoint, $data = null, $token = null, $isFile = false) {
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
        if ($isFile) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } else {
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['code' => $http_code, 'response' => $response];
}

// Create sample avatar image
function createTestImage($filename = 'test_avatar.jpg') {
    $width = 200;
    $height = 200;

    $image = imagecreatetruecolor($width, $height);
    $bg_color = imagecolorallocate($image, 100, 150, 200);
    $text_color = imagecolorallocate($image, 255, 255, 255);

    imagefill($image, 0, 0, $bg_color);
    imagestring($image, 5, 50, 90, 'AVATAR', $text_color);

    imagejpeg($image, $filename, 90);
    imagedestroy($image);

    return $filename;
}

echo "=== AVATAR UPLOAD TEST ===\n\n";

// Get token
echo "1. Getting admin token...\n";
$token = getToken();
if (!$token) {
    echo "❌ Failed to get token\n";
    exit;
}
echo "✅ Token received\n\n";

// Test 1: Get current profile
echo "2. Getting current profile...\n";
$result = apiRequest('GET', '/user/profile', null, $token);
echo "HTTP Code: {$result['code']}\n";
$profile = json_decode($result['response'], true);
if ($profile['success']) {
    echo "✅ Current avatar: " . ($profile['data']['avatar'] ?? 'none') . "\n\n";
} else {
    echo "❌ Failed to get profile\n\n";
}

// Test 2: Create and upload avatar
echo "3. Creating test avatar image...\n";
$testImage = createTestImage();
echo "✅ Test image created: $testImage\n\n";

echo "4. Uploading avatar...\n";
$postData = [
    'avatar' => new CURLFile($testImage, 'image/jpeg', 'avatar.jpg')
];

$result = apiRequest('POST', '/user/avatar', $postData, $token, true);
echo "HTTP Code: {$result['code']}\n";
$upload = json_decode($result['response'], true);

if ($upload['success']) {
    echo "✅ Avatar uploaded successfully!\n";
    echo "Avatar URL: " . $upload['data']['avatar'] . "\n";
    echo "Available sizes:\n";
    foreach ($upload['data']['avatar_sizes'] as $size => $url) {
        echo "  - $size: $url\n";
    }
    echo "\n";
} else {
    echo "❌ Avatar upload failed\n";
    echo "Error: " . ($upload['message'] ?? 'Unknown error') . "\n\n";
}

// Test 3: Get updated profile
echo "5. Getting updated profile...\n";
$result = apiRequest('GET', '/user/profile', null, $token);
$profile = json_decode($result['response'], true);
if ($profile['success']) {
    echo "✅ Updated avatar: " . $profile['data']['avatar'] . "\n";
    if (isset($profile['data']['avatar_sizes'])) {
        echo "Avatar sizes available: " . count($profile['data']['avatar_sizes']) . "\n\n";
    }
} else {
    echo "❌ Failed to get updated profile\n\n";
}

// Test 4: Delete avatar
echo "6. Deleting avatar...\n";
$result = apiRequest('DELETE', '/user/avatar', null, $token);
echo "HTTP Code: {$result['code']}\n";
$delete = json_decode($result['response'], true);

if ($delete['success']) {
    echo "✅ Avatar deleted successfully!\n";
    echo "Default avatar: " . $delete['data']['avatar'] . "\n\n";
} else {
    echo "❌ Avatar deletion failed\n\n";
}

// Cleanup
unlink($testImage);
echo "✅ Test image cleaned up\n";
echo "=== TEST COMPLETED ===\n";
