<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductAdminController;
use \App\Http\Controllers\LoginController;

Route::get('/', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/login', [LoginController::class, 'loginPage'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/products', [ProductAdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/products/add', [ProductAdminController::class, 'addProductForm'])->name('admin.add.product');
    Route::post('/admin/products/add', [ProductAdminController::class, 'addProduct'])->name('admin.add.product.submit');
    Route::get('/admin/products/edit/{id}', [ProductAdminController::class, 'editProduct'])->name('admin.edit.product');
    Route::post('/admin/products/edit/{id}', [ProductAdminController::class, 'updateProduct'])->name('admin.update.product');
    Route::get('/admin/products/delete/{id}', [ProductAdminController::class, 'deleteProduct'])->name('admin.delete.product');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});
