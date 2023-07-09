<?php

namespace App\Data;

use App\Data\Casts\TVShowStatusCast;
use App\TVSHow\TVShowStatus;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class TVShowData extends Data
{
    public function __construct(
        public string $name,
        public string $permalink,
        public ?string $description,
//
//        #[WithTransformer(TVShowStatusTransformer::class, 444)]
        #[WithCast(TVShowStatusCast::class)]
        public TVShowStatus $status,
        public string $country,
        public string $network,
        #[MapInputName('image_thumbnail_path')]
        public ?string $thumb_url,
        #[MapInputName('image_path')]
        public ?string $image_url,
        public ?Carbon $start_date,
        public ?Carbon $end_date,
        public ?Carbon $next_ep_date,
        public ?EpisodeData $last_aired_ep,
        #[MapInputName('countdown')]
        public ?EpisodeData $next_ep,
        public ?array $genres,
        public ?array $pictures,
        #[DataCollectionOf(EpisodeData::class)]
        public ?DataCollection $episodes,

    ) {}

    // do some preparation before parsing data
    public static function prepareForPipeline(Collection $properties) : Collection
    {
        // we can not have a date with empty string so we convert it to null
        if($properties['end_date'] === "")
            $properties->put('end_date', null);

        return $properties;
    }
}
