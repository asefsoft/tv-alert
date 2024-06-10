<?php

namespace App\Jobs;

use App\TVShow\Crawling\CrawlNotRecentlyCrawledShows;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CrawlNotRecentlyCrawledShowsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected int $totalShows = 20)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $crawler = new CrawlNotRecentlyCrawledShows();
        $crawler->doCrawl($this->totalShows);
        dump($crawler);
    }
}
