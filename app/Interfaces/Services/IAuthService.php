<?php

namespace App\Interfaces\Services;

use App\Http\Resources\UserDetailedResource;

interface IAuthService
{
    public function loginUser(array $credentials): string;

    public function logoutUser(): void;

    public function registerUser(array $data): array;

    public function getAuthenticatedUser(): UserDetailedResource;
}
