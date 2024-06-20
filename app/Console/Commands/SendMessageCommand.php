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
        $message = new Message([
            'content' => 'Hello, World 123123!',
            'user_id' => 3,
            'channel_id' => 97,
        ]);

        $message->save();

        SendMessage::dispatch($message);

        $this->info('message send');
    }
}
