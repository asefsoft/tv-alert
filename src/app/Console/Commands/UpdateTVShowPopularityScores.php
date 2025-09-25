<?php

namespace App\Console\Commands;

use App\Models\TVShowImdbInfo;
use Illuminate\Console\Command;

class UpdateTVShowPopularityScores extends Command
{
    protected $signature = 'tvshow:update-popularity';
    protected $description = 'Update popularity scores for all TV shows';

    public function handle()
    {
        $this->info('Starting to update TV show popularity scores...');

        $count = TVShowImdbInfo::count();
        if($count == 0) {
            $this->info('No TV shows found to update.');
            return;
        }

        $this->info("There is $count TV shows to be updated.");

        $bar = $this->output->createProgressBar($count);

        TVShowImdbInfo::chunk(100, function ($shows) use ($bar) {
            foreach ($shows as $show) {
                $show->updatePopularityScore();
                $show->save();
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info('All popularity scores have been updated!');
    }
}
