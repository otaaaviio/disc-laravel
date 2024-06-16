<?php

namespace App\Http\Repositories;

use App\enums\Role;
use App\Exceptions\ChannelException;
use App\interfaces\Repositories\IChannelRepository;
use App\Models\Channel;
use App\Models\GuildMember;

class ChannelRepository implements IChannelRepository
{
    /**
     * @throws ChannelException
     */
    public function create(array $data, int $guild_id, int $user_id): Channel
    {
        $this->checkPermissions($guild_id, $user_id);

        return Channel::create([
            ...$data,
            'guild_id' => $guild_id,
        ]);
    }

    /**
     * @throws ChannelException
     */
    public function update(array $data, int $user_id, int $guild_id, Channel $channel): Channel
    {
        $this->checkPermissions($guild_id, $user_id);

        $channel->update($data);

        return $channel;
    }

    /**
     * @throws ChannelException
     */
    public function delete(int $guild_id, int $user_id, Channel $channel): void
    {
        $this->checkPermissions($guild_id, $user_id);

        $channel->delete();
    }

    /**
     * @throws ChannelException
     */
    private function checkPermissions(int $guild_id, int $user_id): void
    {
        $guild_member = GuildMember::where('user_id', $user_id)->where('guild_id', $guild_id)->first();
        if (!$guild_member || ($guild_member->role !== Role::Admin->value && $guild_member->role !== Role::Moderator->value))
            throw ChannelException::dontHaveManagerPermission();
    }
}
