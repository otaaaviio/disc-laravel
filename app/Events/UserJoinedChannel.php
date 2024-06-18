<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserJoinedChannel implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected int $channel_id;
    protected User $user;

    public function __construct(int $channel_id, User $user)
    {
        $this->channel_id = $channel_id;
        $this->user = $user;
    }

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('channel.'.$this->channel_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->name,
        ];
    }

    public function broadcastAs(): string
    {
        return 'presence';
    }
}
