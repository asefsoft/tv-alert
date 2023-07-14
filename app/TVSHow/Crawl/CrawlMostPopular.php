<?php

namespace App\TVShow\Crawl;

use App\TVShow\RemoteData\GetRemoteMostPopularTVShow;

class CrawlMostPopular
{

    public function __construct(protected int $startPage = 1, protected int $totalPages = 1) {
    }

    public function doCrawl() {
        for ($page = $this->startPage; $page < ($this->startPage + $this->totalPages); $page++) {
            $crawler = new GetRemoteMostPopularTVShow();
            $searchData = $crawler->getMostPopular();
        }
    }
}
