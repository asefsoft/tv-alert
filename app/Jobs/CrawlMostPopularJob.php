<?php

namespace App\Jobs;

use App\TVShow\Crawling\CrawlMostPopular;
use App\TVShow\Crawling\MainCrawler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CrawlMostPopularJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected int $totalPages = 1, protected ?int $startPage = null)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $startPage = is_null($this->startPage) ? MainCrawler::getLastPopularPage() + 1 : $this->startPage;
        $crawler = new CrawlMostPopular($startPage, $this->totalPages);
        $crawler->doCrawl();
    }
}
