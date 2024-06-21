<?php

namespace App\interfaces\Services;

use App\Http\Resources\AuthResource;
use App\Http\Resources\UserDetailedResource;

interface IAuthService
{
    public function login(array $credentials): string;

    public function logout(): void;

    public function register(array $data): array;

    public function user(): UserDetailedResource;
}
