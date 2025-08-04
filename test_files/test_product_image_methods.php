<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Laravel bootstrap
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

echo "=== PRODUCT MODEL getImageUrl() METODI TESTI ===\n\n";

// Create test product without image
$testProduct = new Product([
    'sku' => 'TEST-001',
    'name' => 'Test Product',
    'price' => 99.99,
    'is_active' => true
]);

echo "1. METOD MAVJUDLIGI:\n";
$hasMethod = method_exists($testProduct, 'getImageUrl');
echo "   " . ($hasMethod ? "✅" : "❌") . " getImageUrl() metodi: " . ($hasMethod ? "MAVJUD" : "YO'Q") . "\n";

$hasGetNameMethod = method_exists($testProduct, 'getName');
echo "   " . ($hasGetNameMethod ? "✅" : "❌") . " getName() metodi: " . ($hasGetNameMethod ? "MAVJUD" : "YO'Q") . "\n";

if ($hasMethod) {
    echo "\n2. DEFAULT RASM TESTI:\n";

    try {
        $defaultImageUrl = $testProduct->getImageUrl();
        echo "   ✅ Default rasm URL: {$defaultImageUrl}\n";

        // Check if default image file exists
        $defaultImagePath = public_path('images/default-image.png');
        $defaultExists = file_exists($defaultImagePath);
        echo "   " . ($defaultExists ? "✅" : "❌") . " Default rasm fayl: " . ($defaultExists ? "MAVJUD" : "YO'Q") . "\n";

        // Test with different sizes
        $thumbnailUrl = $testProduct->getImageUrl('thumbnail');
        echo "   ✅ Thumbnail URL: {$thumbnailUrl}\n";

    } catch (Exception $e) {
        echo "   ❌ Xato: " . $e->getMessage() . "\n";
    }
}

if ($hasGetNameMethod) {
    echo "\n3. getName() METODI TESTI:\n";

    try {
        // Set a name for testing
        $testProduct->name = "Test Mahsulot";
        $productName = $testProduct->getName();
        echo "   ✅ Mahsulot nomi: {$productName}\n";

    } catch (Exception $e) {
        echo "   ❌ Xato: " . $e->getMessage() . "\n";
    }
}

echo "\n4. REAL MA'LUMOTLAR BAZASI TESTI:\n";

try {
    // Get first product from database
    $firstProduct = Product::first();

    if ($firstProduct) {
        echo "   ✅ Ma'lumotlar bazasida mahsulot topildi: ID {$firstProduct->id}\n";

        $imageUrl = $firstProduct->getImageUrl();
        echo "   ✅ Rasm URL: {$imageUrl}\n";

        $productName = $firstProduct->getName();
        echo "   ✅ Mahsulot nomi: {$productName}\n";

        // Check if product has actual image
        if ($firstProduct->image) {
            $imagePath = storage_path('app/public/products/' . $firstProduct->image);
            $imageExists = file_exists($imagePath);
            echo "   " . ($imageExists ? "✅" : "⚠️") . " Mahsulot rasmi: " . ($imageExists ? "MAVJUD" : "FAYL YO'Q") . "\n";
        } else {
            echo "   ⚠️  Mahsulotda rasm yo'q (default ishlatiladi)\n";
        }

    } else {
        echo "   ⚠️  Ma'lumotlar bazasida mahsulot topilmadi\n";
    }

} catch (Exception $e) {
    echo "   ❌ Ma'lumotlar bazasi xatosi: " . $e->getMessage() . "\n";
}

echo "\n5. YAKUNIY BAHOLASH:\n";

$score = 0;
$maxScore = 4;

if ($hasMethod) $score++;
if ($hasGetNameMethod) $score++;
if (file_exists(public_path('images/default-image.png'))) $score++;
if (Product::count() > 0) $score++;

$percentage = round(($score / $maxScore) * 100, 1);

echo "   📊 Jami: {$score}/{$maxScore} ({$percentage}%)\n";

if ($percentage >= 100) {
    echo "   🎉 MUKAMMAL! Product model to'liq ishlayapti!\n";
} elseif ($percentage >= 75) {
    echo "   👍 YAXSHI! Asosiy funksiyalar ishlayapti!\n";
} else {
    echo "   ⚠️  YAXSHILASH KERAK! Ba'zi muammolar mavjud!\n";
}

echo "\n=== TEST YAKUNLANDI ===\n";
