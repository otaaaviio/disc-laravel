<?php

namespace App\Console\Commands;

use App\Events\SendMessage;
use App\Models\Message;
use Illuminate\Console\Command;

class SendMessageCommand extends Command
{
    protected $signature = 'app:send-message-command';

    protected $aliases = ['send:msg'];

    protected $description = 'Command description';

    public function handle(): void
    {
        $userId = $this->ask('Write the user ID');
        $channelId = $this->ask('Write the channel ID');

        if (! is_numeric($userId) || ! is_numeric($channelId)) {
            $this->error('User ID and Channel ID must be numeric');

            return;
        }

        $message = Message::create([
            'content' => 'Hello, World!',
            'user_id' => $userId,
            'channel_id' => $channelId,
        ]);

        SendMessage::dispatch($message);

        $this->info('message send');
    }
}
