<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
 use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\CatalogController as OwnerCatalogController;
use App\Http\Controllers\Owner\ListingController as OwnerListingController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::prefix('storefront')->group(function () {
    Route::get('shops', [StorefrontController::class, 'shops']);
    Route::get('products', [StorefrontController::class, 'latest']);
    Route::get('shops/{store:slug}', [StorefrontController::class, 'shop']);
    Route::get('shops/{store:slug}/products', [StorefrontController::class, 'products']);
    Route::get('shops/{store:slug}/products/{listing}', [StorefrontController::class, 'product']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('admin')->name('admin.')->middleware('checkRole:admin')->group(function () {
        Route::get('dashboard', DashboardController::class);
        Route::apiResource('stores', StoreController::class);
        Route::apiResource('products', ProductController::class);
        Route::apiResource('users', UserController::class);
    });

    Route::prefix('owner')->name('owner.')->middleware('checkRole:owner')->group(function () {
        Route::get('dashboard', OwnerDashboardController::class);
        Route::get('catalog', [OwnerCatalogController::class, 'index']);
        Route::post('catalog', [OwnerCatalogController::class, 'store']);
        Route::put('catalog/{product}', [OwnerCatalogController::class, 'update']);
        Route::delete('catalog/{product}', [OwnerCatalogController::class, 'destroy']);
        Route::apiResource('listings', OwnerListingController::class)->except('show');
    });
});
