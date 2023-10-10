<?php

namespace App\Console;

use App\TVShow\Crawling\MainCrawler;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // main crawling task

        // crawl not recently crawled shows
        $schedule->call(function () {
            $t = now();
            echo "\n", $t, "\n";
            MainCrawler::crawlNotRecentlyCrawledShows(70);
            echo now(), "\n";
            echo "Done in ", $t->longAbsoluteDiffForHumans(), "\n";
        })
            ->name('crawling not recently crawled shows every 5 min')
            ->everyFiveMinutes();

        // crawl most popular shows to find NEW tvshows
        $schedule->call(function () {
            $t = now();
            echo "\n", $t, "\n";
            MainCrawler::crawlMostPopular(totalPages: 5, startPage: null, onlyCrawlNewShows: true);
            echo now(), "\n";
            echo "Done in ", $t->longAbsoluteDiffForHumans(), "\n";
        })
            ->name('crawling most popular shows every 4 min')
            ->everyFourMinutes();

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
