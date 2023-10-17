<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TVShowsUpdatesNotif extends Mailable
{
    use Queueable, SerializesModels;

    public $todayShows;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $user)
    {
        // get today shows of give user
        $this->todayShows = $user->getRecentShows(1, 10)['today'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: sprintf('Series Alert: Your today TV shows (%s)', now()->format("Y/m/d")),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.today_tvshows',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
