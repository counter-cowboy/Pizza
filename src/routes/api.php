<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::middleware('api')
    ->prefix('auth')
    ->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('me', [AuthController::class, 'me']);
    });

Route::post('login', [AuthController::class, 'login']);

Route::post('register', [RegisterController::class, 'register']);

Route::apiResource('products', ProductController::class)
    ->only(['index', 'show']);
Route::post('carts', [CartController::class, 'store']);


Route::middleware('auth:api')
    ->group(function () {
        Route::apiResource('products', ProductController::class)
            ->except(['index', 'show']);
        Route::apiResource('carts', CartController::class)
        ->except('store');

        Route::apiResources([
            'orders' => OrderController::class,
            'categories' => CategoryController::class
        ]);
    });




