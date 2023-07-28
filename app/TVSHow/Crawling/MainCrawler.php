<?php

namespace App\TVShow\Crawling;

use App\Jobs\CrawlMostPopularJob;
use App\Jobs\CrawlToBeCrawledJob;

class MainCrawler
{
    // get page number of last crawled popular tvshows
    public static function getLastPopularPage() : int {
        return cache()->get('crawl_popular_last_page', 0);
    }

    public static function getLastPopularDate() {
        return cache()->get('crawl_popular_last_date');
    }

    // set
    public static function setLastPopularPage(int $crawledPage) {
        cache()->put('crawl_popular_last_page', $crawledPage);
        cache()->put('crawl_popular_last_date', now());
    }

    // resume crawl from last crawled page number
    public static function crawlMostPopular($totalPages = 1, ?int $startPage = null) {
        CrawlMostPopularJob::dispatch($totalPages, $startPage);
    }

    public static function crawlToBeCrawled($totalPages = 1, int $startPage = 1) {
        CrawlToBeCrawledJob::dispatch($totalPages, $startPage);
    }


}
