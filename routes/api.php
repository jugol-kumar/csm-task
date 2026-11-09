<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OwnerController;
use App\Http\Controllers\Admin\ProductController;
 use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::prefix('storefront')->group(function () {
    Route::get('shops', [StorefrontController::class, 'shops']);
    Route::get('products', [StorefrontController::class, 'latest']);
    Route::get('shops/{shop:slug}', [StorefrontController::class, 'shop']);
    Route::get('shops/{shop:slug}/products', [StorefrontController::class, 'products']);
    Route::get('shops/{shop:slug}/products/{listing}', [StorefrontController::class, 'product']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('admin')->name('admin.')->middleware('checkRole:admin')->group(function () {
        Route::get('dashboard', DashboardController::class);
        Route::apiResource('stores', StoreController::class);
        Route::apiResource('products', ProductController::class);
        Route::apiResource('owners', OwnerController::class);
    });
});
