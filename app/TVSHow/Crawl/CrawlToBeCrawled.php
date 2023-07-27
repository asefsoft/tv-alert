<?php

namespace App\TVShow\Crawl;

use App\Models\TVShow;
use App\TVShow\RemoteData\GetRemoteMostPopularTVShow;

// crawl from db, shows that not crawled recently or the air date is close
class CrawlToBeCrawled extends CrawlerAbstract
{
    public function doCrawl() {

        for ($currentPage = $this->startPage; $currentPage < ($this->startPage + $this->totalPages); $currentPage++) {
            $shows = TVShow::getToBeCrawledShows($currentPage);

            $this->storeTVShows($shows->pluck('permalink')->toArray());
            $this->totalCrawledPages++;
        }

    }
}
