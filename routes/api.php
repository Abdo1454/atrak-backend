<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\AuthController;


// Admin Controllers
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\MessageController;
use App\Http\Controllers\Api\Admin\SettingsController;



// Public Products
Route::get('/products', [ProductController::class, 'index']);

Route::get('/products/{product}', [ProductController::class, 'show']);


// Public Categories
Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/categories/{category}', [CategoryController::class, 'show']);


// Orders
Route::post('/orders', [OrderController::class, 'store']);


// Contact
Route::post('/contact', [ContactController::class, 'store']);


// Authentication
Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);



// ======================
// Admin API
// ======================

Route::prefix('admin')
    ->middleware('auth:sanctum')
    ->group(function () {


        // Dashboard
        Route::get(
            '/dashboard',
            DashboardController::class
        );


        // Products CRUD
        Route::apiResource(
            'products',
            AdminProductController::class
        );


        // Categories CRUD
        Route::apiResource(
            'categories',
            AdminCategoryController::class
        );


        // Orders
        Route::get(
            '/orders',
            [AdminOrderController::class, 'index']
        );

        Route::put(
            '/orders/{order}',
            [AdminOrderController::class, 'update']
        );


        // Customers
        Route::get(
            '/users',
            [UserController::class, 'index']
        );


        // Messages
        Route::get(
            '/messages',
            [MessageController::class, 'index']
        );

        Route::delete(
            '/messages/{message}',
            [MessageController::class, 'destroy']
        );


        // Settings
        Route::get(
            '/settings',
            [SettingsController::class, 'show']
        );

        Route::put(
            '/settings',
            [SettingsController::class, 'update']
        );

    });