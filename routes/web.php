<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::controller(PageController::class)->group(function () {
    Route::get('/menu', 'menu')->name('menu.index');
    Route::get('/menu/{slug}', 'menuDetail')->name('menu.show');
    Route::get('/products', 'products')->name('products.index');
    Route::get('/products/{slug}', 'productDetail')->name('products.show');
    Route::get('/produk', 'products')->name('products.legacy');
    Route::get('/jelajahi', 'explore')->name('explore');
});

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/items', [CartController::class, 'store'])->name('cart.items.store');
Route::patch('/cart/items/{key}', [CartController::class, 'update'])->name('cart.items.update');
Route::delete('/cart/items/{key}', [CartController::class, 'destroy'])->name('cart.items.destroy');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'create'])->name('login');
        Route::post('/login', [AuthController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::resource('categories', AdminCategoryController::class)->except(['show']);
        Route::resource('products', AdminProductController::class)->except(['show']);
    });
});
