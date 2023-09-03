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
         $schedule->call(function (){
             $t = now();
             echo $t, "\n";
             MainCrawler::CrawlNotRecentlyCrawledShows(70);
             echo now(), "\n";
             echo $t->longAbsoluteDiffForHumans(), "\n";;
         })
             ->name("crawling not recently crawled shows every 5 min")
             ->everyFiveMinutes();

//    $schedule->call(function (){
//             echo 'hi', now();
//         })->name("hiiiii")
//             ->everyMinute();
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
