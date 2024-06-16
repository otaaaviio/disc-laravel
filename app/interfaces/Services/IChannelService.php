<?php

namespace App\interfaces\Services;

use App\Http\Resources\ChannelResource;
use App\Models\Channel;
use App\Models\Guild;

interface IChannelService
{
    public function upsert(array $data, Guild $guild, ?Channel $channel = null): ChannelResource;

    public function delete(Guild $guild, Channel $channel): void;
}
