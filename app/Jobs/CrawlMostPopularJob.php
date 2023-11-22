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

    public const MAX_CRAWL_PAGE_NUMBER = 1250;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected int $totalPages = 1,
        protected ?int $startPage = null,
        protected bool $onlyCrawlNewShows = false
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $startPage = is_null($this->startPage) ? $this->getNextCrawlPageNumber() : $this->startPage;
        $crawler = new CrawlMostPopular($startPage, $this->totalPages, $this->onlyCrawlNewShows);
        $crawler->doCrawl();
        dump($crawler);
    }

    // get next page number from cache
    private function getNextCrawlPageNumber(): int
    {
        $nextPage = MainCrawler::getLastPopularPage() + 1;

        // dont exceed max page number
        if ($nextPage > self::MAX_CRAWL_PAGE_NUMBER) {
            $nextPage = 1;
            MainCrawler::setLastPopularPage($nextPage);
        }

        return $nextPage;
    }
}
