<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Test password checking
$user = User::where('email', 'admin@domproduct.uz')->first();

if ($user) {
    echo "User found: " . $user->email . "\n";
    echo "Stored hash: " . $user->password . "\n";
    echo "Password 'password' check: " . (Hash::check('password', $user->password) ? 'TRUE' : 'FALSE') . "\n";
    echo "User is_active: " . ($user->is_active ? 'TRUE' : 'FALSE') . "\n";
} else {
    echo "User not found!\n";
}
