<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthException;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use App\interfaces\Services\IAuthService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as StatusCode;

class AuthController extends Controller
{
    protected IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(AuthRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $token = $this->authService->login($credentials);

        return response()->json([
            'message' => 'Successfully logged in',
            'token' => $token
        ], StatusCode::HTTP_OK);
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ], StatusCode::HTTP_OK);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $res = $this->authService->register($data);

        return response()->json([
            'message' => 'Successfully registered',
            'user' => $res['user'],
            'token' => $res['token'],
        ], StatusCode::HTTP_CREATED);
    }

    public function getUserAuthenticated(): JsonResponse
    {
        $userResource = $this->authService->user();

        return response()->json([
            'user' => $userResource
        ], StatusCode::HTTP_OK);
    }
}
