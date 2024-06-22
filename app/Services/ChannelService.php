<?php

namespace App\Services;

use App\Enums\Role;
use App\Events\UserJoinedChannel;
use App\Exceptions\ChannelException;
use App\Exceptions\GuildException;
use App\Http\Resources\ChannelResource;
use App\Interfaces\Services\IChannelService;
use App\Models\Channel;
use App\Models\Guild;
use App\Models\GuildMember;
use App\Models\User;

class ChannelService implements IChannelService
{
    /**
     * @throws ChannelException
     */
    public function upsertChannel(array $data, Guild $guild, Channel $channel = null): ChannelResource
    {
        $this->checkPermissions($guild->id, auth()->id());

        $channel = Channel::updateOrCreate([
            'id' => $channel?->id,
            'guild_id' => $guild->id,
        ], $data);

        return ChannelResource::make($channel);
    }

    /**
     * @throws ChannelException
     */
    public function deleteChannel(Guild $guild, Channel $channel): void
    {
        $this->checkPermissions($guild->id, auth()->id());

        $channel->delete();
    }

    /**
     * @throws GuildException
     * @throws ChannelException
     */
    public function joinChannel(Guild $guild, Channel $channel): void
    {
        /** @var User $user */
        $user = auth()->user();

        if (! $guild->channels->contains($channel)) {
            throw ChannelException::channelDoesNotBelongToGuild();
        }

        if (! $guild->members->contains($user)) {
            throw GuildException::notAGuildMemberException();
        }

        event(new UserJoinedChannel($channel->id, $user));
    }

    /**
     * @throws ChannelException
     */
    private function checkPermissions(int $guild_id, int $user_id): void
    {
        $guild_member = GuildMember::where('user_id', $user_id)->where('guild_id', $guild_id)->first();
        if (! $guild_member || ($guild_member->role !== Role::Admin->value && $guild_member->role !== Role::Moderator->value)) {
            throw ChannelException::dontHaveManagerPermission();
        }
    }
}
