<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\ShoppingCartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// AUTH ROUTES - Public Access with stricter rate limiting
Route::prefix('auth')->middleware('rate_limit:5,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Auth required routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    });
});

// USER PROFILE ROUTES - Authenticated Users
Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::put('/location', [UserController::class, 'updateLocation']);
    Route::put('/password', [UserController::class, 'changePassword']);
    Route::delete('/account', [UserController::class, 'deleteAccount']);

    // Avatar management
    Route::post('/avatar', [UserController::class, 'uploadAvatar']);
    Route::delete('/avatar', [UserController::class, 'deleteAvatar']);
});

// LEGACY - Eski route (backward compatibility)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// PUBLIC API ROUTES - Rate limited
Route::prefix('v1')->middleware('rate_limit:60,1')->group(function () {
    // Tillar va tarjimalar (public access)
    Route::get('/languages', [LanguageController::class, 'index']);
    Route::get('/languages/default', [LanguageController::class, 'getDefault']);
    Route::get('/languages/{id}', [LanguageController::class, 'show']);

    // CATEGORIES (public access)
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/tree', [CategoryController::class, 'tree']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::get('/slug/{slug}', [CategoryController::class, 'showBySlug']);
        Route::get('/{id}/breadcrumbs', [CategoryController::class, 'breadcrumbs']);
    });

    // PRODUCTS (public access)
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/featured', [ProductController::class, 'featured']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::get('/slug/{slug}', [ProductController::class, 'showBySlug']);
        Route::get('/{id}/related', [ProductController::class, 'related']);
        Route::get('/category/{categoryId}', [ProductController::class, 'byCategory']);
    });

    // NEWSLETTER (public access)
    Route::prefix('newsletter')->group(function () {
        Route::post('/subscribe', [App\Http\Controllers\Api\NewsletterController::class, 'subscribe']);
        Route::post('/unsubscribe', [App\Http\Controllers\Api\NewsletterController::class, 'unsubscribe']);
        Route::post('/status', [App\Http\Controllers\Api\NewsletterController::class, 'status']);
    });
});

// ADMIN API'LAR - Role-based Access
Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin,manager'])->group(function () {
    // Tillarni boshqarish (CRUD)
    Route::apiResource('languages', LanguageController::class)->except(['index', 'show']);

    // USER MANAGEMENT - Admin/Manager only
    Route::prefix('users')->group(function () {
        Route::get('/', [AdminUserController::class, 'index']);
        Route::get('/statistics', [AdminUserController::class, 'statistics']);
        Route::get('/{id}', [AdminUserController::class, 'show']);
        Route::put('/{id}', [AdminUserController::class, 'update']);
        Route::delete('/{id}', [AdminUserController::class, 'destroy'])->middleware('role:admin'); // Only admin can delete
    });

    // CATEGORY MANAGEMENT - Admin/Manager only
    Route::prefix('categories')->group(function () {
        Route::get('/', [AdminCategoryController::class, 'index']);
        Route::post('/', [AdminCategoryController::class, 'store']);
        Route::get('/statistics', [AdminCategoryController::class, 'statistics']);
        Route::get('/{id}', [AdminCategoryController::class, 'show']);
        Route::put('/{id}', [AdminCategoryController::class, 'update']);
        Route::delete('/{id}', [AdminCategoryController::class, 'destroy']);

        // Category image management
        Route::post('/{id}/image', [AdminCategoryController::class, 'uploadImage']);
        Route::delete('/{id}/image', [AdminCategoryController::class, 'deleteImage']);
    });

    // PRODUCT MANAGEMENT - Admin/Manager/Employee
    Route::prefix('products')->group(function () {
        Route::get('/', [AdminProductController::class, 'index']);
        Route::post('/', [AdminProductController::class, 'store']);
        Route::get('/statistics', [AdminProductController::class, 'statistics']);
        Route::get('/low-stock', [AdminProductController::class, 'lowStock']);
        Route::get('/analytics', [AdminProductController::class, 'analytics']);
        Route::get('/{id}', [AdminProductController::class, 'show']);
        Route::put('/{id}', [AdminProductController::class, 'update']);
        Route::delete('/{id}', [AdminProductController::class, 'destroy']);

        // Stock management
        Route::put('/{id}/stock', [AdminProductController::class, 'updateStock']);
        Route::post('/bulk-stock', [AdminProductController::class, 'bulkUpdateStock']);

        // Product image management
        Route::post('/{id}/images', [AdminProductController::class, 'uploadImage']);
        Route::delete('/{id}/images/{imageId}', [AdminProductController::class, 'deleteImage']);
        Route::put('/{id}/images/{imageId}/primary', [AdminProductController::class, 'setPrimaryImage']);
    });

    // ORDER MANAGEMENT - Admin/Manager only
    Route::prefix('orders')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Admin\OrderController::class, 'index']);
        Route::get('/statistics', [\App\Http\Controllers\Api\Admin\OrderController::class, 'statistics']);
        Route::get('/{id}', [\App\Http\Controllers\Api\Admin\OrderController::class, 'show']);
        Route::put('/{id}/status', [\App\Http\Controllers\Api\Admin\OrderController::class, 'updateStatus']);
    });
});

