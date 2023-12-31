<?php

namespace App\TVShow\Timeline\Types;

use Carbon\Carbon;

class FutureTimeline extends Timeline
{
    public function getStart(): Carbon
    {
        // start from beginning of tomorrow
        return now()->addDay()->startOfDay();
    }

    public function getEnd(): Carbon
    {
        return now()->addDays($this->length)->endOfDay();
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getEpisodeField(): string
    {
        return 'next_ep';
    }

    public function getType(): TimelineType
    {
        return TimelineType::Future;
    }
}
