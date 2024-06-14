<?php

namespace App\TVShow\Timeline\Types;

use Carbon\Carbon;

interface HasDuration
{
    public function __construct(int $length = 1);

    public function getLength(): int;
    public function getStart(): Carbon;
    public function getEnd(): Carbon;
}
