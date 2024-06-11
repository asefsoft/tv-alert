<?php

namespace App\Events;

use App\Models\TVShow;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TVShowCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public TVShow $TVShow)
    {
        $isTesting = isTesting() ? '<TESTING ENV> ' : '';
        logMe('new_tv_series_found', sprintf(
            '%sShow: %s, Start Date: %s, Country: %s, Status: %s, ID: %s',
            $isTesting,
            $this->TVShow->name,
            $this->TVShow->start_date?->format('Y/m'),
            $this->TVShow->country,
            $this->TVShow->status,
            $this->TVShow->id
        ), true, false);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
