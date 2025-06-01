<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//login dan register,logout
Route::POST('/uth/registrasi', [AuthController::class, 'register']);
Route::POST('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout']);
//Lupa password
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPasswordVerifyEmail']);
Route::get('/auth/verify-email/{token}/{email}', [AuthController::class, 'RedirectToResetPaswword']);
Route::post('/auth/reset-password', [AuthController::class, 'resetpassword']);



//crud product
Route::apiResource('/product', ProductController::class)->middleware('auth:sanctum');
