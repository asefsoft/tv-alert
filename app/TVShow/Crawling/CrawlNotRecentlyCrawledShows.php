<?php

namespace App\TVShow\Crawling;

use App\Models\TVShow;

// crawl from db, shows that not crawled recently
class CrawlNotRecentlyCrawledShows extends CrawlerAbstract
{
    public function doCrawl($total = 20)
    {

        //        for ($currentPage = $this->startPage; $currentPage < ($this->startPage + $this->totalPages); $currentPage++) {
        $shows = TVShow::getNotRecentlyCrawledShows($total);

        $this->storeTVShows($shows->pluck('permalink')->toArray());
        $this->totalCrawledPages++;
        //        }

    }
}
