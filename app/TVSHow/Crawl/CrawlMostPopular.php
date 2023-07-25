<?php

namespace App\TVShow\Crawl;

use App\Data\SearchTVShowData;
use App\Data\TVShowData;
use App\Models\TVShow;
use App\TVSHow\CreateOrUpdateTVShow;
use App\TVShow\RemoteData\GetRemoteMostPopularTVShow;
use App\TVShow\RemoteData\GetRemoteTVShowInfo;

class CrawlMostPopular
{
    private int $totalFoundShows = 0;
    private int $totalCrawledShows = 0;

    private int $totalCrawledPages = 0;
    private int $totalSkippedShows = 0;
    private int $totalInvalidShowData = 0;

    public function __construct(protected int $startPage = 1, protected int $totalPages = 1) {
    }

    public function doCrawl() {

        for ($currentPage = $this->startPage; $currentPage < ($this->startPage + $this->totalPages); $currentPage++) {
            $crawler = new GetRemoteMostPopularTVShow($currentPage);
            $searchData = $crawler->getMostPopular();
            $this->storeTVShows($searchData);
            $this->totalCrawledPages++;

            // cache last crawled page
            MainCrawler::setLastPopularPage($currentPage);
        }

    }

    // store on db
    protected function storeTVShows(SearchTVShowData $searchTVShowData){

        /** @var TVShowData $tvshow */
        foreach ($searchTVShowData->tv_shows as $tvshow) {

            $this->totalFoundShows++;

            // dont crawl too often
            if(!TVShow::shouldShowBeCrawled($tvshow->permalink)) {
                $this->totalSkippedShows++;
                continue;
            }

            // get full info of tvshow from remote api
            $remote = new GetRemoteTVShowInfo($tvshow->permalink);
            $tvshowInfo = $remote->getTVShowInfo();

            // invalid?
            if(is_null($tvshow)) {
                $this->totalInvalidShowData++;
                continue;
            }

            $this->totalCrawledShows++;

            // save on db
            $creator = new CreateOrUpdateTVShow($tvshowInfo);
        }
    }

    public function getTotalFoundShows(): int {
        return $this->totalFoundShows;
    }

    public function getTotalCrawledShows(): int {
        return $this->totalCrawledShows;
    }

    public function getTotalCrawledPages(): int {
        return $this->totalCrawledPages;
    }

    public function getTotalSkippedShows(): int {
        return $this->totalSkippedShows;
    }

    public function getTotalInvalidShowData(): int {
        return $this->totalInvalidShowData;
    }


}
