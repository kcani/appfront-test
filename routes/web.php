<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductAdminController;
use \App\Http\Controllers\LoginController;

/**
 * Guest products routes.
 */
Route::get('/', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

/**
 * Login routes.
 */
Route::get('/login', [LoginController::class, 'loginPage'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::middleware(['auth'])->group(function () {
    /**
     * Admin product routes.
     */
    Route::resource('admin/products', ProductAdminController::class)
        ->names('admin.products')
        ->except('show');

    /**
     * Logout route.
     */
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});
