<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\AuthRequest;
use App\Http\Requests\Api\Auth\ConfirmOtpRequest;
use App\Services\Auth\Otp\OtpService;

class AuthApiController extends Controller
{
    public function loginRegister(AuthRequest $request, OtpService $otpService)
    {

    }

    public function loginConfirm($token, ConfirmOtpRequest $request, OtpService $otpService)
    {

    }

    public function loginResendOtp($token, OtpService $otpService)
    {

    }

    public function logout()
    {

    }
}
