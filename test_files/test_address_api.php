<?php

// Address API ni test qilish uchun skript
require_once 'vendor/autoload.php';

// Base URL sozlash
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

echo "=== ADDRESS API TESTI ===\n\n";

// 1-qadam: Login qilish
echo "1. Tizimga kirish...\n";
$loginData = [
    'email' => 'testorder@test.com',
    'password' => 'password123'
];

$response = makeRequest("$baseUrl/auth/login", 'POST', $loginData);
echo "Login javobi: " . $response['code'] . "\n";

if ($response['data'] && isset($response['data']['data']['token'])) {
    $token = $response['data']['data']['token'];
    echo "✅ Tizimga kirish muvaffaqiyatli! Token: " . substr($token, 0, 20) . "...\n\n";

    $authHeaders = ['Authorization: Bearer ' . $token];

    // 2-qadam: Manzil yaratish
    echo "2. Yangi manzil yaratish...\n";
    $addressData = [
        'type' => 'both',
        'first_name' => 'Test',
        'last_name' => 'Foydalanuvchi',
        'phone' => '+998901234567',
        'country' => 'O\'zbekiston',
        'region' => 'Toshkent viloyati',
        'city' => 'Toshkent',
        'district' => 'Chilonzor tumani',
        'street_address' => 'Bunyodkor ko\'chasi 12-uy',
        'apartment' => '5-xonadon',
        'postal_code' => '100000',
        'delivery_instructions' => 'Lift bor, 5-qavat',
        'is_default' => true
    ];

    $response = makeRequest("$baseUrl/addresses", 'POST', $addressData, $authHeaders);
    echo "Manzil yaratish: " . $response['code'] . "\n";

    if ($response['data'] && $response['code'] == 201) {
        $addressId = $response['data']['data']['address']['id'];
        echo "✅ Manzil yaratildi! ID: $addressId\n";
        echo "To'liq manzil: " . $response['data']['data']['address']['formatted_address'] . "\n\n";

        // 3-qadam: Barcha manzillarni olish
        echo "3. Barcha manzillarni olish...\n";
        $response = makeRequest("$baseUrl/addresses", 'GET', null, $authHeaders);
        echo "Manzillar ro'yxati: " . $response['code'] . "\n";

        if ($response['data']) {
            $addresses = $response['data']['data']['addresses'];
            echo "Jami manzillar soni: " . count($addresses) . "\n";
            foreach ($addresses as $addr) {
                echo "- {$addr['full_name']}: {$addr['formatted_address']}" .
                     ($addr['is_default'] ? " (ASOSIY)" : "") . "\n";
            }
            echo "\n";
        }

        // 4-qadam: Manzil tafsilotlarini olish
        echo "4. Manzil tafsilotlarini olish...\n";
        $response = makeRequest("$baseUrl/addresses/$addressId", 'GET', null, $authHeaders);
        echo "Manzil tafsilotlari: " . $response['code'] . "\n";

        if ($response['data']) {
            $address = $response['data']['data']['address'];
            echo "Manzil: {$address['full_name']}\n";
            echo "Telefon: {$address['phone']}\n";
            echo "Manzil: {$address['formatted_address']}\n\n";
        }

        // 5-qadam: Yetkazib berish narxini hisoblash
        echo "5. Yetkazib berish narxini hisoblash...\n";
        $deliveryData = [
            'delivery_method' => 'standard',
            'total_amount' => 150000
        ];

        $response = makeRequest("$baseUrl/addresses/$addressId/delivery-fee", 'POST', $deliveryData, $authHeaders);
        echo "Yetkazib berish narxi: " . $response['code'] . "\n";

        if ($response['data']) {
            $fee = $response['data']['data']['delivery_fee'];
            echo "✅ Standard yetkazib berish: $fee UZS\n";
        }

        // Express yetkazib berish narxi
        $deliveryData['delivery_method'] = 'express';
        $response = makeRequest("$baseUrl/addresses/$addressId/delivery-fee", 'POST', $deliveryData, $authHeaders);
        if ($response['data']) {
            $fee = $response['data']['data']['delivery_fee'];
            echo "✅ Express yetkazib berish: $fee UZS\n\n";
        }

        // 6-qadam: Manzilni yangilash
        echo "6. Manzilni yangilash...\n";
        $updateData = [
            'delivery_instructions' => 'Yangilangan yo\'riqnoma: eshikni qo\'ng\'iroq qiling',
            'apartment' => '7-xonadon'
        ];

        $response = makeRequest("$baseUrl/addresses/$addressId", 'PUT', $updateData, $authHeaders);
        echo "Manzil yangilash: " . $response['code'] . "\n";

        if ($response['data']) {
            echo "✅ Manzil muvaffaqiyatli yangilandi\n\n";
        }

        // 7-qadam: Ikkinchi manzil yaratish
        echo "7. Ikkinchi manzil yaratish...\n";
        $secondAddressData = [
            'type' => 'shipping',
            'first_name' => 'Ikkinchi',
            'last_name' => 'Manzil',
            'phone' => '+998909876543',
            'country' => 'O\'zbekiston',
            'region' => 'Samarqand viloyati',
            'city' => 'Samarqand',
            'district' => 'Markaz',
            'street_address' => 'Registon ko\'chasi 1-uy',
            'delivery_instructions' => 'Samarqand shahar markazi',
            'is_default' => false
        ];

        $response = makeRequest("$baseUrl/addresses", 'POST', $secondAddressData, $authHeaders);
        if ($response['data'] && $response['code'] == 201) {
            $secondAddressId = $response['data']['data']['address']['id'];
            echo "✅ Ikkinchi manzil yaratildi! ID: $secondAddressId\n";

            // Samarqand uchun yetkazib berish narxi
            echo "   Samarqand uchun yetkazib berish narxi tekshirish...\n";
            $response = makeRequest("$baseUrl/addresses/$secondAddressId/delivery-fee", 'POST', $deliveryData, $authHeaders);
            if ($response['data']) {
                $fee = $response['data']['data']['delivery_fee'];
                echo "   Samarqand express yetkazib berish: $fee UZS\n\n";
            }
        }

        // 8-qadam: Barcha manzillarni qayta olish
        echo "8. Yangilangan manzillar ro'yxati...\n";
        $response = makeRequest("$baseUrl/addresses", 'GET', null, $authHeaders);
        if ($response['data']) {
            $addresses = $response['data']['data']['addresses'];
            echo "Jami manzillar soni: " . count($addresses) . "\n";
            foreach ($addresses as $addr) {
                echo "- {$addr['full_name']} ({$addr['type']}): {$addr['city']}" .
                     ($addr['is_default'] ? " (ASOSIY)" : "") . "\n";
            }
        }

    } else {
        echo "❌ Manzil yaratishda xatolik:\n";
        echo json_encode($response['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    }

} else {
    echo "❌ Tizimga kirishda xatolik!\n";
    echo "Javob: " . json_encode($response['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
}

echo "\n=== TEST TUGADI ===\n";
