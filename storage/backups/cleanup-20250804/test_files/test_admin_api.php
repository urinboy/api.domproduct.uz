<?php

// Admin Dashboard API ni test qilish uchun skript
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

echo "=== ADMIN DASHBOARD API TESTI ===\n\n";

// 1-qadam: Admin sifatida login qilish
echo "1. Admin sifatida tizimga kirish...\n";
$loginData = [
    'email' => 'admin@test.com',
    'password' => 'admin123'
];

$response = makeRequest("$baseUrl/auth/login", 'POST', $loginData);
echo "Admin login: " . $response['code'] . "\n";

if ($response['data'] && isset($response['data']['data']['token'])) {
    $adminToken = $response['data']['data']['token'];
    $user = $response['data']['data']['user'];
    echo "✅ Admin tizimga kirdi! Rol: {$user['role']}\n\n";

    $adminHeaders = ['Authorization: Bearer ' . $adminToken];

    // 2-qadam: Buyurtmalar statistikasini olish
    echo "2. Buyurtmalar statistikasini olish...\n";
    $response = makeRequest("$baseUrl/admin/orders/statistics", 'GET', null, $adminHeaders);
    echo "Buyurtma statistikasi: " . $response['code'] . "\n";

    if ($response['data'] && $response['code'] == 200) {
        $stats = $response['data']['data'];
        echo "📊 Statistika:\n";
        echo "- Jami buyurtmalar: {$stats['summary']['total_orders']}\n";
        echo "- Jami daromad: " . number_format($stats['summary']['total_revenue']) . " UZS\n";
        echo "- O'rtacha buyurtma: " . number_format($stats['summary']['average_order_value']) . " UZS\n";

        if (isset($stats['status_breakdown']) && count($stats['status_breakdown']) > 0) {
            echo "Status bo'yicha:\n";
            foreach ($stats['status_breakdown'] as $status => $data) {
                echo "  - {$data['name']}: {$data['count']} ta\n";
            }
        }
        echo "\n";
    }

    // 3-qadam: Barcha buyurtmalarni olish
    echo "3. Barcha buyurtmalarni olish...\n";
    $response = makeRequest("$baseUrl/admin/orders", 'GET', null, $adminHeaders);
    echo "Buyurtmalar ro'yxati: " . $response['code'] . "\n";

    if ($response['data'] && $response['code'] == 200) {
        $orders = $response['data']['data']['orders'];
        $pagination = $response['data']['data']['pagination'];

        echo "📋 Buyurtmalar ro'yxati:\n";
        echo "Jami: {$pagination['total_items']} ta buyurtma\n";

        foreach (array_slice($orders, 0, 5) as $order) {
            $customer = $order['user'] ? $order['user']['name'] : $order['guest_info']['name'];
            echo "- #{$order['order_number']}: {$customer} - " .
                 number_format($order['total_amount']) . " UZS ({$order['status_name']})\n";
        }
        echo "\n";

        // Birinchi buyurtma tafsilotlarini olish
        if (count($orders) > 0) {
            $firstOrderId = $orders[0]['id'];

            echo "4. Buyurtma tafsilotlarini olish (ID: $firstOrderId)...\n";
            $response = makeRequest("$baseUrl/admin/orders/$firstOrderId", 'GET', null, $adminHeaders);
            echo "Buyurtma tafsilotlari: " . $response['code'] . "\n";

            if ($response['data'] && $response['code'] == 200) {
                $order = $response['data']['data']['order'];
                echo "📄 Buyurtma tafsilotlari:\n";
                echo "- Buyurtma raqami: {$order['order_number']}\n";
                echo "- Mijoz: {$order['customer']['name']} ({$order['customer']['type']})\n";
                echo "- Status: {$order['status_name']}\n";
                echo "- Jami summa: " . number_format($order['total_amount']) . " UZS\n";
                echo "- Yetkazib berish: {$order['delivery']['method']}\n";
                echo "- Mahsulotlar soni: " . count($order['items']) . " ta\n";

                if (count($order['payments']) > 0) {
                    $payment = $order['payments'][0];
                    echo "- To'lov: {$payment['method']} ({$payment['status_name']})\n";
                }
                echo "\n";

                // 5-qadam: Buyurtma statusini yangilash
                echo "5. Buyurtma statusini yangilash...\n";
                $statusData = [
                    'status' => 'confirmed',
                    'comment' => 'Admin tomonidan tasdiqlandi'
                ];

                $response = makeRequest("$baseUrl/admin/orders/$firstOrderId/status", 'PUT', $statusData, $adminHeaders);
                echo "Status yangilash: " . $response['code'] . "\n";

                if ($response['data'] && $response['code'] == 200) {
                    $updatedOrder = $response['data']['data']['order'];
                    echo "✅ Status yangilandi:\n";
                    echo "- Eski status: {$updatedOrder['old_status_name']}\n";
                    echo "- Yangi status: {$updatedOrder['new_status_name']}\n\n";
                }
            }
        }
    }

    // 6-qadam: Foydalanuvchi statistikasini olish
    echo "6. Foydalanuvchi statistikasini olish...\n";
    $response = makeRequest("$baseUrl/admin/users/statistics", 'GET', null, $adminHeaders);
    echo "Foydalanuvchi statistikasi: " . $response['code'] . "\n";

    if ($response['data'] && $response['code'] == 200) {
        $userStats = $response['data']['data'];
        echo "👥 Foydalanuvchi statistikasi:\n";
        echo "- Jami foydalanuvchilar: {$userStats['summary']['total_users']}\n";
        echo "- Faol foydalanuvchilar: {$userStats['summary']['active_users']}\n";
        echo "- Tasdiqlangan: {$userStats['summary']['verified_users']}\n";
        echo "- Tasdiqlash darajasi: {$userStats['summary']['verification_rate']}%\n";

        if (isset($userStats['role_breakdown'])) {
            echo "Rol bo'yicha:\n";
            foreach ($userStats['role_breakdown'] as $role => $data) {
                echo "  - {$data['name']}: {$data['count']} ta\n";
            }
        }
        echo "\n";
    }

    // 7-qadam: Foydalanuvchilar ro'yxatini olish
    echo "7. Foydalanuvchilar ro'yxatini olish...\n";
    $response = makeRequest("$baseUrl/admin/users", 'GET', null, $adminHeaders);
    echo "Foydalanuvchilar ro'yxati: " . $response['code'] . "\n";

    if ($response['data'] && $response['code'] == 200) {
        $users = $response['data']['data']['users'];
        $pagination = $response['data']['data']['pagination'];

        echo "👤 Foydalanuvchilar ro'yxati:\n";
        echo "Jami: {$pagination['total']} ta foydalanuvchi\n";

        foreach (array_slice($users, 0, 5) as $user) {
            $status = $user['is_active'] ? '✅ Faol' : '❌ Nofaol';
            echo "- {$user['name']} ({$user['email']}) - {$user['role']} $status\n";
        }
        echo "\n";
    }

    // 8-qadam: Buyurtmalarni filtrlash
    echo "8. Buyurtmalarni status bo'yicha filtrlash...\n";
    $response = makeRequest("$baseUrl/admin/orders?status=pending", 'GET', null, $adminHeaders);
    echo "Kutilayotgan buyurtmalar: " . $response['code'] . "\n";

    if ($response['data'] && $response['code'] == 200) {
        $pendingOrders = $response['data']['data']['orders'];
        echo "⏳ Kutilayotgan buyurtmalar: " . count($pendingOrders) . " ta\n\n";
    }

} else {
    // Agar admin mavjud bo'lmasa, oddiy user bilan test qilamiz
    echo "❌ Admin login muvaffaqiyatsiz, oddiy foydalanuvchi bilan test qilamiz\n\n";

    $loginData = [
        'email' => 'testorder@test.com',
        'password' => 'password123'
    ];

    $response = makeRequest("$baseUrl/auth/login", 'POST', $loginData);
    if ($response['data'] && isset($response['data']['data']['token'])) {
        $userToken = $response['data']['data']['token'];
        $userHeaders = ['Authorization: Bearer ' . $userToken];

        echo "Admin API uchun foydalanuvchi huquqlari tekshirilmoqda...\n";
        $response = makeRequest("$baseUrl/admin/orders", 'GET', null, $userHeaders);
        echo "Admin orders access: " . $response['code'] . "\n";

        if ($response['code'] == 403) {
            echo "✅ Xavfsizlik ishlayapti - oddiy foydalanuvchi admin API ga kira olmaydi\n";
        }
    }
}

echo "\n=== ADMIN DASHBOARD API TESTI TUGADI ===\n";
