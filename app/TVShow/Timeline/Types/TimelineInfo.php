<?php

namespace App\TVShow\Timeline\Types;

class TimelineInfo
{
    public function __construct(
        protected PastTimeline   | null $pastTimeline,
        protected TodayTimeline  | null $todayTimeline = null,
        protected FutureTimeline | null $futureTimeline = null,
    ) {
    }

    public function hasPastTimeline(): bool
    {
        return ! is_null($this->pastTimeline);
    }

    public function hasTodayTimeline(): bool
    {
        return ! is_null($this->todayTimeline);
    }
    public function hasFutureTimeline(): bool
    {
        return ! is_null($this->futureTimeline);
    }

    public function getPastTimeline(): ?PastTimeline
    {
        return $this->pastTimeline;
    }

    public function getTodayTimeline(): ?TodayTimeline
    {
        return $this->todayTimeline;
    }

    public function getFutureTimeline(): ?FutureTimeline
    {
        return $this->futureTimeline;
    }
}
