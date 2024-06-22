<?php

use App\Exceptions\AuthException;
use App\Http\Controllers\AuthController;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserDetailedResource;
use App\Interfaces\Services\IAuthService;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\Request;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Response as StatusCode;

uses(TestCase::class);

uses()->group('AuthController Test');

test('test login', function () {
    // arrange
    $mockAuthService = $this->mock(IAuthService::class, function (MockInterface $mock) {
        $mock->shouldReceive('loginUser')->once()->andReturn('token');
    });

    $request = Request::create('/login', 'POST', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $req = AuthRequest::createFrom($request);
    $req->setContainer(app());
    $req->validateResolved();

    $authController = new AuthController($mockAuthService);

    // act
    $res = $authController->login($req);

    // assert
    $this->assertEquals(StatusCode::HTTP_OK, $res->status());
    $this->assertEquals([
        'message' => 'Successfully logged in',
        'token' => 'token'
    ], $res->getData(true));
});

test('test login with invalid credentials', function () {
    // arrange
    $mockAuthService = $this->mock(IAuthService::class, function (MockInterface $mock) {
        $mock->shouldReceive('loginUser')->andThrow(AuthException::invalidCredentials());
    });

    $request = Request::create('/login', 'POST', [
        'email' => 'asd@asd.com',
        'password' => 'asd',
    ]);

    $req = AuthRequest::createFrom($request);
    $req->setContainer(app());
    $req->validateResolved();

    $authController = new AuthController($mockAuthService);

    // act & assert
    $authController->login($req);
})->throws(AuthException::class);

test('test logout', function () {
    // arrange
    $mockAuthService = $this->mock(IAuthService::class, function (MockInterface $mock) {
        $mock->shouldReceive('logoutUser')->once();
    });

    $authController = new AuthController($mockAuthService);

    // act
    $res = $authController->logout();

    // assert
    $this->assertEquals(StatusCode::HTTP_OK, $res->status());
});

test('test get user authenticated', function () {
    // arrange
    $user = User::factory()->make();
    $userResource = new UserDetailedResource($user);

    $mockAuthService = $this->mock(IAuthService::class, function (MockInterface $mock) use ($userResource) {
        $mock->shouldReceive('getAuthenticatedUser')->once()->andReturn($userResource);
    });

    $authController = new AuthController($mockAuthService);

    // act
    $res = $authController->show();

    // assert
    $this->assertEquals(StatusCode::HTTP_OK, $res->status());
    $this->assertEquals([
        'user' => $userResource->resolve(),
    ], $res->getData(true));
});

test('test register', function () {
    // arrange
    $mockAuthService = $this->mock(IAuthService::class, function (MockInterface $mock) {
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

    $request = Request::create('/register', 'POST', [
        'name' => 'test',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $req = RegisterRequest::createFrom($request);
    $req->setContainer(app());
    $req->validateResolved();

    $authController = new AuthController($mockAuthService);

    // act
    $res = $authController->register($req);

    // assert
    $this->assertEquals(StatusCode::HTTP_CREATED, $res->status());
});

test('test access user profile without authentication', function () {
    // arrange
    $mockAuthService = $this->mock(IAuthService::class, function (MockInterface $mock) {
        $mock->shouldReceive('getAuthenticatedUser')->andThrow(AuthException::unauthorized());
    });

    $authController = new AuthController($mockAuthService);

    // act & assert
    $res = $authController->show();
})->throws(AuthException::class);
