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
        $mock->shouldReceive('login')->once();
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
});

test('test login with invalid credentials', function () {
    $this->mock(IAuthService::class, function (MockInterface $mock) {
        $mock->shouldReceive('login')->andThrow(new AuthException());
    });

    $response = $this->postJson('api/auth/login', [
        'email' => 'asd@asdok.com',
        'password' => 'asdsda',
    ]);

    $response->assertStatus(StatusCode::HTTP_UNAUTHORIZED);
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

    $authResource = new AuthResource($user);

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
