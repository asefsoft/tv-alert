<?php

namespace App\Console;

use App\TVShow\Crawling\MainCrawler;
use App\TVShow\EmailSubscriptions\EmailSubscriptionManager;
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
        // crawl not-recently crawled shows
        $schedule->call(function () {
            $t = now();
            echo "\n", $t, "\n";
            MainCrawler::crawlNotRecentlyCrawledShows(70);
            echo now(), "\n";
            echo 'Done in ', $t->longAbsoluteDiffForHumans(), "\n";
        })
            ->name('crawling not recently crawled shows every 5 min')
            ->everyFiveMinutes();

        // crawl most popular shows to find NEW tvshows
        $schedule->call(function () {
            $t = now();
            echo "\n", $t, "\n";
            MainCrawler::crawlMostPopular(totalPages: 5, startPage: null, onlyCrawlNewShows: true);
            echo now(), "\n";
            echo 'Done in ', $t->longAbsoluteDiffForHumans(), "\n";
        })
            ->name('crawling most popular shows every 4 min')
            ->everyFourMinutes();

        // add email subscription records for today
        // run once a day
        $schedule->call(function () {
            $t = now();
            echo "\n", $t, "\n";
            $email = new EmailSubscriptionManager();
            $email->addTodayEmailSubscriptionRecords();
            echo now(), "\n";
        })
            ->name('adding email subscription records for today')
            ->dailyAt('08:00');

        // send email subscriptions
        $schedule->call(function () {
            $t = now();
            echo "\n", $t, "\n";
            // send 10 email everytime this called until it ends
            $email = new EmailSubscriptionManager();
            $email->sendEmailSubscriptions();
            echo now(), "\n";
        })
            ->name('sending email subscriptions')
            ->hourly();
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
