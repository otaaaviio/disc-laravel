<?php

namespace App\Http\Controllers;

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

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\RequestBody(
     *         description="User login credentials",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", description="The email of the user"),
     *             @OA\Property(property="password", type="string", format="password", description="The password of the user")
     *         )
     *     )
     * )
     */
    public function login(AuthRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $token = $this->authService->login($credentials);

        return response()->json([
            'message' => 'Successfully logged in',
            'token' => $token
        ], StatusCode::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ], StatusCode::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\RequestBody(
     *         description="User registration data",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", format="text", description="The name of the user"),
     *             @OA\Property(property="email", type="string", format="email", description="The email of the user"),
     *             @OA\Property(property="password", type="string", format="password", description="The password of the user")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/auth/user",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function getUserAuthenticated(): JsonResponse
    {
        $userResource = $this->authService->user();

        return response()->json([
            'user' => $userResource
        ], StatusCode::HTTP_OK);
    }
}
