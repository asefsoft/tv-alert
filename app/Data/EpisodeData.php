<?php

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

class EpisodeData extends Data
{
    public function __construct(
        public int $season,
        public int $episode,
        public string $name,
        public Carbon $air_date,
    ) {}
}
