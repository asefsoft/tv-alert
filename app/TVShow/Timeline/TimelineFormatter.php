<?php

namespace App\TVShow\Timeline;

use App\Models\TVShow;
use App\TVShow\Timeline\Types\AbstractTimelineType;
use App\TVShow\Timeline\Types\TimelineType;

class TimelineFormatter
{

    public function __construct(protected AbstractTimelineType $timelineType) {
    }

    public function getEpisodeName(TVShow $tvShow) : string {
        return $tvShow->{$this->timelineType->getEpisodeField()}['name'] ?? 'No Title';
    }

    public function getEpisodeDate(TVShow $tvShow, $format = 'diffForHumans'): string {

        // last ep or next ep
        $episodeType = $this->timelineType->getEpisodeField();

        // for today we choose that field which is closer to NOW
        if($this->timelineType->getType() == TimelineType::Today) {
            $episodeType = now()->diffInHours($tvShow->last_ep_date) < now()->diffInHours($tvShow->next_ep_date) ?
                "last_ep" : "next_ep";
        }

        return $episodeType == 'next_ep' ?
            $tvShow->getNextEpisodeDateText($format) :
            $tvShow->getLastEpisodeDateText($format);
    }

    public function getEpisodeInfo(TVShow $tvShow): string {
            $episode = $tvShow->{$this->timelineType->getEpisodeField()};
            return sprintf("Season: %s, Episode: %s", $episode['season'] ?? 'N/A', $episode['episode'] ?? 'N/A');
        }

    public function getSectionCssClasses(): string {
            return match($this->timelineType->getType()) {
            TimelineType::Past   => "bg-gray-200",
            TimelineType::Today  => "bg-lime-200",
            TimelineType::Future => "bg-yellow-200",
        };
    }
    public function getSectionMainColor(): string {
        return match($this->timelineType->getType()) {
            TimelineType::Past   => "gray",
            TimelineType::Today  => "lime",
            TimelineType::Future => "yellow",
        };
    }

    public function getSectionTimePeriod(): string {

        $format ='(%s - %s)';

        if($this->timelineType->getType() == TimelineType::Today)
            $format = "%s";

        return sprintf($format,
            $this->timelineType->getStart()->format("Y/m/d"),
            $this->timelineType->getEnd()->format("Y/m/d")
        );
    }
}
