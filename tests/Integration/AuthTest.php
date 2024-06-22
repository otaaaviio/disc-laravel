<?php

namespace Tests\Integration;

use App\Jobs\SendWelcomeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\HttpFoundation\Response as StatusCode;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

uses()->group('Auth Test');

test('can login in system', function () {
    // arrange
    $user = User::factory()->create();

    // act
    $response = $this->postJson('api/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    // assert
    $response->assertStatus(StatusCode::HTTP_OK);
    $response->assertJsonStructure([
        'message',
        'token',
    ]);
});

test('cannot login with invalid credentials', function () {
    // arrange
    User::factory()->make();

    // act
    $response = $this->postJson('api/auth/login', [
        'email' => 'fake@email.com',
        'password' => 'password',
    ]);

    // assert
    $response->assertStatus(StatusCode::HTTP_UNAUTHORIZED);
});

test('can logout', function () {
    // arrange
    $user = User::factory()->create();

    // act
    $response = $this->actingAs($user)->postJson('api/auth/logout');

    // assert
    $response->assertStatus(StatusCode::HTTP_OK);
});

test('can register a new user', function () {
    // arrange
    $payload = [
        'name' => $this->faker->name,
        'email' => $this->faker->unique()->safeEmail,
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    // act
    $response = $this->postJson('api/auth/register', $payload);

    // assert
    $response->assertStatus(StatusCode::HTTP_CREATED);
    $response->assertJsonStructure([
        'user',
        'token',
    ]);
});

test('should get user authenticated', function () {
    // arrange
    $user = User::factory()->create();

    // act
    $response = $this->actingAs($user)->getJson('api/auth/user');

    // assert
    $response->assertStatus(StatusCode::HTTP_OK);
    $response->assertJson([
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ],
    ]);
});

test('cannot access user authenticated route without authentication', function () {
    // act
    $response = $this->getJson('api/auth/user');

    // assert
    $response->assertStatus(StatusCode::HTTP_UNAUTHORIZED);
});

test('should send a email to new user', function () {
    // arrange
    Queue::fake();

    $payload = [
        'name' => $this->faker->name,
        'email' => $this->faker->unique()->safeEmail,
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    // act & assert
    $this->postJson('api/auth/register', $payload)
        ->assertStatus(StatusCode::HTTP_CREATED);
    Queue::assertPushed(SendWelcomeMail::class, function ($job) use ($payload) {
        return $job->user->email === $payload['email'];
    });
});
