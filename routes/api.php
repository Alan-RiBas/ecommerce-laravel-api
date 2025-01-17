<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
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
    });
});
