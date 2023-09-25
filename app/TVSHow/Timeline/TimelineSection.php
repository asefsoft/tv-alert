<?php

namespace App\TVShow\Timeline;



use App\Models\TVShow;
use App\TVShow\Timeline\Types\AbstractTimelineType;
use Illuminate\Contracts\Pagination\Paginator;

class TimelineSection
{
    public TimelineFormatter $fm;

    public function __construct(protected $title, protected Paginator $tvShows, protected AbstractTimelineType $timelineType) {
        $this->fm = new TimelineFormatter($this->timelineType);
    }

    public function getTitle() {
        return $this->title;
    }

    public function getTvShows(): Paginator {
        return $this->tvShows;
    }


}
