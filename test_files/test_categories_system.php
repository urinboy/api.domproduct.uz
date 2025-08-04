<?php

/**
 * Categories System Test
 * Tests the complete categories functionality including translations and image handling
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\Category;
use App\Models\Language;
use Illuminate\Contracts\Http\Kernel;

// Laravel Bootstrap
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);

// Handle a fake request to initialize the application
$request = Illuminate\Http\Request::create('/', 'GET');
$response = $kernel->handle($request);

try {
    echo "🏗️  Testing Categories System...\n";
    echo str_repeat("=", 60) . "\n";

    // Test 1: Check Category Model functionality
    echo "1. Testing Category Model Methods:\n";
    $categories = Category::with(['translations.language', 'parent', 'children'])->get();

    foreach ($categories->take(3) as $category) {
        echo "Category: {$category->id}\n";
        echo "  - getName(): " . $category->getName() . "\n";
        echo "  - getSlug(): " . $category->getSlug() . "\n";
        echo "  - hasChildren(): " . ($category->hasChildren() ? 'Yes' : 'No') . "\n";
        echo "  - getDepth(): " . $category->getDepth() . "\n";
        echo "  - getImageUrl(): " . $category->getImageUrl() . "\n";
        echo "  - getIconUrl(): " . $category->getIconUrl() . "\n";
        echo "  - Translations count: " . $category->translations->count() . "\n";
        echo "  - Children count: " . $category->children->count() . "\n";
        echo "  - Products count: " . $category->products()->count() . "\n";

        // Test getPath method
        $path = $category->getPath();
        if (!empty($path)) {
            echo "  - Path: " . implode(' > ', $path) . "\n";
        }

        echo "\n";
    }

    // Test 2: Check Language support
    echo "2. Testing Language Support:\n";
    $languages = Language::active()->get();
    echo "Active languages: " . $languages->count() . "\n";

    foreach ($languages as $language) {
        echo "  - {$language->name} ({$language->code})";
        if ($language->is_default) {
            echo " [DEFAULT]";
        }
        echo "\n";
    }
    echo "\n";

    // Test 3: Test category scopes
    echo "3. Testing Category Scopes:\n";
    echo "  - Active categories: " . Category::active()->count() . "\n";
    echo "  - Parent categories: " . Category::parents()->count() . "\n";
    echo "  - Ordered categories: " . Category::ordered()->count() . "\n";
    echo "\n";

    // Test 4: Test category translations
    echo "4. Testing Category Translations:\n";
    $testCategory = $categories->first();
    if ($testCategory) {
        echo "Testing category: {$testCategory->id}\n";

        foreach ($languages as $language) {
            $name = $testCategory->getName($language->code);
            $slug = $testCategory->getSlug($language->code);
            echo "  - {$language->name}: '{$name}' (slug: {$slug})\n";
        }
    }
    echo "\n";

    // Test 5: Test image URL generation
    echo "5. Testing Image URL Generation:\n";
    $testCategory = $categories->first();
    if ($testCategory) {
        echo "Category: {$testCategory->getName()}\n";
        $sizes = ['thumbnail', 'small', 'medium', 'large', 'original'];

        foreach ($sizes as $size) {
            $url = $testCategory->getImageUrl($size);
            echo "  - {$size}: {$url}\n";
        }

        echo "  - All sizes: \n";
        $allSizes = $testCategory->getImageSizes();
        foreach ($allSizes as $size => $url) {
            echo "    * {$size}: {$url}\n";
        }
    }
    echo "\n";

    // Test 6: Test parent-child relationships
    echo "6. Testing Parent-Child Relationships:\n";
    $parentCategories = Category::parents()->with(['children'])->get();

    foreach ($parentCategories->take(3) as $parent) {
        echo "Parent: {$parent->getName()}\n";

        if ($parent->children->count() > 0) {
            echo "  Children:\n";
            foreach ($parent->children as $child) {
                echo "    - {$child->getName()} (depth: {$child->getDepth()})\n";
            }
        } else {
            echo "  No children\n";
        }
        echo "\n";
    }

    // Test 7: Check translation coverage
    echo "7. Testing Translation Coverage:\n";
    $translationStats = [];

    foreach ($categories as $category) {
        foreach ($languages as $language) {
            $translation = $category->translations()
                ->whereHas('language', function ($query) use ($language) {
                    $query->where('code', $language->code);
                })->first();

            if (!isset($translationStats[$language->code])) {
                $translationStats[$language->code] = ['total' => 0, 'translated' => 0];
            }

            $translationStats[$language->code]['total']++;
            if ($translation) {
                $translationStats[$language->code]['translated']++;
            }
        }
    }

    foreach ($translationStats as $langCode => $stats) {
        $percentage = $stats['total'] > 0 ? round(($stats['translated'] / $stats['total']) * 100, 1) : 0;
        echo "  - {$langCode}: {$stats['translated']}/{$stats['total']} ({$percentage}%)\n";
    }
    echo "\n";

    // Test 8: Performance test
    echo "8. Performance Test:\n";
    $start = microtime(true);

    // Complex query test
    $complexQuery = Category::with([
        'translations.language',
        'parent.translations',
        'children.translations',
        'products'
    ])->active()->ordered()->get();

    $end = microtime(true);
    $executionTime = round(($end - $start) * 1000, 2);

    echo "  - Complex query with relationships: {$executionTime}ms\n";
    echo "  - Categories loaded: " . $complexQuery->count() . "\n";
    echo "  - Total relationships loaded: " .
         ($complexQuery->sum(function($cat) { return $cat->translations->count(); }) +
          $complexQuery->sum(function($cat) { return $cat->children->count(); }) +
          $complexQuery->sum(function($cat) { return $cat->products->count(); })) . "\n";
    echo "\n";

    // Test 9: URL accessibility test
    echo "9. Testing URL Accessibility:\n";
    $testCategory = $categories->first();
    if ($testCategory) {
        $imageUrl = $testCategory->getImageUrl();
        $iconUrl = $testCategory->getIconUrl();

        echo "  - Image URL: {$imageUrl}\n";
        echo "  - Icon URL: {$iconUrl}\n";

        // Check if URLs are properly formatted
        if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            echo "  ✅ Image URL is valid\n";
        } else {
            echo "  ❌ Image URL is invalid\n";
        }

        if (filter_var($iconUrl, FILTER_VALIDATE_URL)) {
            echo "  ✅ Icon URL is valid\n";
        } else {
            echo "  ❌ Icon URL is invalid\n";
        }
    }
    echo "\n";

    // Summary
    echo "📊 SUMMARY:\n";
    echo str_repeat("-", 40) . "\n";
    echo "✅ Total Categories: " . $categories->count() . "\n";
    echo "✅ Active Categories: " . Category::active()->count() . "\n";
    echo "✅ Parent Categories: " . Category::parents()->count() . "\n";
    echo "✅ Languages Available: " . $languages->count() . "\n";
    echo "✅ Categories with Children: " . $categories->filter(function($cat) { return $cat->hasChildren(); })->count() . "\n";
    echo "✅ Categories with Products: " . $categories->filter(function($cat) { return $cat->products()->count() > 0; })->count() . "\n";
    echo "\n";
    echo "🎉 Categories system test completed successfully!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
