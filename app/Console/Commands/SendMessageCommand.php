<?php

namespace App\Console\Commands;

use App\Events\SendMessage;
use App\Models\Message;
use Illuminate\Console\Command;

class SendMessageCommand extends Command
{
    protected $signature = 'app:send-message-command';

    protected $aliases = ['send:message'];

    protected $description = 'Command description';

    public function handle(): void
    {
        $message = new Message([
            'content' => 'Hello, World!',
            'user_id' => 3,
            'channel_id' => 33,
        ]);

        $message->save();

        event(new SendMessage($message));

        $this->info('message send');
    }
}
