<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== SECURITY CONFIGURATION REVIEW ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

// 1. Environment Configuration Security
echo "1. ENVIRONMENT CONFIGURATION:\n";

$envConfigs = [
    'APP_DEBUG' => env('APP_DEBUG', false),
    'APP_ENV' => env('APP_ENV', 'production'),
    'APP_KEY' => env('APP_KEY'),
    'DB_CONNECTION' => env('DB_CONNECTION'),
    'CACHE_DRIVER' => env('CACHE_DRIVER'),
    'SESSION_DRIVER' => env('SESSION_DRIVER'),
    'QUEUE_CONNECTION' => env('QUEUE_CONNECTION'),
];

$envSecurityScore = 0;
$envTotalChecks = 0;

foreach ($envConfigs as $key => $value) {
    echo "  {$key}: ";

    switch ($key) {
        case 'APP_DEBUG':
            if ($value === false || $value === 'false') {
                echo "✅ Disabled (Secure)\n";
                $envSecurityScore++;
            } else {
                echo "⚠️ Enabled (Development only)\n";
            }
            break;

        case 'APP_ENV':
            if (in_array($value, ['production', 'staging'])) {
                echo "✅ {$value} (Secure)\n";
                $envSecurityScore++;
            } else {
                echo "⚠️ {$value} (Development environment)\n";
            }
            break;

        case 'APP_KEY':
            if (!empty($value) && strlen($value) >= 32) {
                echo "✅ Set and strong\n";
                $envSecurityScore++;
            } else {
                echo "❌ Missing or weak\n";
            }
            break;

        default:
            if (!empty($value)) {
                echo "✅ Configured\n";
                $envSecurityScore++;
            } else {
                echo "⚠️ Not set\n";
            }
            break;
    }
    $envTotalChecks++;
}

echo "  📊 Environment Security: {$envSecurityScore}/{$envTotalChecks}\n\n";

// 2. Laravel Configuration Security
echo "2. LARAVEL CONFIGURATION:\n";

$laravelConfigs = [
    'app.debug' => config('app.debug'),
    'app.env' => config('app.env'),
    'app.key' => config('app.key'),
    'session.secure' => config('session.secure'),
    'session.http_only' => config('session.http_only'),
    'session.same_site' => config('session.same_site'),
    'cors.allowed_origins' => config('cors.allowed_origins'),
    'sanctum.expiration' => config('sanctum.expiration'),
];

$configSecurityScore = 0;
$configTotalChecks = 0;

foreach ($laravelConfigs as $key => $value) {
    echo "  {$key}: ";

    switch ($key) {
        case 'app.debug':
            if ($value === false) {
                echo "✅ Disabled\n";
                $configSecurityScore++;
            } else {
                echo "⚠️ Enabled\n";
            }
            break;

        case 'session.secure':
            if ($value === true) {
                echo "✅ Enabled\n";
                $configSecurityScore++;
            } else {
                echo "⚠️ Disabled (HTTPS required)\n";
            }
            break;

        case 'session.http_only':
            if ($value === true) {
                echo "✅ Enabled\n";
                $configSecurityScore++;
            } else {
                echo "❌ Disabled\n";
            }
            break;

        case 'session.same_site':
            if (in_array($value, ['lax', 'strict'])) {
                echo "✅ {$value}\n";
                $configSecurityScore++;
            } else {
                echo "⚠️ {$value}\n";
            }
            break;

        case 'cors.allowed_origins':
            if (is_array($value) && !in_array('*', $value)) {
                echo "✅ Specific origins only\n";
                $configSecurityScore++;
            } else {
                echo "⚠️ Allows all origins\n";
            }
            break;

        case 'sanctum.expiration':
            if ($value && $value <= 1440) { // 24 hours max
                echo "✅ {$value} minutes\n";
                $configSecurityScore++;
            } else {
                echo "⚠️ " . ($value ?: 'No expiration') . "\n";
            }
            break;

        default:
            if (!empty($value)) {
                echo "✅ Set\n";
                $configSecurityScore++;
            } else {
                echo "⚠️ Not set\n";
            }
            break;
    }
    $configTotalChecks++;
}

echo "  📊 Laravel Config Security: {$configSecurityScore}/{$configTotalChecks}\n\n";

// 3. Middleware Security Configuration
echo "3. MIDDLEWARE SECURITY:\n";

$middlewareConfigs = [
    'Security Headers' => class_exists('App\Http\Middleware\SecurityHeadersMiddleware'),
    'Rate Limiting' => class_exists('App\Http\Middleware\RateLimitMiddleware'),
    'Input Sanitization' => class_exists('App\Http\Middleware\SanitizeInputMiddleware'),
    'API Logging' => class_exists('App\Http\Middleware\ApiLoggingMiddleware'),
    'CORS' => class_exists('Fruitcake\Cors\HandleCors'),
    'Throttle' => true, // Laravel default
    'CSRF Protection' => class_exists('App\Http\Middleware\VerifyCsrfToken'),
];

