<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::prefix('password')->group(function () {
        Route::post('email', 'sendResetLinkEmail');
        Route::post('reset', 'resetPassword');
    });
    Route::post('/user', 'user')->middleware('auth:sanctum');
});

Route::controller(UserController::class)->prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'updateProfile');
    Route::post('/password', 'updatePassword');
});
