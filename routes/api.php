<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=> 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware(['jwt'])->group(function () {
        Route::get('/me', [AuthController::class, 'getUser']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/users/{userId}', [AuthController::class, 'getUserById']);
        Route::patch('/users/{userId}', [AuthController::class, 'updateUserRole']);
        Route::patch('/edit-profile', [AuthController::class, 'updateUserProfile']);
        Route::get('/users', [AuthController::class, 'showAll']);
    });
});

Route::group(['prefix' => 'products'], function () {
    Route::get('/', [ProductController::class, 'showAll']);
    Route::get('/{productId}', [ProductController::class, 'show']);
    Route::post('/create-product', [ProductController::class, 'store']);
    Route::patch('/update-product/{productId}', [ProductController::class, 'update']);
    Route::delete('/delete-product/{productId}', [ProductController::class, 'destroy']);
    Route::get('/related/{productId}', [ProductController::class, 'related']);
});
