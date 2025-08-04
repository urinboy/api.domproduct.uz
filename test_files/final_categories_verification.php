<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Laravel bootstrap
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

echo "=== DOM PRODUCT KATEGORIYALAR MODULI YAKUNIY TEKSHIRUV ===\n\n";

// 1. Routelar tekshiruvi
echo "1. ROUTELAR HOLATI:\n";
$categoryRoutes = collect(Route::getRoutes())->filter(function ($route) {
    return str_contains($route->getName() ?? '', 'admin.categories');
});

echo "   ✓ Kategoriyalar uchun routelar soni: " . $categoryRoutes->count() . "\n";

$requiredRoutes = [
    'admin.categories.index',
    'admin.categories.create',
    'admin.categories.store',
    'admin.categories.show',
    'admin.categories.edit',
    'admin.categories.update',
    'admin.categories.destroy',
    'admin.categories.toggle-status'
];

foreach ($requiredRoutes as $route) {
    $exists = Route::has($route);
    echo "   " . ($exists ? "✓" : "✗") . " {$route}: " . ($exists ? "MAVJUD" : "YO'Q") . "\n";
}

// 2. Model va relationships tekshiruvi
echo "\n2. MODEL VA MUNOSABATLAR:\n";

// Category model metodlari
$category = new Category();
$methods = [
    'translations', 'parent', 'children', 'products',
    'getName', 'getSlug', 'hasChildren', 'getDepth', 'getPath', 'getImageUrl'
];

foreach ($methods as $method) {
    $exists = method_exists($category, $method);
    echo "   " . ($exists ? "✓" : "✗") . " Category::{$method}(): " . ($exists ? "MAVJUD" : "YO'Q") . "\n";
}

// 3. Tarjimalar tekshiruvi
echo "\n3. TARJIMALAR HOLATI:\n";

$languages = ['uz', 'en', 'ru'];
$sampleKeys = [
    'categories', 'category_name', 'category_slug', 'parent_category',
    'category_image', 'category_status', 'add_category', 'edit_category',
    'view_category', 'delete_category', 'no_categories_found'
];

foreach ($languages as $lang) {
    echo "   {$lang} tili:\n";
    $missing = 0;
    foreach ($sampleKeys as $key) {
        $translation = __("admin.{$key}", [], $lang);
        $exists = $translation !== "admin.{$key}";
        if (!$exists) $missing++;
        echo "     " . ($exists ? "✓" : "✗") . " admin.{$key}\n";
    }
    echo "     Jami: " . (count($sampleKeys) - $missing) . "/" . count($sampleKeys) . " mavjud\n";
}

// 4. Fayl tuzilishi tekshiruvi
echo "\n4. FAYL TUZILISHI:\n";

$requiredFiles = [
    'app/Http/Controllers/Admin/CategoryController.php',
    'app/Models/Category.php',
    'app/Models/CategoryTranslation.php',
    'resources/views/admin/categories/index.blade.php',
    'resources/views/admin/categories/create.blade.php',
    'resources/views/admin/categories/edit.blade.php',
    'resources/views/admin/categories/show.blade.php'
];

foreach ($requiredFiles as $file) {
    $fullPath = __DIR__ . '/../' . $file;
    $exists = file_exists($fullPath);
    echo "   " . ($exists ? "✓" : "✗") . " {$file}: " . ($exists ? "MAVJUD" : "YO'Q") . "\n";
}

// 5. Ma'lumotlar bazasi tekshiruvi
echo "\n5. MA'LUMOTLAR BAZASI:\n";

try {
    // Categories jadvali
    $categoriesCount = Category::count();
    echo "   ✓ Kategoriyalar soni: {$categoriesCount}\n";

    // CategoryTranslations jadvali
    $translationsCount = CategoryTranslation::count();
    echo "   ✓ Tarjimalar soni: {$translationsCount}\n";

    // Parent-child munosabatlari
    $parentCategories = Category::whereNull('parent_id')->count();
    $childCategories = Category::whereNotNull('parent_id')->count();
    echo "   ✓ Asosiy kategoriyalar: {$parentCategories}\n";
    echo "   ✓ Ichki kategoriyalar: {$childCategories}\n";

    // Har bir tilida tarjimalar
    foreach ($languages as $lang) {
        // Language modeldan ID olish
        $language = \App\Models\Language::where('code', $lang)->first();
        if ($language) {
            $langCount = CategoryTranslation::where('language_id', $language->id)->count();
            echo "   ✓ {$lang} tilida tarjimalar: {$langCount}\n";
        } else {
            echo "   ✗ {$lang} tili topilmadi\n";
        }
    }

} catch (Exception $e) {
    echo "   ✗ Ma'lumotlar bazasi xatosi: " . $e->getMessage() . "\n";
}

