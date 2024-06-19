<?php

use App\Broadcasting\ChatChannel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('channel.{channelId}', ChatChannel::class);
