<?php

namespace Tests\Integration;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response as StatusCode;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function testLogin()
    {
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
    }

    public function testLoginWithInvalidCredentials()
    {
        User::factory()->create();

        $response = $this->postJson('api/auth/login', [
            'email' => 'fake@email.com',
            'password' => 'password',
        ]);

        $response->assertStatus(StatusCode::HTTP_UNAUTHORIZED);
    }

    public function testLogout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('api/auth/logout');
        $response->assertStatus(StatusCode::HTTP_OK);

    }

    public function testRegister()
    {
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
    }

    public function testGetUserAuthenticated()
    {
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
    }
}
