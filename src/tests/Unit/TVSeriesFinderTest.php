<?php
namespace Tests\Unit;

use App\TVShow\TVShowImdbFinder;
use PHPUnit\Framework\TestCase;

class TVSeriesFinderTest extends TestCase
{
    /** @test */
    public function it_finds_a_real_tv_series_by_title()
    {
        $finder = new TVShowImdbFinder();
        $title = 'Breaking Bad'; // Known TV series
        $result = $finder->findSeries($title);

        $this->assertTrue($result['found'], 'TV series should be found');
        $this->assertTrue($result['is_tv_series'], 'Should be a TV series');
        $this->assertEquals('Breaking Bad', $result['matched_title']);
        $this->assertNotEmpty($result['imdb_id']);
    }
}
