<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Product;

echo "=== Database Performance Test ===\n";

// Query loglarni yoqamiz
DB::enableQueryLog();

$start = microtime(true);

// Products API'ga o'xshash query
$products = Product::with(['translations' => function($query) {
    $query->where('language', 'uz');
}, 'images' => function($query) {
    $query->limit(1);
}, 'category.translations' => function($query) {
    $query->where('language_id', 1);
}])->where('is_active', true)->paginate(10);

$end = microtime(true);
$executionTime = ($end - $start) * 1000; // ms ga o'tkazamiz

$queries = DB::getQueryLog();

echo "Execution time: " . round($executionTime, 2) . "ms\n";
echo "Total queries: " . count($queries) . "\n";
echo "Products found: " . $products->count() . "\n\n";

echo "=== Query Details ===\n";
foreach ($queries as $i => $query) {
    echo ($i + 1) . ". " . $query['query'] . "\n";
    echo "   Time: " . $query['time'] . "ms\n\n";
}

// Indexlar haqida ma'lumot
echo "=== Index Usage Check ===\n";
$explainQuery = "EXPLAIN SELECT * FROM products WHERE is_active = 1 LIMIT 10";
$result = DB::select($explainQuery);
print_r($result);
