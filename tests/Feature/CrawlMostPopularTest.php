<?php

namespace Tests\Feature;

use App\Jobs\CrawlMostPopularJob;
use App\TVShow\Crawling\CrawlMostPopular;
use App\TVShow\Crawling\MainCrawler;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CrawlMostPopularTest extends TestCase
{
    public function test_can_crawl_most_popular(): void
    {
        // force to crawl all shows
        config()->set('tvshow.crawl_min_cache_hours', 0);
        $crawler = new CrawlMostPopular(1,1); // crawl 2 pages of populars
        $crawler->setDelayBetweenRequests(0); // no delay between requests
        $crawler->doCrawl();

        self::assertEquals(20, $crawler->getTotalFoundShows());
        self::assertEquals(20, $crawler->getTotalCrawledShows());
        self::assertTrue($crawler->getTotalFoundShows() == $crawler->getTotalCrawledShows());
        self::assertEquals(1, $crawler->getTotalCrawledPages());

        // force to NOT crawl shows and use current stored data on db
        config()->set('tvshow.crawl_min_cache_hours', 999999);
        $crawler = new CrawlMostPopular(1,1);
        $crawler->doCrawl();

        self::assertEquals(20, $crawler->getTotalFoundShows());
        self::assertEquals(0, $crawler->getTotalCrawledShows()); // no crawl
        self::assertEquals(1, $crawler->getTotalCrawledPages());
        self::assertEquals(20, $crawler->getTotalSkippedShows()); // all skipped

    }

    public function test_crawl_job_is_dispatched() {
        Queue::fake();

        MainCrawler::crawlMostPopular();

        Queue::assertPushed(CrawlMostPopularJob::class);
    }
}
