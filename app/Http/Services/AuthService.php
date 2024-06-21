<?php

namespace App\Http\Services;

use App\Exceptions\AuthException;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserDetailedResource;
use App\interfaces\Services\IAuthService;
use App\Jobs\SendWelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService implements IAuthService
{
    /**
     * @throws AuthException
     */
    public function login(array $credentials): string
    {
        if (! Auth::attempt($credentials)) {
            throw AuthException::invalidCredentials();
        }

        return Auth::user()->createToken('Access Token')->plainTextToken;
    }

    public function logout(): void
    {
        Auth::user()->tokens()->delete();
    }

    public function register(array $data): array
    {
        $user = User::create($data);
        $token = $user->createToken('Access Token')->plainTextToken;

        SendWelcomeMail::dispatch($user)->onConnection('database');

        return [
            'token' => $token,
            'user' => AuthResource::make($user),
        ];
    }

    public function user(): UserDetailedResource
    {
        return UserDetailedResource::make(Auth::user());
    }
}
