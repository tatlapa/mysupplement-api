<?php

use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\AdvicerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    
    Route::prefix('password')->group(function () {
        Route::post('email', 'sendResetLinkEmail');
        Route::post('reset', 'resetPassword');
    });

    Route::post('/user', 'user')->middleware('auth:sanctum');

    // Social Login Routes (with middleware web)
    Route::middleware(['web'])->group(function () {
        Route::get('/redirect/{provider}', 'redirectToProvider');
        Route::get('/callback/{provider}', 'handleProviderCallback');
    });
});

Route::controller(UserController::class)->prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'updateProfile');
    Route::post('/password', 'updatePassword');
});

Route::controller(ShopController::class)->prefix('shop')->group(function () {
    Route::get('products', 'getProducts');
    Route::get('products/{product}', 'getProduct');
    Route::get('categories', 'getCategories');
});

Route::controller(CartController::class)->prefix('cart')->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'getCart');
    Route::post('/add', 'addToCart');
    Route::delete('/delete/{item}', 'removeFromCart');
    Route::patch('/update', 'updateCart');
});

Route::controller(AdminController::class)->prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    // Products
    Route::get('products','getProducts');
    Route::post('products','storeProduct');
    Route::post('products/{product}','updateProduct');
    Route::delete('products/{product}','deleteProduct');

    // Catégories
    Route::get('categories', 'getCategories');
    // Route::post('categories', 'storeCategory');
    // Route::delete('categories/{category}', 'deleteCategory');
});

Route::post('/getSupplementRecommendations', [AdvicerController::class, 'getSupplementRecommendations']);
