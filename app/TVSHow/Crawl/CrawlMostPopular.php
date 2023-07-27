<?php

namespace App\TVShow\Crawl;

use App\TVShow\RemoteData\GetRemoteMostPopularTVShow;

class CrawlMostPopular extends CrawlerAbstract
{
    public function doCrawl() {

        for ($currentPage = $this->startPage; $currentPage < ($this->startPage + $this->totalPages); $currentPage++) {
            $crawler = new GetRemoteMostPopularTVShow($currentPage);
            $searchData = $crawler->getMostPopularShows();

            if(is_null($searchData)) {
                continue;
            }

            $permalinks = $searchData->tv_shows->toCollection()->pluck('permalink')->toArray();
            $this->storeTVShows($permalinks);
            $this->totalCrawledPages++;

            // cache last crawled page
            MainCrawler::setLastPopularPage($currentPage);
        }

    }
}