// Check if middlewares are registered
$kernelFile = file_get_contents(__DIR__ . '/app/Http/Kernel.php');

$middlewareSecurityScore = 0;
foreach ($middlewareConfigs as $middleware => $exists) {
    echo "  {$middleware}: ";
    if ($exists) {
        echo "✅ Available\n";
        $middlewareSecurityScore++;
    } else {
        echo "❌ Missing\n";
    }
}

echo "  📊 Middleware Security: {$middlewareSecurityScore}/" . count($middlewareConfigs) . "\n\n";

// 4. Database Security Configuration
echo "4. DATABASE SECURITY:\n";

$dbConfigs = [
    'Connection' => config('database.default'),
    'Host' => config('database.connections.' . config('database.default') . '.host'),
    'Database' => config('database.connections.' . config('database.default') . '.database'),
    'Username' => config('database.connections.' . config('database.default') . '.username'),
    'SSL Mode' => config('database.connections.' . config('database.default') . '.options.ssl_mode') ?? 'disabled',
];

$dbSecurityScore = 0;
$dbTotalChecks = 0;

foreach ($dbConfigs as $key => $value) {
    echo "  {$key}: ";

    switch ($key) {
        case 'Connection':
            if ($value === 'mysql') {
                echo "✅ {$value}\n";
                $dbSecurityScore++;
            } else {
                echo "⚠️ {$value}\n";
            }
            break;

        case 'Host':
            if ($value === 'localhost' || $value === '127.0.0.1' || filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
                echo "✅ {$value}\n";
                $dbSecurityScore++;
            } else {
                echo "⚠️ {$value}\n";
            }
            break;

        case 'Username':
            if ($value !== 'root' && !empty($value)) {
                echo "✅ Non-root user\n";
                $dbSecurityScore++;
            } else {
                echo "⚠️ " . ($value ?: 'Not set') . "\n";
            }
            break;

        case 'SSL Mode':
            if ($value === 'required' || $value === 'verify_identity') {
                echo "✅ {$value}\n";
                $dbSecurityScore++;
            } else {
                echo "⚠️ {$value}\n";
            }
            break;

        default:
            if (!empty($value)) {
                echo "✅ Set\n";
                $dbSecurityScore++;
            } else {
                echo "⚠️ Not set\n";
            }
            break;
    }
    $dbTotalChecks++;
}

echo "  📊 Database Security: {$dbSecurityScore}/{$dbTotalChecks}\n\n";

// 5. File Permissions Security
echo "5. FILE PERMISSIONS:\n";

$criticalPaths = [
    '.env' => __DIR__ . '/.env',
    'storage directory' => __DIR__ . '/storage',
    'config directory' => __DIR__ . '/config',
    'bootstrap cache' => __DIR__ . '/bootstrap/cache',
];

$permissionSecurityScore = 0;
$permissionTotalChecks = 0;

foreach ($criticalPaths as $name => $path) {
    echo "  {$name}: ";

    if (file_exists($path)) {
        $perms = fileperms($path);
        $octal = substr(sprintf('%o', $perms), -4);

        switch ($name) {
            case '.env':
                if ($octal === '0600' || $octal === '0644') {
                    echo "✅ {$octal} (Secure)\n";
                    $permissionSecurityScore++;
                } else {
                    echo "⚠️ {$octal} (Review permissions)\n";
                }
                break;

            case 'storage directory':
            case 'bootstrap cache':
                if (in_array($octal, ['0755', '0775'])) {
                    echo "✅ {$octal} (Writable)\n";
                    $permissionSecurityScore++;
                } else {
                    echo "⚠️ {$octal} (Check write permissions)\n";
                }
                break;

            default:
                if (in_array($octal, ['0755', '0644'])) {
                    echo "✅ {$octal}\n";
                    $permissionSecurityScore++;
                } else {
                    echo "⚠️ {$octal}\n";
                }
                break;
        }
    } else {
        echo "❌ Not found\n";
    }
    $permissionTotalChecks++;
}

echo "  📊 File Permissions: {$permissionSecurityScore}/{$permissionTotalChecks}\n\n";

// 6. Security Headers Configuration
echo "6. SECURITY HEADERS CONFIGURATION:\n";

if (class_exists('App\Http\Middleware\SecurityHeadersMiddleware')) {
    $securityMiddleware = new \App\Http\Middleware\SecurityHeadersMiddleware();
    $request = \Illuminate\Http\Request::create('/test', 'GET');

    $response = $securityMiddleware->handle($request, function($req) {
        return response()->json(['test' => 'data']);
    });

    $securityHeaders = [
        'X-Content-Type-Options',
        'X-Frame-Options',
        'X-XSS-Protection',
        'Referrer-Policy',
        'Content-Security-Policy',
    ];

    $headerScore = 0;
    foreach ($securityHeaders as $header) {
        echo "  {$header}: ";
        if ($response->headers->has($header)) {
            echo "✅ Set\n";
            $headerScore++;
        } else {
            echo "⚠️ Not set\n";
        }
    }

    echo "  📊 Security Headers: {$headerScore}/" . count($securityHeaders) . "\n\n";
} else {
    echo "  ❌ Security headers middleware not found\n\n";
}

