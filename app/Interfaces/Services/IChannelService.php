<?php

namespace App\Interfaces\Services;

use App\Http\Resources\ChannelResource;
use App\Models\Channel;
use App\Models\Guild;

interface IChannelService
{
    public function upsertChannel(array $data, Guild $guild, ?Channel $channel = null): ChannelResource;

    public function deleteChannel(Guild $guild, Channel $channel): void;

    public function joinChannel(Guild $guild, Channel $channel): void;
}
