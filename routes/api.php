<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\AuthController;


Route::get('/products', [ProductController::class, 'index']);

Route::get('/products/{product}', [ProductController::class, 'show']);


Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/categories/{category}', [CategoryController::class, 'show']);


Route::post('/orders', [OrderController::class, 'store']);

Route::post('/contact', [ContactController::class, 'store']);


// Authentication
Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);