<?php

use App\Http\Controllers\category;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});


Route::resource('/product', ProductController::class);
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');


Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');




Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
// Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::resource('categories', category::class);
