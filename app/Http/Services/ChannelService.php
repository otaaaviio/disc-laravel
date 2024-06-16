<?php

namespace App\Http\Services;

use App\Events\UserJoinedChannel;
use App\Exceptions\ChannelException;
use App\Exceptions\GuildException;
use App\Http\Resources\ChannelResource;
use App\interfaces\Repositories\IChannelRepository;
use App\interfaces\Services\IChannelService;
use App\Models\Channel;
use App\Models\Guild;

class ChannelService implements IChannelService
{
    protected IChannelRepository $channelRepository;

    public function __construct(IChannelRepository $channelRepository)
    {
        $this->channelRepository = $channelRepository;
    }

    public function upsert(array $data, Guild $guild, ?Channel $channel = null): ChannelResource
    {
        if (! $channel) {
            return $this->store($data, $guild);
        }

        return $this->update($data, $guild, $channel);
    }

    private function store(array $data, Guild $guild): ChannelResource
    {
        $user_id = auth()->id();
        $channel = $this->channelRepository->create($data, $guild->id, $user_id);

        return ChannelResource::make($channel);
    }

    private function update(array $data, Guild $guild, Channel $channel): ChannelResource
    {
        $user_id = auth()->id();
        $channel = $this->channelRepository->update($data, $user_id, $guild->id, $channel);

        return ChannelResource::make($channel);
    }

    public function delete(Guild $guild, Channel $channel): void
    {
        $user_id = auth()->id();
        $this->channelRepository->delete($guild->id, $user_id, $channel);
    }

    /**
     * @throws GuildException
     * @throws ChannelException
     */
    public function joinChannel(Guild $guild, Channel $channel): void
    {
        $user = auth()->user();

        if (! $guild->channels->contains($channel)) {
            throw ChannelException::channelDoesNotBelongToGuild();
        }

        if (! $guild->members->contains($user)) {
            throw GuildException::notAGuildMemberException();
        }

        event(new UserJoinedChannel($channel, $user->name));
    }
}
