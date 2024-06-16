<?php

namespace App\interfaces\Repositories;

use App\Models\Channel;

interface IChannelRepository
{
    public function create(array $data, int $guild_id, int $user_id): Channel;

    public function update(array $data, int $user_id, int $guild_id, Channel $channel): Channel;

    public function delete(int $guild_id, int $user_id, Channel $channel): void;
}
