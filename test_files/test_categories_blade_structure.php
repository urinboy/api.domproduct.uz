<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Laravel bootstrap
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== KATEGORIYA BLADE FAYLLAR TUZILISHI TEKSHIRUVI ===\n\n";

$categoryViews = [
    'resources/views/admin/categories/index.blade.php',
    'resources/views/admin/categories/create.blade.php',
    'resources/views/admin/categories/edit.blade.php',
    'resources/views/admin/categories/show.blade.php'
];

$userViews = [
    'resources/views/admin/users/index.blade.php',
    'resources/views/admin/users/create.blade.php',
    'resources/views/admin/users/edit.blade.php',
    'resources/views/admin/users/show.blade.php'
];

// Tekshiriladigan elementlar
$requiredElements = [
    '@extends(\'admin.layouts.app\')',
    '@section(\'title\'',
    '@push(\'styles\')',
    'content-header',
    'container-fluid',
    'breadcrumb',
    'section class="content"',
    '@endsection'
];

echo "1. KATEGORIYA FAYLLAR TUZILISHI:\n";
foreach ($categoryViews as $view) {
    $fullPath = __DIR__ . '/../' . $view;
    $fileName = basename($view);

    echo "   📄 {$fileName}:\n";

    if (!file_exists($fullPath)) {
        echo "     ❌ Fayl mavjud emas!\n";
        continue;
    }

    $content = file_get_contents($fullPath);
    $foundElements = 0;

    foreach ($requiredElements as $element) {
        $found = strpos($content, $element) !== false;
        echo "     " . ($found ? "✅" : "❌") . " {$element}\n";
        if ($found) $foundElements++;
    }

    $percentage = round(($foundElements / count($requiredElements)) * 100, 1);
    echo "     📊 Mos kelish: {$percentage}%\n\n";
}

echo "2. USERS BILAN SOLISHTIRUV:\n";
foreach ($userViews as $view) {
    $fullPath = __DIR__ . '/../' . $view;
    $fileName = basename($view);

    echo "   📄 {$fileName}:\n";

    if (!file_exists($fullPath)) {
        echo "     ❌ Fayl mavjud emas!\n";
        continue;
    }

    $content = file_get_contents($fullPath);
    $foundElements = 0;

    foreach ($requiredElements as $element) {
        $found = strpos($content, $element) !== false;
        if ($found) $foundElements++;
    }

    $percentage = round(($foundElements / count($requiredElements)) * 100, 1);
    echo "     📊 Mos kelish: {$percentage}%\n";
}

echo "\n3. DIZAYN ELEMENTLARI TEKSHIRUVI:\n";

$designElements = [
    'form-section' => 'Form bo\'limlari',
    'card-header' => 'Karta sarlavhasi',
    'card-body' => 'Karta tanasi',
    'btn btn-primary' => 'Asosiy tugmalar',
    'table table-' => 'Jadval stillari',
    'form-control' => 'Form elementlari',
    'breadcrumb' => 'Breadcrumb navigatsiya',
    'content-header' => 'Sahifa sarlavhasi'
];

foreach ($categoryViews as $view) {
    $fullPath = __DIR__ . '/../' . $view;
    $fileName = basename($view);

    echo "   📄 {$fileName}:\n";

    if (!file_exists($fullPath)) {
        continue;
    }

    $content = file_get_contents($fullPath);

    foreach ($designElements as $class => $description) {
        $found = strpos($content, $class) !== false;
        echo "     " . ($found ? "✅" : "❌") . " {$description} ({$class})\n";
    }
    echo "\n";
}

echo "4. RESPONSIVE DIZAYN TEKSHIRUVI:\n";

$responsiveElements = [
    'col-md-' => 'Medium ekranlar',
    'col-sm-' => 'Kichik ekranlar',
    'col-lg-' => 'Katta ekranlar',
    'col-12' => 'To\'liq kenglik',
    'd-flex' => 'Flexbox',
    'table-responsive' => 'Responsive jadval'
];

foreach ($categoryViews as $view) {
    $fullPath = __DIR__ . '/../' . $view;
    $fileName = basename($view);

    if (!file_exists($fullPath)) {
        continue;
    }

    $content = file_get_contents($fullPath);
    $responsiveCount = 0;

    foreach ($responsiveElements as $class => $description) {
        if (strpos($content, $class) !== false) {
            $responsiveCount++;
        }
    }

    $responsivePercentage = round(($responsiveCount / count($responsiveElements)) * 100, 1);
    echo "   📱 {$fileName}: {$responsivePercentage}% responsive\n";
}

echo "\n5. JAVASCRIPT VA AJAX TEKSHIRUVI:\n";

$jsElements = [
    '@push(\'scripts\')' => 'JavaScript bo\'limi',
    '$(document).ready' => 'jQuery tayyor',
    'ajax' => 'AJAX so\'rovlar',
    '.click(' => 'Click hodisalar',
    '.change(' => 'Change hodisalar'
];

foreach ($categoryViews as $view) {
    $fullPath = __DIR__ . '/../' . $view;
    $fileName = basename($view);

    if (!file_exists($fullPath)) {
        continue;
    }

    $content = file_get_contents($fullPath);
    $jsCount = 0;

    foreach ($jsElements as $element => $description) {
        if (strpos($content, $element) !== false) {
            $jsCount++;
        }
    }

    if ($jsCount > 0) {
        echo "   🟢 {$fileName}: {$jsCount} JavaScript elementi\n";
    } else {
        echo "   🔴 {$fileName}: JavaScript yo'q\n";
    }
}

echo "\n6. YAKUNIY BAHOLASH:\n";

$totalScore = 0;
$maxScore = 0;

foreach ($categoryViews as $view) {
    $fullPath = __DIR__ . '/../' . $view;
    $fileName = basename($view);

    if (!file_exists($fullPath)) {
        continue;
    }

    $content = file_get_contents($fullPath);
    $score = 0;

    // Structure check
    foreach ($requiredElements as $element) {
        if (strpos($content, $element) !== false) {
            $score += 2;
        }
        $maxScore += 2;
    }

    // Design check
    foreach ($designElements as $class => $description) {
        if (strpos($content, $class) !== false) {
            $score += 1;
        }
        $maxScore += 1;
    }

    // Responsive check
    foreach ($responsiveElements as $class => $description) {
        if (strpos($content, $class) !== false) {
            $score += 1;
        }
        $maxScore += 1;
    }

    $totalScore += $score;
    $percentage = round(($score / (count($requiredElements) * 2 + count($designElements) + count($responsiveElements))) * 100, 1);

    echo "   📊 {$fileName}: {$percentage}% (Professional)\n";
}

$overallPercentage = round(($totalScore / $maxScore) * 100, 1);

echo "\n🎯 UMUMIY NATIJA: {$overallPercentage}%\n";

if ($overallPercentage >= 90) {
    echo "🏆 A'LO! Kategoriya blade fayllar professional darajada!\n";
} elseif ($overallPercentage >= 80) {
    echo "👍 YAXSHI! Kategoriya blade fayllar yaxshi holatda!\n";
} elseif ($overallPercentage >= 70) {
    echo "⚠️  O'RTACHA! Ba'zi yaxshilashlar kerak!\n";
} else {
    echo "❌ YOMON! Jiddiy yaxshilashlar talab qilinadi!\n";
}

echo "\n=== TEKSHIRUV YAKUNLANDI ===\n";
