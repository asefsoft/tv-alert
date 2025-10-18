<?php

namespace Database\Seeders;

use App\Models\TVShow;
use App\Models\TVShowImdbInfo;
use App\Models\User;
use Illuminate\Database\Seeder;

class TVShowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public const TOTAL_TVSHOWS_SEED = 80;

    public function run(): void
    {
        if(isTesting()) {
            TVShow::truncate();
        }

        TVShow::factory(self::TOTAL_TVSHOWS_SEED)->create()->each(function (TVShow $tvShow) {
            if($tvShow->has_imdb_info) {
                TVShowImdbInfo::factory()->create(['tv_show_id' => $tvShow->id]);
            }
        });
    }
}
