<?php

namespace App\TVShow\Timeline\Types;

abstract class AbstractTimelineType implements HasDuration, HasEpisodeField
{
    protected int $length = 1;
    public function __construct(int $length = 1)
    {
        if ($length < 1) {
            $length = 1;
        }
        $this->length = $length;
    }

    abstract public function getType(): TimelineType;
}
