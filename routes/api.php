<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/menu/categories', [CategoryController::class, 'menu'])->name('menu.categories.index');
    Route::get('/products/categories', [CategoryController::class, 'products'])->name('products.categories.index');
    Route::get('/menu/filters', [CategoryController::class, 'menuFilters'])->name('menu.filters');
    Route::get('/products/filters', [CategoryController::class, 'productFilters'])->name('products.filters');
    Route::get('/menu', [ProductController::class, 'menu'])->name('menu.index');
    Route::get('/products', [ProductController::class, 'products'])->name('products.index');
});
