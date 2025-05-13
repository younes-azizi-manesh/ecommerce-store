<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Web\Auth\AuthRequest;
use App\Http\Requests\Web\Auth\ConfirmOtpRequest;
use App\Services\Auth\Otp\OtpService;
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
    public function loginConfirm($token, ConfirmOtpRequest $request, OtpService $otpService)
    {
        
        $validated = $request->all(); 
        $result = $otpService->updateAndLogin($token, $validated['otp']);

        if($result['success'] === false && $result['message'] === 'url is invalid')
        {
            return $this->customRedirect('auth.login-register-form', 'swal-error', $result['message']);
        }
        if($result['success'] === false && $result['message'] === 'otp is invalid')
        {
            return $this->customRedirect('auth.login-confirm-form', 'swal-error',$result['message'] , ['token' => $token]);
        }

        return $this->customRedirect('home', 'swal-success', $result['message']);
    }
    public function loginResendOtp($token, OtpService $otpService)
    {

        $result = $otpService->reSendOtp($token);
        if($result['success'] === false)
        {
            return $this->customRedirect('auth.login-register-form', 'swal-error', $result['message']);
        }
       
        
        return redirect()->route('auth.login-confirm', $token);
    }


    public function logout()
    {
        Auth::logout();
        return $this->customRedirect('home');
    }

}
