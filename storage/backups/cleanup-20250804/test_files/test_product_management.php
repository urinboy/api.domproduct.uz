<?php

// Product Management API ni test qilish uchun skript
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

echo "=== PRODUCT MANAGEMENT API TESTI ===\n\n";

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
    echo "✅ Admin tizimga kirdi!\n\n";

    $adminHeaders = ['Authorization: Bearer ' . $adminToken];

    // 2-qadam: Mahsulot analitikasini olish
    echo "2. Mahsulot analitikasini olish...\n";
    $response = makeRequest("$baseUrl/admin/products/analytics", 'GET', null, $adminHeaders);
    echo "Mahsulot analitikasi: " . $response['code'] . "\n";

    if ($response['data'] && $response['code'] == 200) {
        $analytics = $response['data']['data'];
        echo "📊 Mahsulot analitikasi:\n";
        echo "- Davr: {$analytics['period']['date_from']} - {$analytics['period']['date_to']}\n";

        if (isset($analytics['top_selling_products']) && count($analytics['top_selling_products']) > 0) {
            echo "Eng ko'p sotiladigan mahsulotlar:\n";
            foreach (array_slice($analytics['top_selling_products'], 0, 3) as $product) {
                echo "  - {$product->name}: {$product->total_sold} ta sotildi\n";
            }
        }

        if (isset($analytics['stock_overview'])) {
            $stock = $analytics['stock_overview'];
            echo "Zaxira holati:\n";
            echo "  - Mavjud: {$stock['in_stock']} ta\n";
            echo "  - Tugagan: {$stock['out_of_stock']} ta\n";
            echo "  - Kam qolgan: {$stock['low_stock']} ta\n";
            echo "  - Jami qiymat: " . number_format($stock['total_value']) . " UZS\n";
        }
        echo "\n";
    }

    // 3-qadam: Kam zaxiradagi mahsulotlar
    echo "3. Kam zaxiradagi mahsulotlarni olish...\n";
    $response = makeRequest("$baseUrl/admin/products/low-stock?threshold=20", 'GET', null, $adminHeaders);
    echo "Kam zaxiradagi mahsulotlar: " . $response['code'] . "\n";

    if ($response['data'] && $response['code'] == 200) {
        $lowStockData = $response['data']['data'];
        $products = $lowStockData['products'];

        echo "📦 Kam zaxiradagi mahsulotlar (20 tadan kam):\n";
        echo "Jami: {$lowStockData['pagination']['total_items']} ta mahsulot\n";

        foreach (array_slice($products, 0, 5) as $product) {
            echo "- {$product['name']} (SKU: {$product['sku']})\n";
            echo "  Zaxira: {$product['stock_quantity']} ta, Min: {$product['min_stock_level']} ta\n";
            echo "  Holat: {$product['stock_level']}\n";
            if ($product['needs_restock']) {
                echo "  ⚠️ Zaxira to'ldirish kerak!\n";
            }
        }
        echo "\n";

        // 4-qadam: Mahsulot zaxirasini yangilash
        if (count($products) > 0) {
            $productToUpdate = $products[0];
            echo "4. Mahsulot zaxirasini yangilash (ID: {$productToUpdate['id']})...\n";

            $stockUpdateData = [
                'stock_quantity' => $productToUpdate['stock_quantity'] + 50,
                'min_stock_level' => 10,
                'note' => 'Test orqali zaxira to\'ldirildi'
            ];

            $response = makeRequest("$baseUrl/admin/products/{$productToUpdate['id']}/stock", 'PUT', $stockUpdateData, $adminHeaders);
            echo "Zaxira yangilash: " . $response['code'] . "\n";

            if ($response['data'] && $response['code'] == 200) {
                $updated = $response['data']['data']['product'];
                echo "✅ Zaxira yangilandi:\n";
                echo "- Mahsulot: {$updated['name']}\n";
                echo "- Eski zaxira: {$updated['old_stock']} ta\n";
                echo "- Yangi zaxira: {$updated['new_stock']} ta\n";
                echo "- O'zgarish: +{$updated['stock_change']} ta\n";
                echo "- Yangi holat: {$updated['stock_status']}\n\n";
            }
        }
    }

    // 5-qadam: Ommaviy zaxira yangilash
    echo "5. Ommaviy zaxira yangilash...\n";
    $bulkUpdateData = [
        'updates' => [
            [
                'product_id' => 1,
                'stock_quantity' => 100,
                'min_stock_level' => 15
            ],
            [
                'product_id' => 2,
                'stock_quantity' => 75,
                'min_stock_level' => 10
            ]
        ]
    ];

    $response = makeRequest("$baseUrl/admin/products/bulk-stock", 'POST', $bulkUpdateData, $adminHeaders);
    echo "Ommaviy yangilash: " . $response['code'] . "\n";

    if ($response['data'] && $response['code'] == 200) {
        $bulkResult = $response['data']['data'];
        echo "✅ Ommaviy yangilash yakunlandi:\n";
        echo "- Yangilangan: {$bulkResult['updated_count']} ta\n";
        echo "- Xatoliklar: {$bulkResult['error_count']} ta\n";

        if (count($bulkResult['updated_products']) > 0) {
            echo "Yangilangan mahsulotlar:\n";
            foreach ($bulkResult['updated_products'] as $product) {
                echo "  - {$product['name']}: {$product['old_stock']} → {$product['new_stock']} ta\n";
            }
        }

        if (count($bulkResult['errors']) > 0) {
            echo "Xatoliklar:\n";
            foreach ($bulkResult['errors'] as $error) {
                echo "  - $error\n";
            }
        }
        echo "\n";
    }

    // 6-qadam: Mahsulot statistikasi
    echo "6. Mahsulot statistikasini olish...\n";
    $response = makeRequest("$baseUrl/admin/products/statistics", 'GET', null, $adminHeaders);
    echo "Mahsulot statistikasi: " . $response['code'] . "\n";

    if ($response['data'] && $response['code'] == 200) {
        $stats = $response['data']['data'];
        echo "📈 Mahsulot statistikasi:\n";
        echo "- Jami mahsulotlar: {$stats['total_products']}\n";
        echo "- Faol mahsulotlar: {$stats['active_products']}\n";
        echo "- Tanlovli mahsulotlar: {$stats['featured_products']}\n";
        echo "- Tugagan mahsulotlar: {$stats['out_of_stock']}\n";
        echo "- Kam qolgan mahsulotlar: {$stats['low_stock']}\n";
        echo "- Nashr etilgan: {$stats['published_products']}\n";
        echo "- Jami qiymat: " . number_format($stats['total_value']) . " UZS\n\n";
    }

    // 7-qadam: Mahsulotlarni filtrlash
    echo "7. Mahsulotlarni filtrlash va qidirish...\n";
    $response = makeRequest("$baseUrl/admin/products?status=active&sort_by=stock_quantity&sort_order=asc", 'GET', null, $adminHeaders);
    echo "Filtrlangan mahsulotlar: " . $response['code'] . "\n";

    if ($response['data'] && $response['code'] == 200) {
        $products = $response['data']['data']['products'];
        $pagination = $response['data']['data']['pagination'];

        echo "🔍 Faol mahsulotlar (zaxira bo'yicha o'sish):\n";
        echo "Jami: {$pagination['total']} ta mahsulot\n";

        foreach (array_slice($products, 0, 5) as $product) {
            echo "- {$product['name']}: {$product['stock_quantity']} ta zaxira\n";
        }
        echo "\n";
    }

} else {
    echo "❌ Admin login muvaffaqiyatsiz!\n";
    echo "Javob: " . json_encode($response['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
}

echo "\n=== PRODUCT MANAGEMENT API TESTI TUGADI ===\n";
