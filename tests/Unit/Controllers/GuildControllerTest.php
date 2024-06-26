<?php

use App\Exceptions\GuildException;
use App\Http\Controllers\GuildController;
use App\Http\Resources\GuildDetailedResource;
use App\Http\Resources\GuildResource;
use App\Interfaces\Services\IGuildService;
use App\Models\Guild;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Response as StatusCode;

uses(TestCase::class, DatabaseTransactions::class);

uses()->group('GuildController Test');

test('should get all guilds', function () {
    // arrange
    $mockGuildService = $this->mock(IGuildService::class, function (MockInterface $mock) {
        $mock->shouldReceive('getAllGuilds')
            ->once()
            ->andReturn(new AnonymousResourceCollection(collect([]), GuildResource::class));
    });

    $guildController = new GuildController($mockGuildService);

    // act
    $res = $guildController->getAllGuilds();

    // assert
    $this->assertEquals(StatusCode::HTTP_OK, $res->status());
    $this->assertEquals(['guilds' => []], json_decode($res->getContent(), true));
});

test('should get guilds with index method', function () {
    // arrange
    $mockGuildService = $this->mock(IGuildService::class, function (MockInterface $mock) {
        $mock->shouldReceive('getUserGuilds')
            ->once()
            ->andReturn(new AnonymousResourceCollection(collect([]), GuildResource::class));
    });

    $guildController = new GuildController($mockGuildService);

    // act
    $res = $guildController->index();

    // assert
    $this->assertEquals(StatusCode::HTTP_OK, $res->status());
    $this->assertEquals(['guilds' => []], json_decode($res->getContent(), true));
});

test('should return a detailed guild', function () {
    // arrange
    $guild = Guild::factory()->create();
    $mockGuildService = $this->mock(IGuildService::class, function (MockInterface $mock) use ($guild) {
        $mock->shouldReceive('getGuild')
            ->once()
            ->with($guild)
            ->andReturn(new GuildDetailedResource($guild));
    });

    $guildController = new GuildController($mockGuildService);

    // act
    $res = $guildController->show($guild);

    // assert
    $this->assertEquals(StatusCode::HTTP_OK, $res->status());
    $this->assertEquals([
        'guild' => (new GuildDetailedResource($guild))
            ->toArray(request())], json_decode($res->getContent(), true));
});

test('should destroy a guild', function () {
    // arrange
    $guild = Guild::factory()->create();
    $mockGuildService = $this->mock(IGuildService::class, function (MockInterface $mock) use ($guild) {
        $mock->shouldReceive('delete')
            ->once()
            ->with($guild);
    });

    $guildController = new GuildController($mockGuildService);

    // act
    $res = $guildController->destroy($guild);

    // assert
    $this->assertEquals(StatusCode::HTTP_OK, $res->status());
    $this->assertEquals([
        'message' => 'Guild successfully deleted',
    ], json_decode($res->getContent(), true));
});

test('should get a guild invite code', function () {
    // arrange
    $guild = Guild::factory()->create();
    $mockGuildService = $this->mock(IGuildService::class, function (MockInterface $mock) use ($guild) {
        $mock->shouldReceive('getInviteCode')
            ->once()
            ->with($guild)
            ->andReturn('test');
    });

    $guildController = new GuildController($mockGuildService);

    // act
    $res = $guildController->getInviteCode($guild);

    // assert
    $this->assertEquals(StatusCode::HTTP_OK, $res->status());
    $this->assertEquals([
        'invite_code' => 'test',
    ], json_decode($res->getContent(), true));
});

test('should entry into a guild', function () {
    // arrange
    $guild = Guild::factory()->create();

    $mockGuildService = $this->mock(IGuildService::class, function (MockInterface $mock) use ($guild) {
        $mock->shouldReceive('entryByInviteCode')
            ->once()
            ->with('token')
            ->andReturn(new GuildResource($guild));
    });

    $guildController = new GuildController($mockGuildService);
    $request = new Request();
    $request->merge(['invite_code' => 'token']);

    // act
    $res = $guildController->entryByInviteCode($request);

    // assert
    $this->assertEquals(StatusCode::HTTP_OK, $res->status());
    $this->assertEquals([
        'guild' => [
            'id' => $guild->id,
            'name' => $guild->name,
            'description' => $guild->description,
            'icon_url' => $guild->icon_url,
        ],
        'message' => 'Successfully entered into the guild',
    ], json_decode($res->getContent(), true));
});

test('should throw an exception when try destroy a guild that does not exist', function () {
    // arrange
    $guild = Guild::factory()->create();
    $mockGuildService = $this->mock(IGuildService::class, function (MockInterface $mock) use ($guild) {
        $mock->shouldReceive('delete')
            ->once()
            ->with($guild)
            ->andThrow(new GuildException());
    });

    $guildController = new GuildController($mockGuildService);

    // act
    $guildController->destroy($guild);
})->throws(GuildException::class);

test('should leave a guild', function () {
    // arrange
    $guild = Guild::factory()->create();
    $mockGuildService = $this->mock(IGuildService::class, function (MockInterface $mock) use ($guild) {
        $mock->shouldReceive('leaveGuild')
            ->once()
            ->with($guild);
    });

    $guildController = new GuildController($mockGuildService);

    // act
    $res = $guildController->leave($guild);

    // assert
    $this->assertEquals(StatusCode::HTTP_OK, $res->status());
    $this->assertEquals([
        'message' => 'Leave Successfully',
    ], json_decode($res->getContent(), true));
});

test('an admin cannot leave their guild', function () {
    // arrange
    $guild = Guild::factory()->create();
    $expectedErrorMessage = 'Admin cannot leave their own guild';

    $mockGuildService = $this->mock(IGuildService::class, function (MockInterface $mock) use ($expectedErrorMessage, $guild) {
        $mock->shouldReceive('leaveGuild')
            ->once()
            ->with($guild)
            ->andThrow(new GuildException($expectedErrorMessage));
    });

    $guildController = new GuildController($mockGuildService);

    // act & assert
    try {
        $guildController->leave($guild);
    } catch (GuildException $e) {
        $this->assertEquals($expectedErrorMessage, $e->getMessage());
    }
});
