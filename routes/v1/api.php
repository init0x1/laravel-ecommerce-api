<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\StockController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::name('auth.')
    ->prefix('auth')
    ->group(function () {
        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::post('login', [AuthController::class, 'login'])->name('login');
    });

// Category Routes
Route::name('categories.')
    ->prefix('categories')
    ->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/{id}', [CategoryController::class, 'show'])->name('show');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        });
    });

// Product Routes
Route::name('products.')
    ->prefix('products')
    ->group(function () {

        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/{id}', [ProductController::class, 'show'])->name('show');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
        });
    });

// Stock Routes
Route::name('stocks.')
    ->prefix('stocks')
    ->group(function () {

        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::get('/{id}', [StockController::class, 'show'])->name('show');

        Route::middleware('auth:sanctum')->group(function () {
            Route::put('/{stock}', [StockController::class, 'update'])->name('update');
        });
    });

// Order Routes
Route::name('orders.')
    ->prefix('orders')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::put('/{order}', [OrderController::class, 'update'])->name('update');
    });

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
