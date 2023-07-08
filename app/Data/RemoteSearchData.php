<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

// data of remote search result om episodate.com api
class RemoteSearchData extends Data
{

    public function __construct(
        public int $total,
        public int $page,
        public int $pages,
        #[DataCollectionOf(TVShowData::class)]
        public ?DataCollection $tv_shows,
    ) {

    }
}
