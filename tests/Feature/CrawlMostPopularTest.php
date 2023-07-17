<?php

namespace Tests\Feature;

use App\TVShow\Crawl\CrawlMostPopular;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CrawlMostPopularTest extends TestCase
{
    public function test_can_crawl_most_popular(): void
    {
        // force to crawl all shows
        config()->set('tvshow.crawl_min_cache_hours', 0);
        $crawler = new CrawlMostPopular(1,2); // crawl 2 pages of populars
        $crawler->doCrawl();

        self::assertEquals(40, $crawler->getTotalFoundShows()); // 2 pages each 20 shows = 40
        self::assertEquals(40, $crawler->getTotalCrawledShows());
        self::assertTrue($crawler->getTotalFoundShows() == $crawler->getTotalCrawledShows());
        self::assertEquals(2, $crawler->getTotalCrawledPages());
        self::assertEquals(0, $crawler->getTotalInvalidShowData());

        // force to NOT crawl shows and use current stored data on db
        config()->set('tvshow.crawl_min_cache_hours', 999999);
        $crawler = new CrawlMostPopular(1,1);
        $crawler->doCrawl();

        self::assertEquals(20, $crawler->getTotalFoundShows());
        self::assertEquals(0, $crawler->getTotalCrawledShows()); // no crawl
        self::assertEquals(1, $crawler->getTotalCrawledPages());
        self::assertEquals(20, $crawler->getTotalSkippedShows()); // all skipped
        self::assertEquals(0, $crawler->getTotalInvalidShowData());

    }
}