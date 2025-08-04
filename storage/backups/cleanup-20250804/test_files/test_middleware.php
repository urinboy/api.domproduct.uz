<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Rate limiting middleware testini simulyatsiya qilamiz
use App\Http\Middleware\RateLimitMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

echo "=== Rate Limiting Test ===\n";

// Request yaratamiz
$request = Request::create('/api/v1/products', 'GET');
$request->server->set('REMOTE_ADDR', '127.0.0.1');

// Middleware yaratamiz
$rateLimitMiddleware = new RateLimitMiddleware();

// 5 ta request yuboramiz
for ($i = 1; $i <= 7; $i++) {
    $response = null;

    try {
        $response = $rateLimitMiddleware->handle($request, function ($request) {
            return response()->json(['status' => 'success', 'data' => 'test']);
        }, 5, 1); // 5 requests per minute

        echo "Request $i: " . $response->getStatusCode() . " - ";

        if ($response->getStatusCode() == 200) {
            echo "SUCCESS\n";
        } else {
            echo "RATE LIMITED\n";
            echo "Response: " . $response->getContent() . "\n";
        }

        // Rate limit headers ko'rsatamiz
        if ($response->headers->has('X-RateLimit-Remaining')) {
            echo "  Remaining: " . $response->headers->get('X-RateLimit-Remaining') . "\n";
        }

    } catch (Exception $e) {
        echo "Request $i: ERROR - " . $e->getMessage() . "\n";
    }
}

echo "\n=== Security Headers Test ===\n";

// Security headers middleware testini simulyatsiya qilamiz
use App\Http\Middleware\SecurityHeadersMiddleware;

$securityMiddleware = new SecurityHeadersMiddleware();

$response = $securityMiddleware->handle($request, function ($request) {
    return response()->json(['status' => 'success']);
});

echo "Security Headers:\n";
$securityHeaders = [
    'X-Content-Type-Options',
    'X-Frame-Options',
    'X-XSS-Protection',
    'Referrer-Policy',
    'Access-Control-Allow-Origin',
    'API-Version'
];

foreach ($securityHeaders as $header) {
    if ($response->headers->has($header)) {
        echo "  $header: " . $response->headers->get($header) . "\n";
    } else {
        echo "  $header: NOT SET\n";
    }
}
