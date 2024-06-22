<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\ChannelController;
use App\Interfaces\Services\IChannelService;
use App\Models\Channel;
use App\Models\Guild;
use Illuminate\Foundation\Testing\TestCase;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Response as StatusCode;

uses(TestCase::class);

uses()->group('ChannelController Test');

test('test delete', function () {
    $channel = Channel::factory()->make();
    $guild = Guild::factory()->make();
    $mockChannelService = $this->mock(IChannelService::class, function (MockInterface $mock) use ($channel, $guild) {
        $mock->shouldReceive('deleteChannel')->once()->with($guild, $channel);
    });

    $channelController = new ChannelController($mockChannelService);

    $res = $channelController->destroy($guild, $channel);

    $this->assertEquals(StatusCode::HTTP_OK, $res->status());
    $this->assertEquals([
        'message' => 'Channel successfully deleted',
    ], json_decode($res->getContent(), true));
});
