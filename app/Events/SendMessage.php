<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel.'.$this->message->channel_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message->content,
            'user' => $this->message->user->name,
            'send_at' => $this->message->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message';
    }
}
