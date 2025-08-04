<?php

echo "=== BLADE FAYLLAR SECTION XATOLIKLARI TEKSHIRUVI ===\n\n";

$files = [
    'resources/views/admin/categories/index.blade.php',
    'resources/views/admin/categories/create.blade.php',
    'resources/views/admin/categories/edit.blade.php',
    'resources/views/admin/categories/show.blade.php'
];

foreach ($files as $file) {
    $fullPath = __DIR__ . '/../' . $file;
    $fileName = basename($file);

    echo "📄 {$fileName}:\n";

    if (!file_exists($fullPath)) {
        echo "   ❌ Fayl mavjud emas!\n";
        continue;
    }

    $content = file_get_contents($fullPath);

    // Count sections
    $startSections = substr_count($content, '@section(');
    $endSections = substr_count($content, '@endsection');

    // Count push/endpush
    $startPush = substr_count($content, '@push(');
    $endPush = substr_count($content, '@endpush');

    echo "   📊 Sections: {$startSections} boshlanish / {$endSections} tugash\n";
    echo "   📊 Push: {$startPush} boshlanish / {$endPush} tugash\n";

    // Check balance
    if ($startSections == $endSections) {
        echo "   ✅ Sections balanslangan\n";
    } else {
        echo "   ❌ Sections balanssiz! Farq: " . ($startSections - $endSections) . "\n";
    }

    if ($startPush == $endPush) {
        echo "   ✅ Push/endpush balanslangan\n";
    } else {
        echo "   ❌ Push/endpush balanssiz! Farq: " . ($startPush - $endPush) . "\n";
    }

    // Check specific patterns
    $hasExtends = strpos($content, "@extends('admin.layouts.app')") !== false;
    $hasContentSection = strpos($content, "@section('content')") !== false;
    $hasMainEndsection = strpos($content, "@endsection") !== false;

    echo "   " . ($hasExtends ? "✅" : "❌") . " @extends mavjud\n";
    echo "   " . ($hasContentSection ? "✅" : "❌") . " @section('content') mavjud\n";
    echo "   " . ($hasMainEndsection ? "✅" : "❌") . " @endsection mavjud\n";

    echo "\n";
}

echo "=== TEKSHIRUV YAKUNLANDI ===\n";
