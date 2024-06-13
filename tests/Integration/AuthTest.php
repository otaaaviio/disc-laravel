<?php

namespace Tests\Integration;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response as StatusCode;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

uses()->group('Auth Test');

test('can login in system', function () {
    $user = User::factory()->create();

    $response = $this->postJson('api/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(StatusCode::HTTP_OK);
    $response->assertJsonStructure([
        'message',
        'token',
    ]);
});

test('cannot login with invalid credentials', function () {
    User::factory()->make();

    $response = $this->postJson('api/auth/login', [
        'email' => 'fake@email.com',
        'password' => 'password',
    ]);

    $response->assertStatus(StatusCode::HTTP_UNAUTHORIZED);
});

test('can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('api/auth/logout');
    $response->assertStatus(StatusCode::HTTP_OK);
});

test('can register a new user', function () {
    $payload = [
        'name' => $this->faker->name,
        'email' => $this->faker->unique()->safeEmail,
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $response = $this->postJson('api/auth/register', $payload);
    $response->assertStatus(StatusCode::HTTP_CREATED);
    $response->assertJsonStructure([
        'user',
        'token',
    ]);
});

test('can get user authenticated', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson('api/auth/user');

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
    $response = $this->getJson('api/auth/user');

    $response->assertStatus(StatusCode::HTTP_UNAUTHORIZED);
});
