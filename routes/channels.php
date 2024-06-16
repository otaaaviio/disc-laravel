<?php

use App\Models\Channel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('channel.{channel}', function (Channel $channel) {
    return $channel->guild->members->contains(auth()->user());
});
