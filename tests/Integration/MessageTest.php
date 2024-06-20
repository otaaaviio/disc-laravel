<?php

namespace Tests\Integration;

use App\enums\Role;
use App\Models\Channel;
use App\Models\Guild;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response as StatusCode;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class);

uses()->group('Message Test');

test('should send a message to a channel', function () {
    $user = User::factory()->create();
    $guild = Guild::factory()->create();
    $guild->members()->attach($user->id, ['role' => Role::Member]);

    $channel = Channel::factory()->create([
        'guild_id' => $guild->id,
    ]);

    $this->actingAs($user)
        ->postJson('api/guilds/'.$guild->id.'/channels/'.$channel->id.'/messages', ['content' => 'Hello World'])
        ->assertStatus(StatusCode::HTTP_CREATED)
        ->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'content',
                'user' => [
                    'id',
                    'name',
                ],
                'channel' => [
                    'id',
                    'name',
                ],
            ],
        ]);
});

test('should delete a message into a channel', function () {
    $user = User::factory()->create();
    $guild = Guild::factory()->create();
    $guild->members()->attach($user->id, ['role' => Role::Member]);

    $channel = Channel::factory()->create([
        'guild_id' => $guild->id,
    ]);

    $message = Message::factory()->create([
        'user_id' => $user->id,
        'channel_id' => $channel->id,
    ]);

    $this->actingAs($user)
        ->delete('api/guilds/'.$guild->id.'/channels/'.$channel->id.'/messages/'.$message->id)
        ->assertStatus(StatusCode::HTTP_OK)
        ->assertJson([
            'message' => 'Message deleted successfully',
        ]);
});