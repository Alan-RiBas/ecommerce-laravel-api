<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=> 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::middleware(['jwt'])->group(function () {
        Route::get('/me', [AuthController::class, 'getUser'])->name('auth.me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('/users/{userId}', [AuthController::class, 'getUserById'])->name('auth.user');
        Route::patch('/users/{userId}', [AuthController::class, 'updateUserRole'])->name('auth.updateRole');
        Route::patch('/edit-profile', [AuthController::class, 'updateUserProfile'])->name('auth.updateProfile');
        Route::get('/users', [AuthController::class, 'showAll'])->name('auth.showAll');
    });
});

Route::group(['prefix' => 'products'], function () {
    Route::get('/', [ProductController::class, 'showAll'])->name('products.showAll');
    Route::get('/{productId}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/related/{productId}', [ProductController::class, 'related'])->name('products.related');
    Route::middleware(['jwt','verifyAdmin'])->group(function () {
        Route::post('/create-product', [ProductController::class, 'store'])->name('products.store');
        Route::patch('/update-product/{productId}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/delete-product/{productId}', [ProductController::class, 'destroy'])->name('products.destroy');
    });
});
Route::post('/post-review', [ReviewController::class, 'store'])->name('reviews.store');

Route::group(['prefix' => 'reviews'], function() {
    Route::get('/show-review', [ReviewController::class, 'showAll']);
    Route::get('/{reviewId}', [ReviewController::class, 'show']);
    Route::middleware(['jwt'])->group(function () {
        Route::patch('/update-review/{reviewId}', [ReviewController::class, 'update']);
        Route::delete('/delete-review/{reviewId}', [ReviewController::class, 'destroy']);
    });
});
