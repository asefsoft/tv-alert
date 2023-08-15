<?php

namespace Tests\Feature;

use App\Models\TVShow;
use App\TVShow\Crawling\CrawlNotRecentlyCrawledShows;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class ToBeCrawledTest extends TestCase
{

    public function test_can_crawl_to_be_crawled_shows() {
        config()->set('tvshow.crawl_min_cache_hours', 0);
        $crawler = new CrawlNotRecentlyCrawledShows();
        $crawler->setDelayBetweenRequests(0); // no delay between requests
        $crawler->doCrawl();

        self::assertEquals(20, $crawler->getTotalFoundShows());
        self::assertEquals(20, $crawler->getTotalCrawledShows());
        self::assertEquals(0, $crawler->getTotalSkippedShows());
        self::assertTrue($crawler->getTotalFoundShows() == $crawler->getTotalCrawledShows());
        self::assertEquals(1, $crawler->getTotalCrawledPages());
    }
}
