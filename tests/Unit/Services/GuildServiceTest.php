<?php

namespace Tests\Unit\Services;

use App\Exceptions\GuildException;
use App\Http\Services\GuildService;
use App\interfaces\Repositories\IGuildRepository;
use App\Models\Guild;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Mockery\MockInterface;

uses(TestCase::class, DatabaseTransactions::class);

uses()->group('GuildService Test');

beforeEach(function () {
    if (!isset($this->user)) {
        $this->user = User::factory()->create([
            'is_super_admin' => true
        ]);
    }
});

test('should create a guild', function () {
    $mockGuildRepository = $this->mock(IGuildRepository::class, function (MockInterface $mock) {
        $mock->shouldReceive('create')
            ->once()
            ->with(['data'], $this->user->id)
            ->andReturn(new Guild([
                'name' => 'test',
                'description' => 'test description',
                'icon_url' => 'test icon url'
            ]));
    });

    $this->actingAs($this->user);

    $guildService = new GuildService($mockGuildRepository);
    $res = $guildService->upsertGuild([
        'data'
    ]);

    $this->assertEquals('test', $res->name);
    $this->assertEquals('test description', $res->description);
    $this->assertEquals('test icon url', $res->icon_url);
});

test('should update a guild', function () {
    $guild = Guild::factory()->create();

    $mockGuildRepository = $this->mock(IGuildRepository::class, function (MockInterface $mock) use ($guild) {
        $mock->shouldReceive('update')
            ->once()
            ->with($guild, ['data'], $this->user->id)
            ->andReturn(new Guild([
                'name' => 'test updated',
                'description' => 'test description updated',
                'icon_url' => 'test icon url updated'
            ]));
    });

    $this->actingAs($this->user);

    $guildService = new GuildService($mockGuildRepository);
    $res = $guildService->upsertGuild(['data'], $guild);

    $this->assertEquals('test updated', $res->name);
    $this->assertEquals('test description updated', $res->description);
    $this->assertEquals('test icon url updated', $res->icon_url);
});

test('should delete a guild', function () {
    $guild = Guild::factory()->create();

    $mockGuildRepository = $this->mock(IGuildRepository::class, function (MockInterface $mock) use ($guild) {
        $mock->shouldReceive('delete')
            ->once()
            ->with($guild, $this->user->id)
            ->andReturn(true);
    });

    $this->actingAs($this->user);

    $guildService = new GuildService($mockGuildRepository);
    $guildService->delete($guild);

    $mockGuildRepository->shouldHaveReceived('delete');
});

test('should get invite code', function () {
    $guild = Guild::factory()->create();

    $mockGuildRepository = $this->mock(IGuildRepository::class, function (MockInterface $mock) use ($guild) {
        $mock->shouldReceive('getInviteCode')
            ->once()
            ->with($guild, $this->user->id)
            ->andReturn('token');
    });

    $this->actingAs($this->user);

    $guildService = new GuildService($mockGuildRepository);
    $res = $guildService->getInviteCode($guild);

    $this->assertEquals('token', $res);
});

test('should entry by invite code into a guild', function () {
    $mockGuildRepository = $this->mock(IGuildRepository::class, function (MockInterface $mock) {
        $mock->shouldReceive('entryByInviteCode')
            ->once()
            ->with('token', $this->user->id)
            ->andReturn(new Guild([
                'name' => 'test',
                'description' => 'test description',
                'icon_url' => 'test icon url'
            ]));
    });

    $this->actingAs($this->user);

    $guildService = new GuildService($mockGuildRepository);
    $res = $guildService->entryByInviteCode('token');

    $this->assertEquals('test', $res->name);
    $this->assertEquals('test description', $res->description);
    $this->assertEquals('test icon url', $res->icon_url);
});

test('should show a guild', function () {
    $guild = Guild::factory()->create();

    $mockGuildRepository = $this->mock(IGuildRepository::class, function (MockInterface $mock) use ($guild) {
        $mock->shouldReceive('getGuild')
            ->once()
            ->with($guild, $this->user->id)
            ->andReturn(new Guild([
                'name' => 'test',
                'description' => 'test description',
                'icon_url' => 'test icon url'
            ]));
    });

    $this->actingAs($this->user);

    $guildService = new GuildService($mockGuildRepository);
    $res = $guildService->show($guild);

    $this->assertEquals('test', $res->name);
    $this->assertEquals('test description', $res->description);
    $this->assertEquals('test icon url', $res->icon_url);
});

test('should get all guilds', function () {
    $mockGuildRepository = $this->mock(IGuildRepository::class, function (MockInterface $mock) {
        $mock->shouldReceive('all')
            ->once()
            ->andReturn(new Collection([
                new Guild([
                    'name' => 'test',
                    'description' => 'test description',
                    'icon_url' => 'test icon url'
                ])
            ]));
    });

    $guildService = new GuildService($mockGuildRepository);
    $res = $guildService->getAllGuilds();

    $this->assertEquals('test', $res->first()->name);
    $this->assertEquals('test description', $res->first()->description);
    $this->assertEquals('test icon url', $res->first()->icon_url);
});

test('should get all guilds by user id', function () {
    $mockGuildRepository = $this->mock(IGuildRepository::class, function (MockInterface $mock) {
        $mock->shouldReceive('getGuildsByUserId')
            ->once()
            ->with($this->user->id)
            ->andReturn(new Collection([
                new Guild([
                    'name' => 'test',
                    'description' => 'test description',
                    'icon_url' => 'test icon url'
                ])
            ]));
    });

    $this->actingAs($this->user);

    $guildService = new GuildService($mockGuildRepository);
    $res = $guildService->index();

    $this->assertEquals('test', $res->first()->name);
    $this->assertEquals('test description', $res->first()->description);
    $this->assertEquals('test icon url', $res->first()->icon_url);
});

test('should return a detailed guild resource', function () {
    $guild = Guild::factory()->create();

    $mockGuildRepository = $this->mock(IGuildRepository::class, function (MockInterface $mock) use ($guild) {
        $mock->shouldReceive('getGuild')
            ->once()
            ->with($guild, $this->user->id)
            ->andReturn(new Guild([
                'name' => 'test',
                'description' => 'test description',
                'icon_url' => 'test icon url'
            ]));
    });

    $this->actingAs($this->user);

    $guildService = new GuildService($mockGuildRepository);
    $res = $guildService->show($guild);

    $this->assertEquals('test', $res->name);
    $this->assertEquals('test description', $res->description);
    $this->assertEquals('test icon url', $res->icon_url);
});

test('should throw an exception when guild not found', function () {
    $guild = Guild::factory()->create();

    $mockGuildRepository = $this->mock(IGuildRepository::class, function (MockInterface $mock) use ($guild) {
        $mock->shouldReceive('getGuild')
            ->once()
            ->with($guild, $this->user->id)
            ->andReturn(null);
    });

    $this->actingAs($this->user);

    $guildService = new GuildService($mockGuildRepository);
    $guildService->show($guild);
})->throws(GuildException::class);
