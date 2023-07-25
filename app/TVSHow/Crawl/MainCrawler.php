<?php

namespace App\TVShow\Crawl;

use App\Jobs\CrawlMostPopularJob;

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


}
