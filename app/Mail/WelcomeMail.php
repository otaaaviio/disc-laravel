<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        protected readonly User $user
    ) {
        $this->queue = 'database';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome Mail',
        );
    }

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'X-Custom-Header' => 'Welcome Mail',
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'welcome-mail',
            with: [
                'user_name' => $this->user->name,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
