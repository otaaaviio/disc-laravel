<?php

use App\enums\Role;
use App\Models\Channel;
use App\Models\Guild;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response as StatusCode;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class);

uses()->group('Channel Test');

test('should register a new channel in a existing guild', function () {
    $user = User::factory()->create();
    $guild = Guild::factory()->create();
    $guild->members()->attach($user->id, ['role' => Role::Admin]);

    $this->actingAs($user)
        ->postJson('api/guilds/'.$guild->id.'/channels', [
            'name' => 'Channel Test',
            'description' => 'Channel Description',
        ])
        ->assertStatus(StatusCode::HTTP_CREATED)
        ->assertJsonStructure([
            'message',
            'channel' => [
                'id',
                'name',
                'description',
            ],
        ]);
});

test('should att a channel', function () {
    $user = User::factory()->create();
    $guild = Guild::factory()->create();
    $guild->members()->attach($user->id, ['role' => Role::Admin]);
    $channel = Channel::factory()->create([
        'guild_id' => $guild->id,
    ]);

    $this->actingAs($user)
        ->putJson('api/guilds/'.$guild->id.'/channels/'.$channel->id, [
            'name' => 'Channel Test',
            'description' => 'Channel Description',
        ])
        ->assertStatus(StatusCode::HTTP_OK)
        ->assertJsonStructure([
            'message',
            'channel' => [
                'id',
                'name',
                'description',
            ],
        ]);
});

test('should delete a channel', function () {
    $user = User::factory()->create();
    $guild = Guild::factory()->create();
    $guild->members()->attach($user->id, ['role' => Role::Admin]);
    $channel = Channel::factory()->create([
        'guild_id' => $guild->id,
    ]);

    $this->actingAs($user)
        ->deleteJson('api/guilds/'.$guild->id.'/channels/'.$channel->id, [
            'name' => 'Channel Test',
            'description' => 'Channel Description',
        ])
        ->assertStatus(StatusCode::HTTP_OK)
        ->assertJson([
            'message' => 'Channel successfully deleted',
        ]);
});

test('cannot manage a non existing channel', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->deleteJson('api/guilds/'. 99999 .'/channels/'. 99999, [
            'name' => 'Channel Test',
        ])
        ->assertStatus(StatusCode::HTTP_NOT_FOUND);
});

test('should join a channel', function () {
    $user = User::factory()->create();
    $guild = Guild::factory()->create();
    $guild->members()->attach($user->id, ['role' => Role::Member]);

    $channel = Channel::factory()->create([
        'guild_id' => $guild->id,
    ]);

    $this->actingAs($user)
        ->getJson('api/guilds/'.$guild->id.'/channels/'.$channel->id)
        ->assertStatus(StatusCode::HTTP_OK)
        ->assertJson([
            'message' => 'Successfully joined channel '.$channel->name,
        ]);
})->skip(getenv('CI') !== false, 'Skipping this test on GitHub Actions');
