<?php

echo "=== BLADE FAYLLAR TO'G'RI TUZILISHI NAMUNASI ===\n\n";

$correctStructure = [
    '@extends(\'admin.layouts.app\')',
    '@section(\'title\', __(\'admin.some_title\'))',
    '@push(\'styles\')',
    'CSS styles...',
    '@endpush',
    '@section(\'content\')',
    'Content here...',
    '@endsection',
    '@push(\'scripts\')',
    'JavaScript code...',
    '@endpush'
];

echo "TO'G'RI STRUKTURA:\n";
foreach ($correctStructure as $i => $line) {
    echo sprintf("%2d. %s\n", $i + 1, $line);
}

echo "\nHAR BIR FAYL UCHUN:\n";
echo "- @section('title', '...') -> INLINE, @endsection KERAK EMAS\n";
echo "- @section('content') -> BLOCK, @endsection KERAK\n";
echo "- @push('styles') -> @endpush KERAK\n";
echo "- @push('scripts') -> @endpush KERAK\n";

echo "\n=== INDEX.BLADE.PHP NI TO'G'RILASH ===\n";

$indexFile = __DIR__ . '/../resources/views/admin/categories/index.blade.php';
if (file_exists($indexFile)) {
    $content = file_get_contents($indexFile);

    // Count all sections and their closing tags
    $lines = explode("\n", $content);
    $openSections = 0;
    $closedSections = 0;
    $inContentSection = false;

    foreach ($lines as $lineNum => $line) {
        $line = trim($line);

        if (strpos($line, "@section('content')") !== false) {
            $openSections++;
            $inContentSection = true;
            echo "Qator " . ($lineNum + 1) . ": Content section boshlandi\n";
        }

        if (strpos($line, "@endsection") !== false) {
            $closedSections++;
            $inContentSection = false;
            echo "Qator " . ($lineNum + 1) . ": Section yakunlandi\n";
        }
    }

    echo "Ochiq sectionlar: $openSections\n";
    echo "Yopiq sectionlar: $closedSections\n";

    if ($openSections != $closedSections) {
        echo "⚠️ MUAMMO: Content section to'liq yopilmagan!\n";
        echo "Oxirgi @endsection dan oldin @endsection qo'shish kerak.\n";
    }
}

echo "\n=== YAKUNIY TEST ===\n";
