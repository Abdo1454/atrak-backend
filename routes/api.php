<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserDashboardController;

// Admin Controllers
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\MessageController;
use App\Http\Controllers\Api\Admin\SettingsController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

// Orders
Route::post('/orders', [OrderController::class, 'store']);

// Contact
Route::post('/contact', [ContactController::class, 'store']);

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Dashboard
    Route::get('/user/dashboard', [UserDashboardController::class, 'index']);

    // Profile
    Route::get('/user/profile', [UserDashboardController::class, 'profile']);
    Route::put('/user/profile', [UserDashboardController::class, 'updateProfile']);

    // Password
    Route::put('/user/change-password', [UserDashboardController::class, 'changePassword']);

    // Orders
    Route::get('/user/orders', [UserDashboardController::class, 'orders']);

    // Favorites
    Route::get('/user/favorites', [UserDashboardController::class, 'favorites']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware('auth:sanctum')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', DashboardController::class);

        // Products
        Route::apiResource('products', AdminProductController::class);

        // Categories
        Route::apiResource('categories', AdminCategoryController::class);

        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index']);
        Route::put('/orders/{order}', [AdminOrderController::class, 'update']);

        // Customers
        Route::get('/users', [UserController::class, 'index']);

        // Messages
        Route::get('/messages', [MessageController::class, 'index']);
        Route::delete('/messages/{message}', [MessageController::class, 'destroy']);

        // Settings
        Route::get('/settings', [SettingsController::class, 'show']);
        Route::put('/settings', [SettingsController::class, 'update']);
    });