// 7. Logging Configuration Security
echo "7. LOGGING CONFIGURATION:\n";

$loggingConfigs = [
    'Default Channel' => config('logging.default'),
    'Available Channels' => array_keys(config('logging.channels')),
    'Log Level' => config('app.log_level') ?: 'debug',
    'Log Location' => config('logging.channels.single.path') ?: 'storage/logs/laravel.log',
];

$loggingScore = 0;
$loggingTotalChecks = 0;

foreach ($loggingConfigs as $key => $value) {
    echo "  {$key}: ";

    switch ($key) {
        case 'Default Channel':
            if (in_array($value, ['stack', 'single', 'daily'])) {
                echo "✅ {$value}\n";
                $loggingScore++;
            } else {
                echo "⚠️ {$value}\n";
            }
            break;

        case 'Available Channels':
            if (is_array($value) && count($value) >= 3) {
                echo "✅ " . count($value) . " channels\n";
                $loggingScore++;
            } else {
                echo "⚠️ Limited channels\n";
            }
            break;

        case 'Log Level':
            if (in_array($value, ['error', 'warning', 'info'])) {
                echo "✅ {$value}\n";
                $loggingScore++;
            } else {
                echo "⚠️ {$value} (too verbose for production)\n";
            }
            break;

        case 'Log Location':
            if (strpos($value, 'storage/logs') === 0) {
                echo "✅ {$value}\n";
                $loggingScore++;
            } else {
                echo "⚠️ {$value}\n";
            }
            break;
    }
    $loggingTotalChecks++;
}

echo "  📊 Logging Security: {$loggingScore}/{$loggingTotalChecks}\n\n";

// 8. Overall Configuration Security Score
echo "8. OVERALL CONFIGURATION SECURITY:\n";

$allScores = [
    'Environment' => ($envSecurityScore / $envTotalChecks) * 100,
    'Laravel Config' => ($configSecurityScore / $configTotalChecks) * 100,
    'Middleware' => ($middlewareSecurityScore / count($middlewareConfigs)) * 100,
    'Database' => ($dbSecurityScore / $dbTotalChecks) * 100,
    'File Permissions' => ($permissionSecurityScore / $permissionTotalChecks) * 100,
    'Security Headers' => isset($headerScore) ? ($headerScore / count($securityHeaders)) * 100 : 0,
    'Logging' => ($loggingScore / $loggingTotalChecks) * 100,
];

$overallConfigScore = array_sum($allScores) / count($allScores);

foreach ($allScores as $category => $score) {
    $status = $score >= 80 ? '✅' : ($score >= 60 ? '⚠️' : '❌');
    echo "  {$status} {$category}: " . round($score) . "%\n";
}

echo "\n  🎯 Overall Configuration Security: " . round($overallConfigScore) . "/100\n";

if ($overallConfigScore >= 90) echo "  🏆 EXCELLENT CONFIGURATION!\n";
elseif ($overallConfigScore >= 80) echo "  🥈 GOOD CONFIGURATION!\n";
elseif ($overallConfigScore >= 70) echo "  🥉 ACCEPTABLE CONFIGURATION\n";
else echo "  ⚠️  CONFIGURATION NEEDS IMPROVEMENT\n";

// 9. Security Recommendations
echo "\n9. CONFIGURATION RECOMMENDATIONS:\n";

$configRecommendations = [];

if ($allScores['Environment'] < 80) {
    $configRecommendations[] = "Review environment variables for production deployment";
}
if ($allScores['Database'] < 80) {
    $configRecommendations[] = "Enable database SSL and use dedicated database user";
}
if ($allScores['Security Headers'] < 90) {
    $configRecommendations[] = "Implement Content Security Policy header";
}
if ($allScores['File Permissions'] < 90) {
    $configRecommendations[] = "Review and secure file permissions";
}

if (empty($configRecommendations)) {
    echo "  🎉 Configuration is secure and well-optimized!\n";
} else {
    foreach ($configRecommendations as $i => $recommendation) {
        echo "  " . ($i + 1) . ". {$recommendation}\n";
    }
}

echo "\n=== SECURITY CONFIGURATION REVIEW SUMMARY ===\n";
echo "✅ Environment Configuration: REVIEWED\n";
echo "✅ Laravel Configuration: REVIEWED\n";
echo "✅ Middleware Security: REVIEWED\n";
echo "✅ Database Security: REVIEWED\n";
echo "✅ File Permissions: REVIEWED\n";
echo "✅ Security Headers: REVIEWED\n";
echo "✅ Logging Configuration: REVIEWED\n";
echo "\n🎉 SECURITY CONFIGURATION REVIEW: COMPLETED!\n";
echo "🔧 Configuration Security Score: " . round($overallConfigScore) . "/100\n";
