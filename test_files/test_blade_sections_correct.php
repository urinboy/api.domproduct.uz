<?php

echo "=== BLADE FAYLLAR TO'G'RILANGAN SECTION TEKSHIRUVI ===\n\n";

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

    // Faqat block sectionlarni hisoblash (inline emas)
    $blockSections = 0;
    $endSections = 0;

    $lines = explode("\n", $content);
    foreach ($lines as $line) {
        $line = trim($line);

        // Block section (inline emas)
        if (preg_match('/^@section\(\s*[\'"](\w+)[\'"]\s*\)$/', $line)) {
            $blockSections++;
        }

        // Endsection
        if ($line === '@endsection') {
            $endSections++;
        }
    }

    // Push/endpush hisoblash
    $startPush = substr_count($content, '@push(');
    $endPush = substr_count($content, '@endpush');

    echo "   📊 Block sections: {$blockSections} boshlanish / {$endSections} tugash\n";
    echo "   📊 Push: {$startPush} boshlanish / {$endPush} tugash\n";

    // Check balance
    if ($blockSections == $endSections) {
        echo "   ✅ Block sections balanslangan\n";
    } else {
        echo "   ❌ Block sections balanssiz! Farq: " . ($blockSections - $endSections) . "\n";
    }

    if ($startPush == $endPush) {
        echo "   ✅ Push/endpush balanslangan\n";
    } else {
        echo "   ❌ Push/endpush balanssiz! Farq: " . ($startPush - $endPush) . "\n";
    }

    // Check required elements
    $hasExtends = strpos($content, "@extends('admin.layouts.app')") !== false;
    $hasContentSection = preg_match('/^@section\(\s*[\'"]content[\'"]\s*\)$/m', $content);
    $hasMainEndsection = strpos($content, "@endsection") !== false;

    echo "   " . ($hasExtends ? "✅" : "❌") . " @extends mavjud\n";
    echo "   " . ($hasContentSection ? "✅" : "❌") . " @section('content') mavjud\n";
    echo "   " . ($hasMainEndsection ? "✅" : "❌") . " @endsection mavjud\n";

    // Overall status
    $isBalanced = ($blockSections == $endSections) && ($startPush == $endPush);
    echo "   🎯 HOLAT: " . ($isBalanced ? "TO'G'RI ✅" : "XATOLIK ❌") . "\n";

    echo "\n";
}

echo "=== TEKSHIRUV YAKUNLANDI ===\n";
