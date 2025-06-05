<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\Admin\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/auth',function(){
echo"tersambung";
});
//login dan register,logout
Route::POST('/auth/registrasi', [AuthController::class, 'register']);
Route::POST('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout']);
//Lupa password
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPasswordVerifyEmail']);
Route::get('/auth/verify-email/{token}/{email}', [AuthController::class, 'RedirectToResetPaswword']);
Route::post('/auth/reset-password', [AuthController::class, 'resetpassword']);



//crud product
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
