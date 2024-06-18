<?php

namespace App\Broadcasting;

use App\Models\Channel;
use App\Models\User;

class ChatChannel
{
    public function __construct()
    {
        //
    }

    public function join(User $user, Channel $channel): array|bool
    {
        return $channel->guild->members->contains($user);
    }
}
