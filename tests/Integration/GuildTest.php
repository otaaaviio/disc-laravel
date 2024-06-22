<?php

namespace Tests\Integration;

use App\Enums\Role;
use App\Models\Guild;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response as StatusCode;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

uses()->group('Guild Test');

test('should create a new Guild', function () {
    // arrange
    $user = User::factory()->create();
    $payload = [
        'name' => $this->faker->name,
        'description' => $this->faker->sentence,
        'icon' => $this->faker->imageUrl(),
    ];

    // act & assert
    $this->actingAs($user)->postJson('/api/guilds', $payload)
        ->assertStatus(StatusCode::HTTP_CREATED)
        ->assertJsonStructure([
            'guild' => [
                'id',
                'name',
                'description',
                'icon_url',
            ],
        ]);
});

test('should update a Guild', function () {
    // arrange
    $user = User::factory()->create();
    $guild = Guild::factory()->create();
    $guild->members()->attach($user->id, ['role' => Role::Admin]);

    $payload = [
        'name' => $this->faker->name,
        'description' => $this->faker->sentence,
        'icon' => $this->faker->imageUrl(),
    ];

    // act & assert
    $this->actingAs($user)->putJson('/api/guilds/' . $guild->id, $payload)
        ->assertStatus(StatusCode::HTTP_OK)
        ->assertJsonStructure([
            'message',
            'guild' => [
                'id',
                'name',
                'description',
                'icon_url',
            ],
        ]);
});

test('should return a specific a Guild', function () {
    // arrange
    $user = User::factory()->create();
    $guild = Guild::factory()->create();
    $guild->members()->attach($user->id, ['role' => Role::Admin]);

    // act & assert
    $this->actingAs($user)->getJson('/api/guilds/' . $guild->id)
        ->assertStatus(StatusCode::HTTP_OK)
        ->assertJsonStructure([
            'guild' => [
                'id',
                'name',
                'description',
                'icon_url',
                'channels',
                'members',
            ],
        ]);
});

test('should return authenticated user guilds', function () {
    // arrange
    $user = User::factory()->create();
    $guild = Guild::factory()->create();
    $guild->members()->attach($user->id, ['role' => Role::Member]);

    // act & assert
    $this->actingAs($user)->getJson('/api/guilds')
        ->assertStatus(StatusCode::HTTP_OK)
        ->assertJsonStructure([
            'guilds' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'icon_url',
                ],
            ],
        ]);

});

test('should return all guilds acting as admin user', function () {
    // arrange
    $user = User::factory([
        'is_super_admin' => true,
    ])->create();
    Guild::factory(5)->create();

    // act & assert
    $this->actingAs($user)->getJson('/api/allGuilds')
        ->assertStatus(StatusCode::HTTP_OK)
        ->assertJsonStructure([
            'guilds' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'icon_url',
                ],
            ],
        ]);
});

test('cannot return all guilds acting as non-admin user', function () {
    // arrange
    $user = User::factory(
        ['is_super_admin' => false]
    )->create();
    Guild::factory(5)->create();

    // act & assert
    $this->actingAs($user)->getJson('/api/allGuilds')
        ->assertStatus(StatusCode::HTTP_UNAUTHORIZED)
        ->assertJson(['message' => 'Unauthorized']);
});

test('should return guild invite code', function () {
    // arrange
    $user = User::factory()->create();
    $guild = Guild::factory()->create();
    $guild->members()->attach($user->id, ['role' => Role::Member]);

    // act & assert
    $this->actingAs($user)->getJson('/api/guilds/inviteCode/' . $guild->id)
        ->assertStatus(StatusCode::HTTP_OK)
        ->assertJson(['invite_code' => $guild->invite_code]);
});

test('should entry into a Guild', function () {
    // arrange
    $user = User::factory()->create();
    $guild = Guild::factory()->create();

    // act & assert
    $this->actingAs($user)->postJson('/api/guilds/entry/', ['invite_code' => $guild->invite_code])
        ->assertStatus(StatusCode::HTTP_OK)
        ->assertJsonStructure([
            'message',
            'guild' => [
                'id',
                'name',
                'description',
                'icon_url',
            ],
        ]);
});

test('should delete a Guild', function () {
    // arrange
    $user = User::factory()->create();
    $guild = Guild::factory()->create();
    $guild->members()->attach($user->id, ['role' => Role::Admin]);

    // act & assert
    $this->actingAs($user)->deleteJson('/api/guilds/' . $guild->id)
        ->assertStatus(StatusCode::HTTP_OK)
        ->assertJson([
            'message' => 'Guild successfully deleted',
        ]);
});

test('should leave a Guild', function () {
    // arrange
    $user = User::factory()->create();
    $guild = Guild::factory()->create();
    $guild->members()->attach($user->id, ['role' => Role::Member]);

    // act & assert
    $this->actingAs($user)->postJson('/api/guilds/leave/' . $guild->id)
        ->assertStatus(StatusCode::HTTP_OK)
        ->assertJsonStructure(['message']);
});

test('an admin cannot leave their Guild', function () {
    // arrange
    $user = User::factory()->create();
    $guild = Guild::factory()->create();
    $guild->members()->attach($user->id, ['role' => Role::Admin]);

    // act & assert
    $this->actingAs($user)->postJson('/api/guilds/leave/' . $guild->id)
        ->assertStatus(StatusCode::HTTP_UNAUTHORIZED)
        ->assertJsonStructure(['message']);
});
