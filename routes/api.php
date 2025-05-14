<?php

use App\Http\Controllers\Api\v1\Auth\AuthApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::middleware(['throttle', 'guest:sanctum'])->group(function () {
    Route::post('/login-register', [AuthApiController::class, 'loginRegister']);
    Route::post('/login-confirm', [AuthApiController::class, 'loginConfirm']);
    Route::post('/login-resend-otp', [AuthApiController::class, 'loginResendOtp']);
    Route::post('/logout', [AuthApiController::class, 'logout'])->middleware('auth:sanctum')->withoutMiddleware('guest:sanctum');
});
