<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes - No authentication required
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Public product viewing (catalog)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{identifier}', [ProductController::class, 'show']);

// Protected routes - JWT authentication required
Route::middleware(['auth:api'])->group(function () {
    
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    // Order management - Regular user and admin orders
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);      // Users: own orders, Admin: all orders
        Route::post('/', [OrderController::class, 'store']);    // Users: create own orders
        Route::get('/{uuid}', [OrderController::class, 'show']); // Users: own orders only
        Route::put('/{uuid}', [OrderController::class, 'update']); // Admin only
        Route::delete('/{uuid}', [OrderController::class, 'destroy']); // Admin only
    });
});

// Admin-only routes
Route::middleware(['auth:api', 'admin'])->group(function () {
    // Product management - CRUD operations (Admin only)
    Route::prefix('products')->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{identifier}', [ProductController::class, 'update']);
        Route::delete('/{identifier}', [ProductController::class, 'destroy']);
    });
});
