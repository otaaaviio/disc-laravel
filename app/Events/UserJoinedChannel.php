<?php

namespace App\Events;

use App\Models\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserJoinedChannel implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected Channel $channel;

    protected string $user_name;

    public function __construct(Channel $channel, $user_name)
    {
        $this->channel = $channel;
        $this->user_name = $user_name;
    }

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('channel.'.$this->channel->id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'user_name' => $this->user_name,
        ];
    }

    public function broadcastAs(): string
    {
        return 'presence';
    }
}
