<?php

namespace App\TVShow\Crawling;

use App\Models\TVShow;
use App\TVShow\CreateOrUpdateTVShow;
use App\TVShow\RemoteData\GetRemoteTVShowInfo;

abstract class CrawlerAbstract
{
    protected int $totalFoundShows = 0;

    protected int $totalCrawledShows = 0;

    protected int $totalCrawledPages = 0;

    protected int $totalSkippedShows = 0;

    protected int $totalInvalidShowData = 0;

    protected int $delayBetweenRequests = 2;

    protected int $maxProcessedShows = -1;

    public function __construct(
        protected int $startPage = 1,
        protected int $totalPages = 1,
        protected bool $onlyCrawlNewShows = false
    ) {
    }

    abstract public function doCrawl($total = 20);

    // store on db
    public function setDelayBetweenRequests(int $delayBetweenRequests): void
    {
        $this->delayBetweenRequests = $delayBetweenRequests;
    }

    public function setMaxProcessedShows(int $maxProcessedShows): void
    {
        $this->maxProcessedShows = $maxProcessedShows;
    }

    public function getTotalFoundShows(): int
    {
        return $this->totalFoundShows;
    }

    public function getTotalCrawledShows(): int
    {
        return $this->totalCrawledShows;
    }

    public function getTotalCrawledPages(): int
    {
        return $this->totalCrawledPages;
    }

    public function getTotalSkippedShows(): int
    {
        return $this->totalSkippedShows;
    }

    public function getTotalInvalidShowData(): int
    {
        return $this->totalInvalidShowData;
    }

    protected function storeTVShows(array $showPermalinks): void
    {
        foreach ($showPermalinks as $permalink) {
            $this->totalFoundShows++;

            // dont crawl too much
            if ($this->maxProcessedShows > 0 && $this->totalFoundShows > $this->maxProcessedShows) {
                // we did not use break here to allow updating $this->totalFoundShows
                continue;
            }

            // dont crawl too often
            if (! $this->shouldCrawl($permalink)) {
                $this->totalSkippedShows++;

                continue;
            }

            sleep($this->delayBetweenRequests);

            // get full info of tvshow from remote api
            $remote = new GetRemoteTVShowInfo($permalink);
            $tvshowInfo = $remote->getTVShowInfo();

            $this->totalCrawledShows++;

            // invalid?
            if (is_null($tvshowInfo)) {
                $this->totalInvalidShowData++;

                continue;
            }

            // save on db
            new CreateOrUpdateTVShow($tvshowInfo);
        }
    }

    // should crawl or not
    protected function shouldCrawl($permalink): bool
    {
        if ($this->onlyCrawlNewShows) {
            return ! TVShow::isShowExist($permalink);
        }

        return TVShow::shouldShowBeCrawled($permalink);
    }
}
