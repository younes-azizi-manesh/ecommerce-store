<?php

namespace App\Providers;

use App\Http\Interfaces\NotificationServiceInterface;
use App\Models\Auth\Otp;
use App\Models\User;
use App\Repositories\OtpRepository;
use App\Repositories\UserRepository;
use App\Services\Auth\SMS\MelipayamakService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(OtpRepository::class, function($app){
            return new OtpRepository($app->make(Otp::class));
        });
        $this->app->bind(UserRepository::class, function($app){
            return new UserRepository($app->make(User::class));
        });
        $this->app->bind(NotificationServiceInterface::class, MelipayamakService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
