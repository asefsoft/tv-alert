<?php

namespace App\TVShow\Timeline\Types;

use Carbon\Carbon;

class PastTimeline extends AbstractTimelineType
{
    public function getStart(): Carbon {
        // start from X days ago
        return now()->subDays($this->length)->startOfDay();
    }

    public function getEnd(): Carbon {
        // end to end of yesterday
        return now()->subDay()->endOfDay();
    }

    public function getLength(): int {
        return $this->length;
    }

    public function getEpisodeField(): string {
        return 'last_aired_ep';
    }

    function getType(): TimelineType {
        return TimelineType::Past;
    }
}
