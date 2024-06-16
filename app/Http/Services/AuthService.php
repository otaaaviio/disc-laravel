<?php

namespace App\Http\Services;

use App\Exceptions\AuthException;
use App\Http\Resources\AuthResource;
use App\interfaces\Repositories\IUserRepository;
use App\interfaces\Services\IAuthService;
use Illuminate\Support\Facades\Auth;

class AuthService implements IAuthService
{
    protected IUserRepository $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

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

    /**
     * @throws AuthException
     */
    public function logout(): void
    {
        Auth::user()->tokens()->delete();
    }

    public function register(array $data): array
    {
        $user = $this->userRepository->create($data);
        $token = $user->createToken('Access Token')->plainTextToken;

        return [
            'token' => $token,
            'user' => AuthResource::make($user),
        ];
    }

    public function user(): AuthResource
    {
        return AuthResource::make(Auth::user());
    }
}
