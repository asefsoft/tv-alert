<?php

namespace App\Jobs;

use App\TVShow\Crawling\CrawlToBeCrawled;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CrawlToBeCrawledJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected int $totalPages = 1, protected int $startPage = 1)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $crawler = new CrawlToBeCrawled($this->startPage, $this->totalPages);
        $crawler->doCrawl();
    }
}
