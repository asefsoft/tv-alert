<?php

namespace App\Data;

use App\Data\Casts\TVShowStatusCast;
use App\TVSHow\TVShowStatus;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Str;

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
        #[Max(30)]
        public ?string $network,
        #[MapInputName('image_thumbnail_path')]
        public ?string $thumb_url,
        #[MapInputName('image_path')]
        public ?string                  $image_url,
        public ?Carbon                  $start_date,
        public ?Carbon                  $end_date,
        public ?Carbon                  $next_ep_date,
        public ?EpisodeData             $last_aired_ep,
        #[MapInputName('countdown')]
        public ?EpisodeData             $next_ep,
        public ?array                   $genres,
        public ?array                   $pictures,
        #[DataCollectionOf(EpisodeData::class)]
        public DataCollection|Lazy|null $episodes,

    ) {}

    // do some preparation before parsing data
    public static function prepareForPipeline(Collection $properties) : Collection
    {
        // we can not have a date with empty string so we convert it to null
        if($properties->has('end_date') && $properties['end_date'] === "")
            $properties->put('end_date', null);

        if($properties->has('start_date') && $properties['start_date'] === "")
            $properties->put('start_date', null);

        if($properties->has('description') && Str::length($properties['description']) > 2500)
            $properties->put('description', substr($properties['description'], 0, 2500));

        return $properties;
    }

//    public static function fromModel( $post)
//    {
//        return new self(
//            Lazy::create(fn() => $post->title),
//            Lazy::create(fn() => $post->content),
//            Lazy::create(fn() => $post->status),
//            Lazy::create(fn() => $post->image),
//            Lazy::create(fn() => $post->published_at)
//        );
//    }
}
