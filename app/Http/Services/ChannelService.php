<?php

namespace App\Http\Services;

use App\enums\Role;
use App\Events\UserJoinedChannel;
use App\Exceptions\ChannelException;
use App\Exceptions\GuildException;
use App\Http\Resources\ChannelResource;
use App\interfaces\Services\IChannelService;
use App\Models\Channel;
use App\Models\Guild;
use App\Models\GuildMember;
use App\Models\User;

class ChannelService implements IChannelService
{
    /**
     * @throws ChannelException
     */
    public function upsert(array $data, Guild $guild, ?Channel $channel = null): ChannelResource
    {
        if (! $channel) {
            return $this->store($data, $guild);
        }

        return $this->update($data, $guild, $channel);
    }

    /**
     * @throws ChannelException
     */
    private function store(array $data, Guild $guild): ChannelResource
    {
        $this->checkPermissions($guild->id, auth()->id());

        $channel = Channel::create([
            ...$data,
            'guild_id' => $guild->id,
        ]);

        return ChannelResource::make($channel);
    }

    /**
     * @throws ChannelException
     */
    private function update(array $data, Guild $guild, Channel $channel): ChannelResource
    {
        $this->checkPermissions($guild->id, auth()->id());

        $channel->update($data);

        return ChannelResource::make($channel);
    }

    /**
     * @throws ChannelException
     */
    public function delete(Guild $guild, Channel $channel): void
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
