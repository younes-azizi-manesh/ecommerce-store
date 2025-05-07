<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomRedirectException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Requests\Auth\ConfirmRequest;
use App\Services\Auth\OtpService;
use Exception;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function loginRegisterForm()
    {
        return view('auth.login-register');
    }

    public function loginRegister(AuthRequest $request, OtpService $otpService)
    {
        $validated = $request->validated();
        $token = $otpService->sendOtpCode($validated['id']);
        return $this->customRedirect('auth.login-confirm', 'swal-success', 'کد تایید باموفقیت برای شما ارسال شد', ['token' => $token]);
    }

    public function loginConfirmForm(OtpService $otpService, $token)
    {
        $otp = $otpService->find($token);
        if (empty($otp)) {
            return $this->customRedirect('auth.login-register-form', 'swal-error', 'ادرس وارد شده نامعتبر است');
        }
        return view('auth.login-confirm', compact('token', 'otp'));
    }
    public function loginConfirm($token, ConfirmRequest $request, OtpService $otpService)
    {
        try
        {
            $validated = $request->all(); 
            $otpService->updateAndLogin($token, $validated['otp']);
            return redirect()->route('home');
        }catch(CustomRedirectException $e)
        {
            if(in_array('codeNotMatch', $e->getExtraData()))
            {
                return $this->customRedirect('auth.login-confirm-form', 'swal-error', 'کد وارد شده نامعتبر است', ['token' => $token]);
            }
            return $this->customRedirect('auth.login-register-form', 'swal-error', 'ادرس وارد شده نامعتبر است');
        }

    }
    public function loginResendOtp($token, OtpService $otpService)
    {
        try
        {
            $otpService->reSendOtp($token);
            return redirect()->route('auth.login-confirm', $token);
        }catch(CustomRedirectException $e)
        {
            return $this->customRedirect('auth.login-register-form', 'swal-error', 'ادرس وارد شده نامعتبر است');
        }
    }


    public function logout()
    {
        Auth::logout();
        return $this->customRedirect('home');
    }

}
