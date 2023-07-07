<?php

namespace App\Data\Transformers;

use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Transformers\Transformer;

class TVShowStatusTransformer implements Transformer
{

    public function __construct() {
        $a=1;
    }

    public function transform(DataProperty $property, mixed $value): mixed
    {

        return strtolower($value);
    }
}
