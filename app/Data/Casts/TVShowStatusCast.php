<?php

namespace App\Data\Casts;

use App\TVSHow\TVShowStatus;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

// Cast Status from a string into a real TVShowStatus enum value
class TVShowStatusCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $context): TVShowStatus
    {
        return TVShowStatus::fromString($value);
    }
}
