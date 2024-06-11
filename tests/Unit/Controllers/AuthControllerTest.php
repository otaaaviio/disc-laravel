<?php

use App\Exceptions\AuthException;
use App\Http\Resources\AuthResource;
use App\interfaces\Services\IAuthService;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Response as StatusCode;

uses(TestCase::class);

test('test login', function () {
    $this->mock(IAuthService::class, function (MockInterface $mock) {
        $mock->shouldReceive('login')->once()->andReturn('token');
    });

    $response = $this->postJson('api/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(StatusCode::HTTP_OK);
    $response->assertJsonStructure([
        'message',
        'token',
    ]);

    $responseData = json_decode($response->getContent(), true);
    $this->assertNotEmpty($responseData['token']);
});

test('test login with invalid credentials', function () {
    $this->mock(IAuthService::class, function (MockInterface $mock) {
        $mock->shouldReceive('login')->andThrow(AuthException::invalidCredentials());
    });

    $response = $this->postJson('api/auth/login', [
        'email' => 'mock@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(StatusCode::HTTP_UNAUTHORIZED);
    $response->assertJson([
        'message' => 'Invalid credentials',
    ]);
});

test('test logout', function () {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('getKey')->andReturn(1);
    $user->shouldReceive('withAccessToken')->andReturnSelf();

    $this->mock(IAuthService::class, function (MockInterface $mock) {
        $mock->shouldReceive('logout')->once();
    });

    $response = $this->actingAs($user)->postJson('api/auth/logout');

    $response->assertStatus(StatusCode::HTTP_OK);
});

test('test get user', function () {
    $user = Mockery::mock(User::class);
    $user->shouldReceive('getKey')->andReturn(1);
    $user->shouldReceive('withAccessToken')->andReturnSelf();
    $user->shouldReceive('getAttribute')->andReturn('token');

    $mockUser = Mockery::mock(User::class);
    $mockUser->shouldReceive('getAttribute')
        ->with('id')
        ->andReturn(1);
    $mockUser->shouldReceive('getAttribute')
        ->with('name')
        ->andReturn('test');
    $mockUser->shouldReceive('getAttribute')
        ->with('email')
        ->andReturn('mock@example.com');

    $authResource = new AuthResource($mockUser);

    $this->mock(IAuthService::class, function (MockInterface $mock) use ($authResource) {
        $mock->shouldReceive('user')->once()->andReturn($authResource);
    });

    $response = $this->actingAs($user)->getJson('api/auth/user');

    $response->assertStatus(StatusCode::HTTP_OK);
});

test('test register', function () {
    $this->mock(IAuthService::class, function (MockInterface $mock) {
        $mock->shouldReceive('register')->once()->andReturn([
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
