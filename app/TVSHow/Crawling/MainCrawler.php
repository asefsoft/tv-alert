<?php

namespace App\TVShow\Crawling;

use App\Jobs\CrawlMostPopularJob;
use App\Jobs\CrawlNotRecentlyCrawledShowsJob;
use App\TVSHow\CreateOrUpdateTVShow;
use App\TVShow\RemoteData\GetRemoteTVShowInfo;

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
    public static function crawlMostPopular($totalPages = 1, ?int $startPage = null): void {
        CrawlMostPopularJob::dispatch($totalPages, $startPage);
    }

    public static function CrawlNotRecentlyCrawledShows($totalShows = 20): void {
        CrawlNotRecentlyCrawledShowsJob::dispatch($totalShows);
    }

    public static function crawlByPermalink(string $permalink): void {
        // get show data from remote
        $crawler = new GetRemoteTVShowInfo($permalink);
        $tvshow = $crawler->getTVShowInfo();
        if(!empty($tvshow)){
            // save on db
            new CreateOrUpdateTVShow($tvshow);
        }
    }


}
