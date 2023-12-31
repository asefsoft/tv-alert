<?php

namespace App\TVShow\Timeline;

use App\TVShow\Timeline\Types\Timeline;
use Illuminate\Contracts\Pagination\Paginator;

class TimelineSection
{
    public TimelineFormatter $fm;

    public function __construct(protected string $title, protected Paginator $tvShows, protected Timeline $timelineType)
    {
        $this->fm = new TimelineFormatter($this->timelineType);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getTvShows(): Paginator
    {
        return $this->tvShows;
    }
}
