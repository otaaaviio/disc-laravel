<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use App\Interfaces\Services\IAuthService;
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

        $token = $this->authService->loginUser($credentials);

        return response()->json([
            'message' => 'Successfully logged in',
            'token' => $token,
        ], StatusCode::HTTP_OK);
    }

    public function logout(): JsonResponse
    {
        $this->authService->logoutUser();

        return response()->json([
            'message' => 'Successfully logged out',
        ], StatusCode::HTTP_OK);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $res = $this->authService->registerUser($data);

        return response()->json([
            'message' => 'Successfully registered',
            'user' => $res['user'],
            'token' => $res['token'],
        ], StatusCode::HTTP_CREATED);
    }

    public function show(): JsonResponse
    {
        $userResource = $this->authService->getAuthenticatedUser();

        return response()->json([
            'user' => $userResource,
        ], StatusCode::HTTP_OK);
    }
}