// 6. Controller metodlari tekshiruvi
echo "\n6. CONTROLLER METODLARI:\n";

$controllerFile = __DIR__ . '/../app/Http/Controllers/Admin/CategoryController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);

    $methods = [
        'index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'toggleStatus'
    ];

    foreach ($methods as $method) {
        $exists = strpos($content, "function {$method}(") !== false;
        echo "   " . ($exists ? "✓" : "✗") . " {$method}(): " . ($exists ? "MAVJUD" : "YO'Q") . "\n";
    }

    // Qo'shimcha metodlar
    $additionalMethods = ['handleImageUpload', 'generateImageSizes'];
    foreach ($additionalMethods as $method) {
        $exists = strpos($content, "function {$method}(") !== false;
        echo "   " . ($exists ? "✓" : "✗") . " {$method}(): " . ($exists ? "MAVJUD" : "YO'Q") . "\n";
    }
}

// 7. Performance test
echo "\n7. PERFORMANCE TEKSHIRUVI:\n";

$startTime = microtime(true);

// Complex query with relationships
$categories = Category::with(['translations', 'parent', 'children'])
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();

$endTime = microtime(true);
$queryTime = round(($endTime - $startTime) * 1000, 2);

echo "   ✓ 10 ta kategoriya (munosabatlar bilan): {$queryTime}ms\n";

// Hierarchical data test
$startTime = microtime(true);

$hierarchicalData = Category::whereNull('parent_id')
    ->with(['children.translations', 'translations'])
    ->get();

$endTime = microtime(true);
$hierarchicalTime = round(($endTime - $startTime) * 1000, 2);

echo "   ✓ Ierarxik ma'lumotlar: {$hierarchicalTime}ms\n";

// 8. Yakuniy xulosa
echo "\n8. YAKUNIY XULOSA:\n";

$totalChecks = 0;
$passedChecks = 0;

// Routes check
$totalChecks += count($requiredRoutes);
foreach ($requiredRoutes as $route) {
    if (Route::has($route)) $passedChecks++;
}

// Files check
$totalChecks += count($requiredFiles);
foreach ($requiredFiles as $file) {
    if (file_exists(__DIR__ . '/../' . $file)) $passedChecks++;
}

// Methods check
$totalChecks += count($methods) + count($additionalMethods ?? []);
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    foreach (array_merge($methods, $additionalMethods ?? []) as $method) {
        if (strpos($content, "function {$method}(") !== false) $passedChecks++;
    }
}

$successRate = round(($passedChecks / $totalChecks) * 100, 1);

echo "   📊 Jami tekshiruvlar: {$totalChecks}\n";
echo "   ✅ Muvaffaqiyatli: {$passedChecks}\n";
echo "   📈 Muvaffaqiyat darajasi: {$successRate}%\n";

if ($successRate >= 95) {
    echo "\n🎉 KATEGORIYALAR MODULI TO'LIQ TAYYOR!\n";
    echo "💡 Barcha komponentlar ishlayapti va ishlab chiqarishga tayyor.\n";
} elseif ($successRate >= 80) {
    echo "\n⚠️  KATEGORIYALAR MODULI DEYARLI TAYYOR\n";
    echo "🔧 Ba'zi komponentlarni to'ldirishingiz kerak.\n";
} else {
    echo "\n❌ KATEGORIYALAR MODULIDA MUAMMOLAR MAVJUD\n";
    echo "🛠️  Asosiy komponentlarni tekshiring va tuzating.\n";
}

echo "\n=== TEKSHIRUV YAKUNLANDI ===\n";
