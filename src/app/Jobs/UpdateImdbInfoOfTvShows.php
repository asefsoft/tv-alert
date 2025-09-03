<?php

namespace App\Jobs;

use App\TVShow\UpdateTVShowsImdbInfo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateImdbInfoOfTvShows implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $t = now();
        $updater = new UpdateTVShowsImdbInfo(25);
        $stats = $updater->update();
        dump($stats);
        Log::info(sprintf("UpdateTVShowsImdbInfo Done in %s\n%s" , $t->longAbsoluteDiffForHumans(), json_encode($stats, JSON_PRETTY_PRINT)));
    }
}
