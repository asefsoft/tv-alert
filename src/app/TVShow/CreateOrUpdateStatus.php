<?php

namespace App\TVShow;

enum CreateOrUpdateStatus
{
    case Created;
    case Updated;
    case InvalidData;
}
