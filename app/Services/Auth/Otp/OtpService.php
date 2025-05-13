<?php

namespace App\Services\Auth\Otp;

use App\Http\Interfaces\NotificationServiceInterface;
use App\Models\User;
use App\Repositories\OtpRepository;
use App\Services\Auth\User\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OtpService
{
    public function __construct(
        private OtpRepository $otpRepo,
        private UserService $userService,
        private NotificationServiceInterface $notificationService
    ) {}

    public function sendOtpCode($loginId)
    {
        $check = $this->checkLoginId($loginId);
        $user = $this->userService->findOrCreateByMobile($check['login_id']);
        $otp = $this->makeOtp($user, $check);

        $this->notificationService
            ->setText([$otp['otp_code']])
            ->setTo($user->mobile)
            ->setBodyId(314163)
            ->send();

        return $otp['token'];
    }

    public function reSendOtp($token)
    {
        $otp = $this->findWhere([
            'token' => $token,
            'used' => 0,
            'created_at' => ['>=', $this->getMinutes(5)]
        ],[], ['*']);

        if (empty($otp)) {
            return ['success' => false, 'message' => 'url is invalid'];
        }

        $user = $otp->user;

        $this->notificationService
            ->setText([$otp->otp_code])
            ->setTo($user->mobile)
            ->setBodyId(314163)
            ->send();
    }
    public function getMinutes(?int $minutes)
    {   // set minute return subMinutes, otherwise return now.
        return isset($minutes) ? Carbon::now()->subMinutes($minutes)->toDateTimeString() : Carbon::now();
    }

    public function updateAndLogin($token, $otpCode)
    {
        $otp = $this->findWhere(['token' => $token, 'used' => 0, 'created_at' => ['>=', $this->getMinutes(5)]], [], ['*']);
        if (empty($otp)) {
            return ['success' => false, 'message' => 'url is invalid'];
        }
        if ($otp->otp_code !== $otpCode) {
            return ['success' => false, 'message' => 'otp is invalid'];
        }
        $this->otpRepo->update($otp->id, ['used' => 1]);
        $user = $otp->user;
        if ($otp->type == 0) {
            $this->userService->markMobileVerified($user);
        }
        Auth::login($user);
        return ['success' => true, 'message' => 'you are logged in'];
    }

    public function makeOtp(User $user, array $check)
    {
        //create otp code
        $otpCode = rand(111111, 999999);
        $token = Str::random(60);

        $otpInputs = [
            'token' => $token,
            'otp_code' => $otpCode,
            'user_id' => $user->id,
            'login_id' => $check['login_id'],
            'type' => $check['type'],
        ];
        $this->otpRepo->create($otpInputs);
        return $otpInputs;
    }

    public function checkLoginId($loginId)
    {
        if(preg_match('/^(\+98|98|0)9\d{9}$/', $loginId)) {
            // 0 =>mobile
            //check mobile format
            $loginId = ltrim($loginId, 0);
            $loginId = substr($loginId, 0, 2) === '98' ? substr($loginId, 2) : $loginId;
            $loginId = str_replace('98', '', $loginId);
            $result = ['type' => 0, 'login_id' => $loginId];
        }
        return $result;
    }

    public function find($token)
    {
        return $this->otpRepo->findBy('token', $token);
    }

    public function findWhere(array $conditions, array $with = [], $columns)
    {
        return $this->otpRepo->findWhere($conditions, $with, $columns);
    }
}
