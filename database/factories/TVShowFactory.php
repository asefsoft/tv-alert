<?php

namespace Database\Factories;

use App\Models\TVShow;
use App\TVShow\TVShowStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TVShowFactory extends Factory
{
    protected $model = TVShow::class;

    public function definition(): array
    {
        $fake = fake();
        $fake->addProvider(new \Xylis\FakerCinema\Provider\TvShow($fake));

        $name = fake()->unique()->tvShow();
        $startDate = $fake->dateTimeBetween('-10 years', '+1 year');

        return [
            'name' => $name,
            'permalink' => Str::slug($name),
            'description' => strLimit(fake()->overview(), 2500, ''),
            'status' => TVShowStatus::cases()[array_rand(TVShowStatus::cases())],
            'country' => $fake->countryCode(),
            'start_date' => $startDate,
            'end_date' => $fake->dateTimeBetween($startDate, '+1 year'),
            'next_ep_date' => $fake->dateTimeBetween('-2 days', '+2 days'),
            'last_ep_date' => $fake->dateTimeBetween('-2 days', '+2 days'),
            'last_check_date' => $fake->dateTimeBetween('-2 days', 'now'),
            'network' => $fake->tvNetwork(),
            'genres' => $fake->tvGenres(rand(1, 4)),
        ];
    }
}
