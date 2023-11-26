<?php

namespace App\Mail;

use App\Models\User;
use App\TVShow\Timeline\TimelineFormatter;
use App\TVShow\Timeline\Types\TodayTimeline;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
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
        $this->getTodayShowsOfGiveUser($user);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->user->email],
            subject: sprintf('Series Alert: Your today TV shows (%s)', now()->format('Y/m/d')),
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

    public function headers(): Headers
    {
        return new Headers(
            // add category header for mailtrap.io
            text: [
                'X-MT-Category' => 'New Episode',
            ],
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

    private function getTodayShowsOfGiveUser(User $user): void
    {
        // get today shows of give user
        $this->todayShows = $user->getRecentShows(1, 10)['today'];
        $formatter = new TimelineFormatter(new TodayTimeline());
        foreach ($this->todayShows as $tvShow) {
            $tvShow->ep_info = $formatter->getEpisodeInfo($tvShow);
        }
    }
}