// SHOPPING CART ROUTES - Public & Authenticated
Route::prefix('cart')->group(function () {
    Route::get('/', [ShoppingCartController::class, 'index']);
    Route::post('/add', [ShoppingCartController::class, 'addItem']);
    Route::put('/items/{itemId}', [ShoppingCartController::class, 'updateItem']);
    Route::delete('/items/{itemId}', [ShoppingCartController::class, 'removeItem']);
    Route::delete('/clear', [ShoppingCartController::class, 'clear']);
    Route::post('/coupon/apply', [ShoppingCartController::class, 'applyCoupon']);
    Route::delete('/coupon', [ShoppingCartController::class, 'removeCoupon']);
    Route::get('/summary', [ShoppingCartController::class, 'summary']);
});

// ORDER ROUTES - Authenticated Users
Route::prefix('orders')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'store']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::post('/{id}/cancel', [OrderController::class, 'cancel']);
    Route::get('/{id}/status-history', [OrderController::class, 'statusHistory']);
});

// ADDRESS ROUTES - Authenticated Users
Route::prefix('addresses')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [AddressController::class, 'index']);
    Route::post('/', [AddressController::class, 'store']);
    Route::get('/{id}', [AddressController::class, 'show']);
    Route::put('/{id}', [AddressController::class, 'update']);
    Route::delete('/{id}', [AddressController::class, 'destroy']);
    Route::post('/{id}/set-default', [AddressController::class, 'setDefault']);
    Route::post('/{id}/delivery-fee', [AddressController::class, 'calculateDeliveryFee']);
});

// PAYMENT ROUTES - Authenticated Users
Route::prefix('payments')->middleware('auth:sanctum')->group(function () {
    Route::get('/methods', [PaymentController::class, 'getPaymentMethods']);
    Route::post('/process', [PaymentController::class, 'processPayment']);
    Route::get('/history', [PaymentController::class, 'getPaymentHistory']);
    Route::get('/{id}/status', [PaymentController::class, 'checkPaymentStatus']);
    Route::post('/{id}/confirm', [PaymentController::class, 'confirmPayment']);
});

// NOTIFICATION ROUTES - Authenticated Users
Route::prefix('notifications')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/{id}/mark-read', [NotificationController::class, 'markAsRead']);
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/{id}', [NotificationController::class, 'destroy']);
    Route::post('/test', [NotificationController::class, 'sendTest']); // Test uchun
});

// TEST ROUTE - Commented out as TestController doesn't exist
// Route::get('/test-order', [\App\Http\Controllers\TestController::class, 'testOrder']);
