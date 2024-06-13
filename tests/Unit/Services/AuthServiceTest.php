<?php

namespace Tests\Unit\Services;

use App\Http\Resources\AuthResource;
use App\Http\Services\AuthService;
use App\interfaces\Repositories\IUserRepository;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Mockery;
use Mockery\MockInterface;

uses(TestCase::class);

uses()->group('AuthService Test');

test('test register a user', function () {
    $mockUserRepository = $this->mock(IUserRepository::class, function (MockInterface $mock) {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('createToken')->andReturn((object)['plainTextToken' => 'token']);

        $mock->shouldReceive('create')
            ->once()
            ->with([
                'name' => 'test',
                'email' => 'mock@example.com',
                'password' => 'password',
            ])
            ->andReturn($user);
    });

    $authService = new AuthService($mockUserRepository);

    $data = [
        'name' => 'test',
        'email' => 'mock@example.com',
        'password' => 'password',
    ];

    $res = $authService->register($data);

    $this->assertInstanceOf(AuthResource::class, $res['user']);
    $this->assertArrayHasKey('user', $res);
    $this->assertArrayHasKey('token', $res);
});
