<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\GuildController;
use App\Http\Controllers\MessageController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('guilds', GuildController::class);

    Route::prefix('guilds')
        ->group(function () {
            Route::get('/inviteCode/{guild}', [GuildController::class, 'getInviteCode']);
            Route::post('/entry', [GuildController::class, 'entryByInviteCode']);
            Route::post('/leave/{guild}', [GuildController::class, 'leave']);
            Route::get('/user', [GuildController::class, 'show']);

            Route::prefix('/{guild}/channels')->group(function () {
                Route::get('/{channel}', [ChannelController::class, 'join']);
                Route::post('/', [ChannelController::class, 'store']);
                Route::put('/{channel}', [ChannelController::class, 'update']);
                Route::delete('/{channel}', [ChannelController::class, 'destroy']);

                Route::prefix('/{channel}/messages')->group(function () {
                    Route::post('/', [MessageController::class, 'store']);
                    Route::delete('/{message}', [MessageController::class, 'destroy']);
                });
            });
        });

    Route::get('allGuilds', [GuildController::class, 'getAllGuilds'])
        ->middleware(AdminMiddleware::class);

    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'show']);
    });
});
