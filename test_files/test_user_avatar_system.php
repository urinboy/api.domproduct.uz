<?php

/**
 * User Avatar System Test
 * Tests the user avatar functionality including default avatar support
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\User;
use Illuminate\Contracts\Http\Kernel;

// Laravel Bootstrap
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);

// Handle a fake request to initialize the application
$request = Illuminate\Http\Request::create('/', 'GET');
$response = $kernel->handle($request);

// Database connection test
try {
    echo "🔧 Testing User Avatar System...\n";
    echo str_repeat("=", 50) . "\n";

    // Test 1: Get all users and check avatar URLs
    echo "1. Testing all users' avatar URLs:\n";
    $users = User::all();

    foreach ($users as $user) {
        echo "User: {$user->name} (ID: {$user->id})\n";
        echo "  - Has Avatar: " . ($user->hasAvatar() ? 'Yes' : 'No') . "\n";
        echo "  - Avatar URL: " . $user->getAvatarUrl() . "\n";
        echo "  - Default Avatar: " . $user->getDefaultAvatar() . "\n";
        echo "  - Avatar Column: " . ($user->avatar ?? 'NULL') . "\n";
        echo "\n";
    }

    // Test 2: Check if default avatar file exists
    echo "2. Testing default avatar file:\n";
    $defaultAvatarPath = public_path('images/default-avatar.png');
    if (file_exists($defaultAvatarPath)) {
        echo "✅ Default avatar file exists at: {$defaultAvatarPath}\n";
        echo "   File size: " . formatBytes(filesize($defaultAvatarPath)) . "\n";
    } else {
        echo "❌ Default avatar file NOT found at: {$defaultAvatarPath}\n";
    }
    echo "\n";

    // Test 3: Test URL accessibility
    echo "3. Testing avatar URL accessibility:\n";
    $testUser = $users->first();
    if ($testUser) {
        $avatarUrl = $testUser->getAvatarUrl();
        echo "Testing URL: {$avatarUrl}\n";

        // Check if it's a relative URL (starts with /)
        if (strpos($avatarUrl, '/') === 0) {
            $fullPath = public_path(ltrim($avatarUrl, '/'));
            if (file_exists($fullPath)) {
                echo "✅ Avatar file accessible at: {$fullPath}\n";
            } else {
                echo "❌ Avatar file NOT found at: {$fullPath}\n";
            }
        } else {
            echo "ℹ️  Avatar URL is absolute: {$avatarUrl}\n";
        }
    }
    echo "\n";

    // Test 4: Create a test user without avatar
    echo "4. Testing user without custom avatar:\n";
    $testUserData = [
        'name' => 'Test User Avatar',
        'email' => 'test.avatar.' . time() . '@example.com',
        'password' => bcrypt('password'),
        'avatar' => null // No custom avatar
    ];

    $newUser = User::create($testUserData);
    echo "Created test user: {$newUser->name}\n";
    echo "  - Has Avatar: " . ($newUser->hasAvatar() ? 'Yes' : 'No') . "\n";
    echo "  - Avatar URL: " . $newUser->getAvatarUrl() . "\n";
    echo "  - Should use default: " . ($newUser->getAvatarUrl() === $newUser->getDefaultAvatar() ? 'Yes' : 'No') . "\n";

    // Clean up test user
    $newUser->delete();
    echo "  - Test user deleted\n";
    echo "\n";

    // Test 5: Translation test
    echo "5. Testing translations:\n";
    $languages = ['uz', 'en', 'ru'];

    foreach ($languages as $lang) {
        app()->setLocale($lang);
        echo "Language: {$lang}\n";
        echo "  - Users: " . __('admin.users') . "\n";
        echo "  - Name: " . __('admin.name') . "\n";
        echo "  - Email: " . __('admin.email') . "\n";
        echo "  - Avatar: " . __('admin.avatar') . "\n";
        echo "\n";
    }

    echo "✅ All tests completed successfully!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

function formatBytes($size, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    for ($i = 0; $size >= 1024 && $i < count($units) - 1; $i++) {
        $size /= 1024;
    }
    return round($size, $precision) . ' ' . $units[$i];
}
