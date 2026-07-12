<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Post\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    // Authentication Routes
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::post('login', [AuthController::class, 'login'])->name('login');
    });

    // Protected Routes
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        // Posts

        Route::prefix('posts')->group(function () {
            Route::get('/', [PostController::class, 'index'])->name('index');
            Route::post('store', [PostController::class, 'store'])->name('store');
            Route::put('{id}', [PostController::class, 'update'])->name('update');
            Route::delete('{id}', [PostController::class, 'delete'])->name('destroy');
        });
    });
});
