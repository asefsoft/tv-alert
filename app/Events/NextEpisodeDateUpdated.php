<?php

namespace App\Events;

use App\Models\TVShow;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NextEpisodeDateUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public TVShow | null $TVShow,
        public CarbonImmutable | null $oldDate,
        public Carbon | null  $newDate)
    {
        //
        logMe("new_ep_date_changes.log", sprintf("Show: %s, Old: %s, New: %s, Diff: %s",
            $this->TVShow?->name, $this->oldDate ?? "N/A", $this->newDate,
            $this->newDate->diffForHumans($this->oldDate)
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