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
use Illuminate\Support\Facades\Gate;

class ChannelService implements IChannelService
{
    /**
     * @throws ChannelException
     */
    public function upsertChannel(array $data, Guild $guild, Channel $channel = null): ChannelResource
    {
        if(!Gate::authorize('manageChannels', $guild))
            throw ChannelException::dontHaveManagerPermission();

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
        if(!Gate::authorize('manageChannels', $guild))
            throw ChannelException::dontHaveManagerPermission();

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
}
