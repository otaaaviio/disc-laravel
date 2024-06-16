<?php

namespace Tests\Unit\Services;

use App\enums\Role;
use App\Http\Services\ChannelService;
use App\interfaces\Repositories\IChannelRepository;
use App\Models\Channel;
use App\Models\Guild;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Mockery\MockInterface;

uses(TestCase::class, DatabaseTransactions::class);

uses()->group('ChannelService Test');

test('test create', function () {
    $guild = Guild::factory()->create();
    $user = User::factory()->create();
    $guild->members()->attach($user->id, ['role' => Role::Moderator]);

    $mockChannelRepository = $this->mock(IChannelRepository::class,
        function (MockInterface $mock) use ($guild, $user) {
            $mock->shouldReceive('create')
                ->once()
                ->with(['data'], $guild->id, $user->id)
                ->andReturn(new Channel([
                    'name' => 'test',
                    'description' => 'test description',
                ]));
        });

    $this->actingAs($user);

    $channelService = new ChannelService($mockChannelRepository);

    $res = $channelService->upsert(['data'], $guild);

    $this->assertEquals('test', $res->name);
    $this->assertEquals('test description', $res->description);
});

test('test update', function () {
    $channel = Channel::factory()->create();
    $user = User::factory()->create();

    $mockChannelRepository = $this->mock(IChannelRepository::class,
        function (MockInterface $mock) use ($channel, $user) {
            $mock->shouldReceive('update')
                ->once()
                ->with(['data'], $user->id, $channel->guild->id, $channel)
                ->andReturn(new Channel([
                    'name' => 'test updated',
                    'description' => 'test description updated',
                ]));
        });

    $this->actingAs($user);

    $channelService = new ChannelService($mockChannelRepository);

    $res = $channelService->upsert(['data'], $channel->guild, $channel);

    $this->assertEquals('test updated', $res->name);
    $this->assertEquals('test description updated', $res->description);
});

test('test delete', function () {
    $channel = Channel::factory()->create();
    $user = User::factory()->create();

    $mockChannelRepository = $this->mock(IChannelRepository::class,
        function (MockInterface $mock) use ($channel, $user) {
            $mock->shouldReceive('delete')
                ->once()
                ->with($channel->guild->id, $user->id, $channel);
        });

    $this->actingAs($user);

    $channelService = new ChannelService($mockChannelRepository);

    $channelService->delete($channel->guild, $channel);

    $mockChannelRepository->shouldHaveReceived('delete');
});
