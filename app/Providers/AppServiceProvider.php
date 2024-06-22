<?php

namespace App\Providers;

use App\Interfaces\Services\IAuthService;
use App\Interfaces\Services\IChannelService;
use App\Interfaces\Services\IGuildService;
use App\Interfaces\Services\IMessageService;
use App\Services\AuthService;
use App\Services\ChannelService;
use App\Services\GuildService;
use App\Services\MessageService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(IGuildService::class, GuildService::class);
        $this->app->bind(IChannelService::class, ChannelService::class);
        $this->app->bind(IMessageService::class, MessageService::class);
    }

    public function boot(): void
    {
        //
    }
}
