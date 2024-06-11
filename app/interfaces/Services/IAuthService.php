<?php

namespace App\interfaces\Services;

use App\Http\Resources\AuthResource;

interface IAuthService
{
    public function login(array $credentials): string;

    public function logout(): void;

    public function register(array $data): array;

    public function user(): AuthResource;
}
