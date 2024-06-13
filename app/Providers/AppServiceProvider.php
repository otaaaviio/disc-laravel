<?php

namespace App\Providers;

use App\Http\Repositories\GuildRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Services\AuthService;
use App\Http\Services\GuildService;
use App\interfaces\Repositories\IGuildRepository;
use App\interfaces\Repositories\IUserRepository;
use App\interfaces\Services\IAuthService;
use App\interfaces\Services\IGuildService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IGuildRepository::class, GuildRepository::class);
        $this->app->bind(IGuildService::class, GuildService::class);
    }

    public function boot(): void
    {
        //
    }
}
