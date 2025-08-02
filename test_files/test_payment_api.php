<?php

// Payment API ni test qilish uchun skript
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

echo "=== TO'LOV API TESTI ===\n\n";

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

    // 2-qadam: Savatga mahsulot qo'shish
    echo "2. Savatga mahsulot qo'shish...\n";
    $cartData = [
        'product_id' => 1,
        'quantity' => 2
    ];

    $response = makeRequest("$baseUrl/cart/add", 'POST', $cartData, $authHeaders);
    echo "Savatga qo'shish: " . $response['code'] . "\n";

    if ($response['data'] && $response['code'] == 201) {
        echo "✅ Savatga mahsulot qo'shildi\n";

        // Savatcha holati
        $response = makeRequest("$baseUrl/cart/summary", 'GET', null, $authHeaders);
        if ($response['data']) {
            echo "Cart summary response:\n";
            echo json_encode($response['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

            $summary = $response['data']['data'];
            $itemsCount = $summary['items_count'] ?? 0;
            $totalAmount = $summary['total_amount'] ?? 0;
            echo "Savatcha: {$itemsCount} ta mahsulot, {$totalAmount} UZS\n\n";
        }
    }    // 3-qadam: To'lov usullarini olish
    echo "3. To'lov usullarini olish...\n";
    $response = makeRequest("$baseUrl/payments/methods", 'GET', null, $authHeaders);
    echo "To'lov usullari: " . $response['code'] . "\n";

    if ($response['data'] && $response['code'] == 200) {
        $methods = $response['data']['data']['payment_methods'];
        echo "Mavjud to'lov usullari:\n";
        foreach ($methods as $method) {
            echo "- {$method['name']}: {$method['description']}\n";
            echo "  Min: " . number_format($method['min_amount']) . " UZS, Max: " . number_format($method['max_amount']) . " UZS\n";
        }
        echo "\n";
    }

    // 4-qadam: Buyurtma yaratish (to'lov qilish uchun)
    echo "4. Yangi buyurtma yaratish...\n";
    $orderData = [
        'delivery_method' => 'standard',
        'delivery_address_id' => 1,
        'payment_method' => 'cash',
        'notes' => 'Test buyurtma to\'lov uchun'
    ];

    $response = makeRequest("$baseUrl/orders", 'POST', $orderData, $authHeaders);
    echo "Buyurtma yaratish: " . $response['code'] . "\n";

    if ($response['data'] && $response['code'] == 201) {
        $orderId = $response['data']['data']['order']['id'];
        $totalAmount = $response['data']['data']['order']['total_amount'];
        echo "✅ Buyurtma yaratildi! ID: $orderId, Summa: " . number_format($totalAmount) . " UZS\n\n";

        // 5-qadam: Naqd to'lov
        echo "5. Naqd to'lov jarayoni...\n";
        $paymentData = [
            'order_id' => $orderId,
            'payment_method' => 'cash',
            'amount' => $totalAmount
        ];

        $response = makeRequest("$baseUrl/payments/process", 'POST', $paymentData, $authHeaders);
        echo "Naqd to'lov: " . $response['code'] . "\n";

        if ($response['data'] && $response['code'] == 201) {
            $paymentId = $response['data']['data']['payment']['id'];
            $transactionId = $response['data']['data']['payment']['transaction_id'];
            echo "✅ Naqd to'lov muvaffaqiyatli boshlandi!\n";
            echo "To'lov ID: $paymentId\n";
            echo "Tranzaksiya ID: $transactionId\n";
            echo "Status: " . $response['data']['data']['payment']['status'] . "\n";
            echo "Keyingi qadam: " . $response['data']['data']['next_steps']['message'] . "\n\n";

            // 6-qadam: To'lov holatini tekshirish
            echo "6. To'lov holatini tekshirish...\n";
            $response = makeRequest("$baseUrl/payments/$paymentId/status", 'GET', null, $authHeaders);
            echo "To'lov holati: " . $response['code'] . "\n";

            if ($response['data']) {
                $payment = $response['data']['data']['payment'];
                echo "To'lov holati: {$payment['status']}\n";
                echo "Summa: " . number_format($payment['amount']) . " {$payment['currency']}\n";
                echo "Usul: {$payment['method']}\n\n";
            }
        }

        // 7-qadam: Yangi buyurtma karta to'lovi uchun
        echo "7. Karta to'lovi uchun yangi buyurtma...\n";
        $response = makeRequest("$baseUrl/orders", 'POST', $orderData, $authHeaders);

        if ($response['data'] && $response['code'] == 201) {
            $orderId2 = $response['data']['data']['order']['id'];
            $totalAmount2 = $response['data']['data']['order']['total_amount'];
            echo "✅ Ikkinchi buyurtma yaratildi! ID: $orderId2\n";

            // Karta to'lovi
            echo "8. Karta to'lovi jarayoni...\n";
            $cardPaymentData = [
                'order_id' => $orderId2,
                'payment_method' => 'card',
                'amount' => $totalAmount2
            ];

            $response = makeRequest("$baseUrl/payments/process", 'POST', $cardPaymentData, $authHeaders);
            echo "Karta to'lovi: " . $response['code'] . "\n";

            if ($response['data'] && $response['code'] == 201) {
                $cardPaymentId = $response['data']['data']['payment']['id'];
                echo "✅ Karta to'lovi boshlandi!\n";
                echo "To'lov ID: $cardPaymentId\n";
                echo "Status: " . $response['data']['data']['payment']['status'] . "\n";
                echo "Keyingi qadam: " . $response['data']['data']['next_steps']['message'] . "\n\n";

                // To'lovni tasdiqlash (admin vazifasi)
                echo "9. To'lovni tasdiqlash (admin)...\n";
                $response = makeRequest("$baseUrl/payments/$cardPaymentId/confirm", 'POST', null, $authHeaders);
                echo "To'lov tasdiqlash: " . $response['code'] . "\n";

                if ($response['data']) {
                    echo "✅ To'lov tasdiqlandi!\n";
                    echo "Yangi status: " . $response['data']['data']['payment']['status'] . "\n\n";
                }
            }
        }

        // 10-qadam: To'lov tarixini olish
        echo "10. To'lov tarixini olish...\n";
        $response = makeRequest("$baseUrl/payments/history", 'GET', null, $authHeaders);
        echo "To'lov tarixi: " . $response['code'] . "\n";

        if ($response['data']) {
            $payments = $response['data']['data']['payments'];
            echo "Jami to'lovlar soni: " . count($payments) . "\n";

            foreach ($payments as $payment) {
                echo "- To'lov #{$payment['id']}: " . number_format($payment['amount']) . " {$payment['currency']}\n";
                echo "  Usul: {$payment['method']}, Status: {$payment['status']}\n";
                echo "  Buyurtma: {$payment['order']['order_number']} ({$payment['order']['status']})\n";
                echo "  Vaqt: {$payment['created_at']}\n\n";
            }
        }

    } else {
        echo "❌ Buyurtma yaratishda xatolik:\n";
        echo json_encode($response['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    }

} else {
    echo "❌ Tizimga kirishda xatolik!\n";
    echo "Javob: " . json_encode($response['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
}

echo "\n=== TO'LOV API TESTI TUGADI ===\n";
