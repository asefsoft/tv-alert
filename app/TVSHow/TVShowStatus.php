<?php

namespace App\TVSHow;

use Illuminate\Support\Str;

enum TVShowStatus : string
{
    case Ended = 'Ended';
    case Running = "Running";
    case InDevelopment = "InDevelopment";
    case CanceledEnded = "Canceled/Ended";
    case ToBeDetermined = "ToBeDetermined";
    case TBD_OnTheBubble = "Tbd/OnTheBubble";
    case NewSeries = "NewSeries";

    case Unknown = "Unknown";


    public static function fromString(mixed $value) {
        $value = Str::studly(Str::title(trim($value)));
        try{
            return static::from($value);
        }
        catch (\Throwable $e) {
            return static::Unknown;
        }
    }
}
