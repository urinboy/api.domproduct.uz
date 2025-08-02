<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocsController;

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

Route::get('/about', function () {
    return view('welcome');
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
