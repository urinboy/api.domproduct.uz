<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| Main Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/home', function () {
    return view('home');
})->name('home.page');

// Global logout route
Route::post('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('global.logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Test Routes (Development Only)
|--------------------------------------------------------------------------
*/
Route::get('/test-auth', function () {
    return view('test-auth');
});

/*
|--------------------------------------------------------------------------
| Language Routes
|--------------------------------------------------------------------------
*/
Route::prefix('language')->name('language.')->group(function () {
    Route::get('/switch/{locale}', [LanguageController::class, 'switchLanguage'])->name('switch');
    Route::get('/current', [LanguageController::class, 'getCurrentLanguage'])->name('current');
    Route::get('/translations', [LanguageController::class, 'getTranslations'])->name('translations');
});

Route::get('/about', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Login routes (accessible for guests only)
    Route::middleware(['guest', 'redirect.if.admin'])->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });

    // Logout route (accessible for authenticated users only)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
        // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/stats', [DashboardController::class, 'apiStats'])->name('api.stats');

    // Users Management
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

    // Language switching
    Route::post('/language/switch', [\App\Http\Controllers\Admin\LanguageController::class, 'switch'])->name('language.switch');
    Route::get('/language/switch/{locale}', [\App\Http\Controllers\Admin\LanguageController::class, 'switchLanguage'])->name('language.switch.get');
    Route::get('/language/{locale}', [\App\Http\Controllers\Admin\LanguageController::class, 'switchLanguage'])->name('admin.language.switch');

    // Theme switching
    Route::post('/theme/toggle', [\App\Http\Controllers\Admin\ThemeController::class, 'toggle'])->name('theme.toggle');
    Route::get('/theme/current', [\App\Http\Controllers\Admin\ThemeController::class, 'getCurrent'])->name('theme.current');

    // User Management
    Route::middleware('admin:users.view')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    });

    // Category Management
    Route::middleware('admin:categories.view')->group(function () {
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        Route::post('categories/{category}/upload-image', [\App\Http\Controllers\Admin\CategoryController::class, 'uploadImage'])->name('categories.upload-image');
    });

    // Product Management
    Route::middleware('admin:products.view')->group(function () {
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
        Route::post('products/{product}/upload-images', [\App\Http\Controllers\Admin\ProductController::class, 'uploadImages'])->name('products.upload-images');
        Route::get('products/low-stock', [\App\Http\Controllers\Admin\ProductController::class, 'lowStock'])->name('products.low-stock');
    });

    // Order Management
    Route::middleware('admin:orders.view')->group(function () {
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'edit', 'update']);
        Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    });

    // Language Management
    Route::middleware('admin:languages.view')->group(function () {
        Route::resource('languages', \App\Http\Controllers\Admin\LanguageController::class);
    });

    // Translation Management
    Route::middleware('admin:translations.view')->group(function () {
        Route::resource('translations', \App\Http\Controllers\Admin\TranslationController::class);
        Route::post('translations/bulk-update', [\App\Http\Controllers\Admin\TranslationController::class, 'bulkUpdate'])->name('translations.bulk-update');
    });

    // Reports
    Route::middleware('admin:reports.view')->group(function () {
        Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/sales', [\App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('reports.sales');
        Route::get('reports/products', [\App\Http\Controllers\Admin\ReportController::class, 'products'])->name('reports.products');
        Route::get('reports/users', [\App\Http\Controllers\Admin\ReportController::class, 'users'])->name('reports.users');
    });

    // Settings (Admin only)
    Route::middleware('admin:*')->group(function () {
        Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    });

    // Notifications
    Route::get('notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('notifications/{notification}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.read');
});

/*
|--------------------------------------------------------------------------
| API Documentation Routes
|--------------------------------------------------------------------------
*/
Route::prefix('docs')->name('docs.')->group(function () {
    Route::get('/', [DocsController::class, 'index'])->name('index');
    Route::get('/getting-started', [DocsController::class, 'gettingStarted'])->name('getting-started');
    Route::get('/authentication', [DocsController::class, 'authentication'])->name('authentication');
    Route::get('/endpoints/{section}', [DocsController::class, 'endpoints'])->name('endpoints');
    Route::get('/api-tester', [DocsController::class, 'apiTester'])->name('api-tester');
});
