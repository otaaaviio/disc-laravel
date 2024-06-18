<?php

use App\Broadcasting\ChatChannel;
use App\Models\Channel;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('channel.{channelId}', ChatChannel::class);
