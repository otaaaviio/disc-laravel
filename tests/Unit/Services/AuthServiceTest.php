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

test('test register a user', function () {
    $mockUserRepository = $this->mock(IUserRepository::class, function (MockInterface $mock) {
        $mockUser = Mockery::mock(User::class);
        $mockUser->shouldReceive('createToken')->andReturn((object)['plainTextToken' => 'token']);

        $mock->shouldReceive('create')
            ->once()
            ->with([
                'name' => 'test',
                'email' => 'mock@example.com',
                'password' => 'password',
            ])
            ->andReturn($mockUser);
    });

    $authService = new AuthService($mockUserRepository);

    $data = [
        'name' => 'test',
        'email' => 'mock@example.com',
        'password' => 'password',
    ];

    $result = $authService->register($data);

    $this->assertInstanceOf(AuthResource::class, $result['user']);
    $this->assertIsString($result['token']);
});
