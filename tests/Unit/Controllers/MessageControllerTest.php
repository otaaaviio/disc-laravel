<?php

namespace Tests\Unit\Controllers;

use App\Enums\Role;
use App\Exceptions\MessageException;
use App\Http\Controllers\MessageController;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Interfaces\Services\IMessageService;
use App\Models\Channel;
use App\Models\Guild;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Response as StatusCode;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class);

uses()->group('MessageController Test');

test('should send a message', function () {
    // arrange
    $user = User::factory()->create();
    $guild = Guild::factory()->create();
    $guild->members()->attach($user, ['role' => Role::Admin]);
    $channel = Channel::factory()->create(['guild_id' => $guild->id]);
    $messageReturned = Message::factory()->create(['user_id' => $user->id]);
    $requestMock = \Mockery::mock(StoreMessageRequest::class);
    $requestMock->shouldReceive('validated')->andReturn(['content' => $messageReturned->content]);

    $mockMessageService = $this->mock(IMessageService::class,
        function (MockInterface $mock) use ($messageReturned, $guild, $channel) {
            $mock->shouldReceive('sendMessage')
                ->once()
                ->with($guild, $channel, ['content' => $messageReturned->content])
                ->andReturn(
                    new MessageResource($messageReturned)
                );
        });

    $messageController = new MessageController($mockMessageService);

    // act
    $this->actingAs($user);

    $res = $messageController->store($guild, $channel, $requestMock);

    // assert
    $this->assertEquals(StatusCode::HTTP_CREATED, $res->status());
    $this->assertEquals([
        'message' => 'Message sent successfully',
        'data' => MessageResource::make($messageReturned)->resolve(),
    ], $res->getData(true));
});

test('should delete a message', function () {
    // arrange
    $user = User::factory()->create();
    $guild = Guild::factory()->create();
    $guild->members()->attach($user, ['role' => Role::Admin]);
    $channel = Channel::factory()->create(['guild_id' => $guild->id]);
    $message = Message::factory()->create(['user_id' => $user->id, 'channel_id' => $channel->id]);

    $mockMessageService = $this->mock(IMessageService::class,
        function (MockInterface $mock) use ($message, $guild, $channel) {
            $mock->shouldReceive('deleteMessage')
                ->once()
                ->with($guild, $channel, $message);
        });

    $messageController = new MessageController($mockMessageService);

    // act
    $this->actingAs($user);

    $res = $messageController->destroy($guild, $channel, $message);

    // assert
    $this->assertEquals(StatusCode::HTTP_OK, $res->status());
    $this->assertEquals([
        'message' => 'Message deleted successfully',
    ], $res->getData(true));
});

test('cannot delete a message that is not your', function () {
    // arrange
    $user = User::factory()->create();
    $guild = Guild::factory()->create();
    $guild->members()->attach($user, ['role' => Role::Admin]);
    $channel = Channel::factory()->create(['guild_id' => $guild->id]);
    $message = Message::factory()->create(['channel_id' => $channel->id]);

    $mockMessageService = $this->mock(IMessageService::class,
        function (MockInterface $mock) use ($message, $guild, $channel) {
            $mock->shouldReceive('deleteMessage')
                ->once()
                ->with($guild, $channel, $message)
                ->andThrow(MessageException::dontHavePermissionToDeleteMessage());
        });

    $messageController = new MessageController($mockMessageService);

    // act & assert
    $this->actingAs($user);
    $messageController->destroy($guild, $channel, $message);
})->throws(MessageException::class);
