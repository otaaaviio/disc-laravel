<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use App\interfaces\Services\IAuthService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as StatusCode;

class AuthController extends Controller
{
    protected IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }

    #[OA\Post(
        path: '/api/auth/login',
        summary: 'Login user',
        requestBody: new OA\RequestBody(required: true,
            content: new OA\MediaType(mediaType: 'application/json',
                schema: new OA\Schema(required: ['email, password'],
                    properties: [
                        new OA\Property(property: 'email', description: 'email of user', type: 'string', format: 'email'),
                        new OA\Property(property: 'password', description: 'password of user', type: 'string', format: 'password')]
                ))),
        tags: ['Authentication'],
        responses: [
            new OA\Response(response: StatusCode::HTTP_OK, description: 'Successful operation'),
            new OA\Response(response: StatusCode::HTTP_BAD_REQUEST, description: 'Bad Request'),
            new OA\Response(response: StatusCode::HTTP_UNAUTHORIZED, description: 'Unauthorized'),
            new OA\Response(response: StatusCode::HTTP_INTERNAL_SERVER_ERROR, description: 'Server Error'),
        ]
    )]
    public function login(AuthRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $token = $this->authService->login($credentials);

        return response()->json([
            'message' => 'Successfully logged in',
            'token' => $token,
        ], StatusCode::HTTP_OK);
    }

    #[OA\Post(
        path: '/api/auth/logout',
        summary: 'Logout user',
        tags: ['Authenticated'],
        responses: [
            new OA\Response(response: StatusCode::HTTP_OK, description: 'Successful operation'),
            new OA\Response(response: StatusCode::HTTP_BAD_REQUEST, description: 'Bad Request'),
            new OA\Response(response: StatusCode::HTTP_UNAUTHORIZED, description: 'Unauthorized'),
            new OA\Response(response: StatusCode::HTTP_INTERNAL_SERVER_ERROR, description: 'Server Error'),
        ]
    )]
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'message' => 'Successfully logged out',
        ], StatusCode::HTTP_OK);
    }

    #[OA\Post(
        path: '/api/auth/register',
        summary: 'Logout user',
        requestBody: new OA\RequestBody(required: true,
            content: new OA\MediaType(mediaType: 'application/json',
                schema: new OA\Schema(required: ['name', 'email', 'password', 'password_confirmation'],
                    properties: [
                        new OA\Property(property: 'name', description: 'User name', type: 'string'),
                        new OA\Property(property: 'email', description: 'email of user', type: 'string', format: 'email'),
                        new OA\Property(property: 'password', description: 'password of user', type: 'string', format: 'password'),
                        new OA\Property(property: 'password_confirmation', description: 'password confirmation', type: 'string', format: 'password')]
                ))),
        tags: ['Registration'],
        responses: [
            new OA\Response(response: StatusCode::HTTP_CREATED, description: 'Successful operation'),
            new OA\Response(response: StatusCode::HTTP_BAD_REQUEST, description: 'Bad Request'),
            new OA\Response(response: StatusCode::HTTP_INTERNAL_SERVER_ERROR, description: 'Server Error'),
        ]
    )]
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

    #[OA\Get(
        path: '/api/auth/user',
        summary: 'Get authenticated user',
        tags: ['Authenticated'],
        responses: [
            new OA\Response(response: StatusCode::HTTP_OK, description: 'Successful operation'),
            new OA\Response(response: StatusCode::HTTP_BAD_REQUEST, description: 'Bad Request'),
            new OA\Response(response: StatusCode::HTTP_UNAUTHORIZED, description: 'Unauthorized'),
            new OA\Response(response: StatusCode::HTTP_INTERNAL_SERVER_ERROR, description: 'Server Error'),
        ]
    )]
    public function getUserAuthenticated(): JsonResponse
    {
        $userResource = $this->authService->user();

        return response()->json([
            'user' => $userResource,
        ], StatusCode::HTTP_OK);
    }
}
