<?php

namespace App\TVShow\Timeline\Types;

use Carbon\Carbon;

class TodayTimeline extends AbstractTimelineType
{
    public function getStart(): Carbon {
        return now()->startOfDay();
    }

    public function getEnd(): Carbon {
        return now()->endOfDay();
    }

    public function getLength(): int {
        return 1;
    }

    public function getEpisodeField(): string {
        return 'next_ep';
    }

    function getType(): TimelineType {
        return TimelineType::Today;
    }
}
