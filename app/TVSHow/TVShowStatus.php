<?php

namespace App\TVSHow;

use Illuminate\Support\Str;

enum TVShowStatus : string
{
    case Ended = 'Ended';
    case Running = "Running";
    case InDevelopment = "In Development";
    case CanceledEnded = "Canceled/Ended";
    case ToBeDetermined = "To Be Determined";
    case TBD_OnTheBubble = "TBD/On The Bubble";
    case NewSeries = "New Series";

    case Unknown = "Unknown";


    public static function fromString(mixed $value) {
        try{
            return static::from($value);
        }
        catch (\Throwable $e) {

            $value = Str::title(trim($value));

            try{
                return static::from($value);
            }
            catch (\Throwable $e) {
                return static::Unknown;
            }
        }
    }
}
