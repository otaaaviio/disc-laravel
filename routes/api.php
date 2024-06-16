<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\GuildController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('guilds', GuildController::class);
    Route::get('guilds/inviteCode/{guild}', [GuildController::class, 'getInviteCode']);
    Route::post('guilds/entry', [GuildController::class, 'entryByInviteCode']);
    Route::get('allGuilds', [GuildController::class, 'getAllGuilds'])
        ->middleware(AdminMiddleware::class);
    Route::get('guilds/user', [GuildController::class, 'getAuthenticatedUserGuilds']);
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'getUserAuthenticated']);
    });
    Route::prefix('/guilds/{guild}/channels')->group(function () {
        Route::post('/', [ChannelController::class, 'store']);
        Route::put('/{channel}', [ChannelController::class, 'update']);
        Route::delete('/{channel}', [ChannelController::class, 'destroy']);
    });
});
