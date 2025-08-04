<?php

// Debug login test
$url = 'http://localhost:8080/auth/login';
$data = [
    'email' => 'admin@domproduct.uz',
    'password' => 'admin123',
    'device_name' => 'test_device'
];

echo "Testing URL: $url\n";
echo "Data: " . json_encode($data) . "\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "HTTP Code: $http_code\n";
echo "Response: $response\n";

if ($http_code == 200) {
    $data = json_decode($response, true);
    if (isset($data['data']['token'])) {
        $token = $data['data']['token'];
        echo "\nLogin successful! Testing user profile...\n";

        // Test user profile
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, 'http://localhost:8080/user/profile');
        curl_setopt($ch2, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

        $profile_response = curl_exec($ch2);
        $profile_http_code = curl_getinfo($ch2, CURLINFO_HTTP_CODE);

        echo "Profile HTTP Code: $profile_http_code\n";
        echo "Profile Response: $profile_response\n";

        curl_close($ch2);
    }
}

curl_close($ch);
