<?php

namespace Database\Factories;

use App\Models\TVShow;
use App\Models\TVShowImdbInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class TVShowImdbInfoFactory extends Factory
{
    protected $model = TVShowImdbInfo::class;

    public function definition(): array
    {
        return [
            'tv_show_id' => TVShow::factory(),
            'imdb_id' => $this->faker->unique()->numerify('#######'),  // IMDb IDs are 7 digits
            'imdb_url' => function (array $attributes) {
                return 'https://www.imdb.com/title/tt' . $attributes['imdb_id'];
            },
            'seasons' => $this->faker->numberBetween(1, 20),
            'lang' => $this->faker->randomElement(['en', 'es', 'fr', 'de']),
            'year' => $this->faker->year(),
            'yearspan' => function (array $attributes) {
                $endYear = $this->faker->numberBetween($attributes['year'], $attributes['year'] + 10);
                return ['start' => $attributes['year'], 'end' => (string)$endYear];
            },
            'endyear' => function (array $attributes) {
                return $attributes['yearspan']['end'];
            },
            'keywords' => $this->faker->words(5),
            'rating' => $this->faker->randomFloat(1, 1, 10),
            'votes' => $this->faker->numberBetween(1000, 2000000),
        ];
    }

    /**
     * Configure the factory to generate high-rated shows.
     */
    public function highRated(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => $this->faker->randomFloat(1, 8.5, 10),
                'votes' => $this->faker->numberBetween(500000, 2000000),
            ];
        });
    }

    /**
     * Configure the factory to generate recent shows.
     */
    public function recent(): self
    {
        return $this->state(function (array $attributes) {
            $year = $this->faker->numberBetween(2020, 2025);
            return [
                'year' => $year,
                'yearspan' => ['start' => $year, 'end' => 'present'],
                'endyear' => null,
            ];
        });
    }
}
