<?php

// Simple API test script
$url = 'http://localhost:8080/auth/login';
$data = [
    'email' => 'admin@domproduct.uz',
    'password' => 'password',
    'device_name' => 'test_device'
];

$options = [
    'http' => [
        'header' => [
            'Content-Type: application/json',
            'Accept: application/json'
        ],
        'method' => 'POST',
        'content' => json_encode($data)
    ]
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    echo "Error: Cannot connect to API\n";
    var_dump($http_response_header);
} else {
    echo "Response:\n";
    echo $result . "\n";

    $response = json_decode($result, true);
    if (isset($response['token'])) {
        echo "\nLogin successful! Token: " . substr($response['token'], 0, 20) . "...\n";

        // Test user profile
        $token = $response['token'];
        $profile_url = 'http://localhost:8080/user/profile';

        $profile_options = [
            'http' => [
                'header' => [
                    'Authorization: Bearer ' . $token,
                    'Accept: application/json'
                ],
                'method' => 'GET'
            ]
        ];

        $profile_context = stream_context_create($profile_options);
        $profile_result = file_get_contents($profile_url, false, $profile_context);

        echo "\nProfile Response:\n";
        echo $profile_result . "\n";

    } else {
        echo "Login failed\n";
    }
}
