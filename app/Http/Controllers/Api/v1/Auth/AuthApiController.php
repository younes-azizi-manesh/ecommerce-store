<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\AuthRequest;
use App\Http\Requests\Api\Auth\ConfirmOtpRequest;
use App\Http\Resources\Api\AuthUserResource;
use App\Services\Auth\Otp\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AuthApiController extends Controller
{
    public function loginRegister(AuthRequest $request, OtpService $otpService)
    {
        $validated = $request->validated();
        $token = $otpService->sendOtpCode($validated['id']); 

        return Response::jsonResponse(['otpToken' => $token], 'send otp code successful', 200);
    }

    public function loginConfirm(ConfirmOtpRequest $request, OtpService $otpService)
    {
        $validated = $request->validated();
        $result = $otpService->loginViaApi($validated['otp_token'], $validated['otp_code']);

        if($result['success'] === false && $result['message'] == 'url is invalid')
        {
            return Response::jsonResponse(null, $result['message'], 400);
        }
        if($result['success'] === false && $result['message'] == 'otp is invalid')
        {
            return Response::jsonResponse(null, $result['message'], 422);
        }
        return Response::jsonResponse(['user' => new AuthUserResource($result['user']), 'token' => $result['accessToken']], $result['message'], 200);
    }

    public function loginResendOtp(OtpService $otpService, Request $request)
    {
        $otpToken = $request->validate([
            'otp_token' => ['required', 'string', 'size:60']
        ]);
        $result = $otpService->reSendOtp($otpToken['otp_token']);
        if($result['success'] === false && $result['message'] == 'url is invalid')
        {
            return Response::jsonResponse(null, $result['message'], 400);
        }
        return Response::jsonResponse($otpToken, $result['message'], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return Response::jsonResponse(null, 'you are logged out', 204);
    }
}
