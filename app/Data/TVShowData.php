<?php

namespace App\Data;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class TVShowData extends Data
{
    public function __construct(
      //
        public string $name,
//        public string $permalink,
//        public string $description,
//        #[MapInputName('record_company')]
//        public string $status,
//        #[Date]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'y-m-d')]
        public Carbon $start_date,
        public ?object $last_aired_ep

    ) {}
}
