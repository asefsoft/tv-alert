<?php

namespace App\TVShow\Timeline;

use App\Models\TVShow;
use App\Models\User;
use App\TVShow\Timeline\Types\FutureTimeline;
use App\TVShow\Timeline\Types\PastTimeline;
use App\TVShow\Timeline\Types\TimelineInfo;
use App\TVShow\Timeline\Types\TodayTimeline;
use Illuminate\Support\Collection;

class Timeline
{
    public const MaxDisplayShows = 20;
    protected Collection $sections;

    public function __construct(protected TimelineInfo $timelineInfo)
    {
        $this->buildSections();
    }

    public static function makeTimeline(int $duration): Timeline
    {
        return new Timeline(new TimelineInfo(new PastTimeline($duration), new TodayTimeline(), new FutureTimeline($duration)));
    }

    public function buildSections()
    {
        $this->sections = collect();

        // auth user subscribed shows
        $userTvShows = User::getAuthUserSubscribedShows();

        if (count($userTvShows) === 0) {
            $userTvShows = [-999]; // an invalid tvshow id
        }

        if ($this->timelineInfo->hasPastTimeline()) {
            $totalDays = -1 * $this->timelineInfo->getPastTimeline()->getLength();
            $tvShows = TVShow::getShowsByAirDateDistance($totalDays, 1, self::MaxDisplayShows, $userTvShows);
            $this->sections->add(new TimelineSection('Past Episodes', $tvShows, $this->timelineInfo->getPastTimeline()));
        }

        if ($this->timelineInfo->hasTodayTimeline()) {
            $tvShows = TVShow::getShowsByAirDateDistance(0, 1, self::MaxDisplayShows, $userTvShows);
            $this->sections->add(new TimelineSection('Today Episodes', $tvShows, $this->timelineInfo->getTodayTimeline()));
        }

        if ($this->timelineInfo->hasFutureTimeline()) {
            $tvShows = TVShow::getShowsByAirDateDistance($this->timelineInfo->getFutureTimeline()->getLength(), 1, self::MaxDisplayShows, $userTvShows);
            $this->sections->add(new TimelineSection('Future Episodes', $tvShows, $this->timelineInfo->getFutureTimeline()));
        }
    }

    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function getInfo(): TimelineInfo
    {
        return $this->timelineInfo;
    }
}
