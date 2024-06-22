<?php

use App\Exceptions\AuthException;
use App\Http\Controllers\AuthController;
use App\Http\Resources\UserDetailedResource;
use App\Interfaces\Services\IAuthService;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Response as StatusCode;

uses(TestCase::class);

uses()->group('AuthController Test');

test('test login', function () {
    $this->mock(IAuthService::class, function (MockInterface $mock) {
        $mock->shouldReceive('loginUser')->once()->andReturn('token');
    });

    $res = $this->postJson('api/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $res->assertStatus(StatusCode::HTTP_OK)
        ->assertJsonStructure([
            'message',
            'token',
        ]);
});

test('test login with invalid credentials', function () {
    $this->mock(IAuthService::class, function (MockInterface $mock) {
        $mock->shouldReceive('loginUser')->andThrow(AuthException::invalidCredentials());
    });

    $res = $this->postJson('api/auth/login', [
        'email' => 'mock@example.com',
        'password' => 'password',
    ]);

    $res->assertStatus(StatusCode::HTTP_UNAUTHORIZED)
        ->assertJson([
            'message' => 'Invalid credentials',
        ]);
});

test('test logout', function () {
    $user = User::factory()->make();

    $mockAuthService = $this->mock(IAuthService::class, function (MockInterface $mock) {
        $mock->shouldReceive('logoutUser')->once();
    });

    $authController = new AuthController($mockAuthService);

    $this->actingAs($user);

    $res = $authController->logout();

    $this->assertEquals(StatusCode::HTTP_OK, $res->status());
});

test('test get user authenticated', function () {
    $user = User::factory()->make();

    $userResource = new UserDetailedResource($user);

    $this->mock(IAuthService::class, function (MockInterface $mock) use ($userResource) {
        $mock->shouldReceive('getAuthenticatedUser')->once()->andReturn($userResource);
    });

    $res = $this->actingAs($user)->getJson('api/auth/user');

    $res->assertStatus(StatusCode::HTTP_OK)
        ->assertJsonStructure([
            'user' => [
                'id',
                'name',
                'email',
            ],
        ]);
});

test('test register', function () {
    $this->mock(IAuthService::class, function (MockInterface $mock) {
        $mock->shouldReceive('registerUser')
            ->once()
            ->andReturn([
                'user' => [
                    'id' => 1,
                    'name' => 'test',
                    'email' => 'newtest@example.com',
                ],
                'token' => 'token',
            ]);
    });

    $response = $this->postJson('api/auth/register', [
        'name' => 'test',
        'email' => 'newtest@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $response->assertStatus(StatusCode::HTTP_CREATED);
});

test('test access user profile without authentication', function () {
    $response = $this->getJson('api/auth/user');

    $response->assertStatus(StatusCode::HTTP_UNAUTHORIZED);
});
