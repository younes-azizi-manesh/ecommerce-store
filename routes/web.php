<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::middleware(['throttle', 'guest'])->group(function () {
    Route::get('login-register', [AuthController::class, 'loginRegisterForm'])->name('auth.login-register-form');
    Route::post('/login-register', [AuthController::class, 'loginRegister'])->name('auth.login-register');
    Route::get('login-confirm/{token}', [AuthController::class, 'loginConfirmForm'])->name('auth.login-confirm-form');
    Route::post('/login-confirm/{token}', [AuthController::class, 'loginConfirm'])->name('auth.login-confirm');
    Route::get('/login-resend-otp/{token}', [AuthController::class, 'loginResendOtp'])->name('auth.login-resend-otp');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth')->withoutMiddleware('guest');
});
