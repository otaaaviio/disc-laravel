<?php

use App\Exceptions\AuthException;
use App\Http\Resources\AuthResource;
use App\interfaces\Services\IAuthService;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Response as StatusCode;

uses(TestCase::class);

uses()->group('AuthController Test');

test('test login', function () {
    $this->mock(IAuthService::class, function (MockInterface $mock) {
        $mock->shouldReceive('login')->once()->andReturn('token');
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
        $mock->shouldReceive('login')->andThrow(AuthException::invalidCredentials());
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

    $this->mock(IAuthService::class, function (MockInterface $mock) {
        $mock->shouldReceive('logout')->once();
    });

    $response = $this->actingAs($user)->postJson('api/auth/logout');

    $response->assertStatus(StatusCode::HTTP_OK);
});

test('test get user authenticated', function () {
    $user = User::factory()->make();

    $authResource = new AuthResource($user);

    $this->mock(IAuthService::class, function (MockInterface $mock) use ($authResource) {
        $mock->shouldReceive('user')->once()->andReturn($authResource);
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
        $mock->shouldReceive('register')
            ->once()
            ->andReturn([
                'user' => [
                    'id' => 1,
                    'name' => 'test',
                    'email' => 'newtest@example.com'
                ],
                'token' => 'token'
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
