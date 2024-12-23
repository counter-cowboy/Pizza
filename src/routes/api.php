<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->prefix('auth')
    ->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
        Route::post('me', [AuthController::class, 'me'])->name('me');
    });

Route::get('/', [HomeController::class, 'index']);

Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])
    ->middleware('auth:api')->name('orders.cancel');

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::post('register', [RegisterController::class, 'register'])->name('register');

Route::apiResource('products', ProductController::class)
    ->only(['index', 'show']);
Route::apiResource('categories', CategoryController::class)
    ->only(['index', 'show']);

Route::post('carts', [CartController::class, 'store'])->name('carts.store');

Route::get('users', [UserController::class, 'index'])->name('users.index');

Route::middleware('auth:api')
    ->group(function () {
        Route::apiResource('products', ProductController::class)
            ->except(['index', 'show']);

        Route::patch('orders/{order}/cancel', [OrderController::class, 'cancel'])
            ->name('order.cancel');

        Route::apiResource('carts', CartController::class)
            ->except('store');

        Route::apiResource('orders', OrderController::class)
            ->except('destroy');

        Route::apiResource('categories', CategoryController::class)
            ->except('index', 'show');
    });
