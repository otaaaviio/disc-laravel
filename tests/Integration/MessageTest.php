<?php

namespace Tests\Integration;

use App\Enums\Role;
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
    // arrange
    $user = User::factory()->create();
    $guild = Guild::factory()->create();
    $guild->members()->attach($user->id, ['role' => Role::Member]);

    $channel = Channel::factory()->create(['guild_id' => $guild->id]);

    // act & assert
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
})->skip(getenv('CI') !== false, 'Skipping this test on GitHub Actions');

test('should delete a message into a channel', function () {
    // arrange
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

    // act & assert
    $this->actingAs($user)
        ->delete('api/guilds/'.$guild->id.'/channels/'.$channel->id.'/messages/'.$message->id)
        ->assertStatus(StatusCode::HTTP_OK)
        ->assertJson(['message' => 'Message deleted successfully']);
})->skip(getenv('CI') !== false, 'Skipping this test on GitHub Actions');
