<?php

namespace App\interfaces\Services;

use App\Http\Resources\MessageResource;
use App\Models\Channel;
use App\Models\Guild;
use App\Models\Message;

interface IMessageService
{
    public function sendMessage(Guild $guild, Channel $channel, array $data): MessageResource;

    public function delete(Guild $guild, Channel $channel, Message $message): void;
}
