<?php

namespace App\Repositories;

use App\Models\Auth\Otp;

class OtpRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Otp());
    }
}