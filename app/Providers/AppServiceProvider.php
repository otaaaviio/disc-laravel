<?php

namespace App\Providers;

use App\Http\Repositories\UserRepository;
use App\Http\Services\AuthService;
use App\interfaces\Repositories\IUserRepository;
use App\interfaces\Services\IAuthService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
