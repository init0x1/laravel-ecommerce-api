<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\StockController;

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
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{id}', [CategoryController::class, 'show'])->name('show');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
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


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
