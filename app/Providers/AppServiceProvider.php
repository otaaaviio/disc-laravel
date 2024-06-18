<?php

namespace App\Providers;

use App\Http\Services\AuthService;
use App\Http\Services\ChannelService;
use App\Http\Services\GuildService;
use App\Http\Services\MessageService;
use App\interfaces\Services\IAuthService;
use App\interfaces\Services\IChannelService;
use App\interfaces\Services\IGuildService;
use App\interfaces\Services\IMessageService;
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
