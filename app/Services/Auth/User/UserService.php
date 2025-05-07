<?php

namespace App\Services\Auth\User;

use App\Models\User;
use App\Repositories\UserRepository;
class UserService
{
    public function __construct(private UserRepository $userRepo) {}

    public function findOrCreateByMobile(string $mobile): User
    {
        $user = $this->userRepo->findBy('mobile', $mobile);

        if (!$user) {
            $user = $this->userRepo->create([
                'mobile' => $mobile,
                'activation' => 1,
            ]);
        }

        return $user;
    }

    public function markMobileVerified(User $user): void
    {
        if (empty($user->mobile_verified_at)) {
            $user->update([
                'mobile_verified_at' => now(),
            ]);
        }
    }
}