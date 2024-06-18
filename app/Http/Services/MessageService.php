<?php

namespace App\Http\Services;

use App\Events\MessageDeleted;
use App\Events\SendMessage;
use App\Exceptions\ChannelException;
use App\Exceptions\MessageException;
use App\Http\Resources\MessageResource;
use App\interfaces\Services\IMessageService;
use App\Models\Channel;
use App\Models\Guild;
use App\Models\Message;

class MessageService implements IMessageService
{
    /**
     * @throws ChannelException
     */
    public function sendMessage(Guild $guild, Channel $channel, array $data): MessageResource
    {
        $this->verifyGuildChannel($guild, $channel);

        $payload = [
            ...$data,
            'user_id' => auth()->id(),
        ];

        /** @var Message $message */
        $message = $channel->messages()->create($payload);

        event(new SendMessage($message));

        return MessageResource::make($message);
    }

    /**
     * @throws ChannelException
     * @throws MessageException
     */
    public function delete(Guild $guild, Channel $channel, Message $message): void
    {
        $user_id = auth()->id();

        $this->verifyGuildChannel($guild, $channel);

        if ($message->user_id !== $user_id) {
            throw MessageException::dontHavePermissionToDeleteMessage();
        }

        event(new MessageDeleted($message));

        $message->delete();
    }

    /**
     * @throws ChannelException
     */
    private function verifyGuildChannel(Guild $guild, Channel $channel): void
    {
        if (! $guild->channels->contains($channel)) {
            throw ChannelException::channelDoesNotBelongToGuild();
        }
    }
}
