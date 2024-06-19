<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        private readonly Message $message
    ) {
        //
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('channel.');
    }

    public function broadcastWith(): array
    {
        return ['message_id' => $this->message->id];
    }

    public function broadcastAs(): string
    {
        return 'message-deleted';
    }
}
