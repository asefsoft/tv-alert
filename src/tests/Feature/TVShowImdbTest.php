<?php

namespace Tests\Feature;

use App\Models\TVShow;
use App\Models\TVShowImdbInfo;
use App\Tools\TVShowImdbFinder;
use Tests\TestCase;
use Mockery;
use Illuminate\Support\Str;

class TVShowImdbTest extends TestCase
{
    /** @test */
    public function it_can_update_imdb_info_for_show()
    {
        // Create a TV show
        $tvShow = TVShow::factory()->createOne([
            'name' => 'Breaking Bad',
            'permalink' => 'breaking-bad-' . Str::random(15),
            'has_imdb_info' => false,
            'last_imdb_check_date' => null,
        ]);

        // Mock the IMDb finder
        $mockFinder = Mockery::mock(TVShowImdbFinder::class);
        $mockFinder->shouldReceive('findSeries')
            ->once()
            ->with('Breaking Bad')
            ->andReturn([
                'found' => true,
                'is_tv_series' => true,
                'imdb_id' => '0903747',
                'imdb_url' => 'https://www.imdb.com/title/tt0903747',
                'seasons' => 5,
                'lang' => 'en',
                'year' => 2008,
                'yearspan' => ["start"=>2008,"end"=>"2013"],
                'endyear' => 2013,
                'keywords' => ['crime', 'drama', 'thriller'],
                'rating' => 9.5,
                'votes' => 1800000,
            ]);

        // Replace the real finder with our mock
        $this->instance(TVShowImdbFinder::class, $mockFinder);
        $tvShow->setImdbFinder($mockFinder);

        // Update IMDb info
        $imdbInfo = $tvShow->updateImdbInfo();

        // Assert TV show fields were updated
        $tvShow->refresh();
        $this->assertTrue($tvShow->has_imdb_info);
        $this->assertNotNull($tvShow->last_imdb_check_date);
        $this->assertLessThanOrEqual(now(), $tvShow->last_imdb_check_date);

        // Assert IMDb info was created and saved correctly
        $this->assertInstanceOf(TVShowImdbInfo::class, $imdbInfo);
        $this->assertEquals($tvShow->id, $imdbInfo->tv_show_id);

        // Check all IMDb fields are saved correctly
        $this->assertEquals('0903747', $imdbInfo->imdb_id);
        $this->assertEquals('https://www.imdb.com/title/tt0903747', $imdbInfo->imdb_url);
        $this->assertEquals(5, $imdbInfo->seasons);
//        $this->assertEquals('en', $imdbInfo->lang);
        $this->assertEquals(2008, $imdbInfo->year);
        $this->assertEquals(["start"=>2008,"end"=>"2013"], $imdbInfo->yearspan);
        $this->assertEquals(2013, $imdbInfo->endyear);

        // Check array and numeric fields
        $this->assertIsArray($imdbInfo->keywords);
        $this->assertContains('crime', $imdbInfo->keywords);
        $this->assertContains('drama', $imdbInfo->keywords);
        $this->assertContains('thriller', $imdbInfo->keywords);

        // Check rating and votes
        $this->assertIsFloat($imdbInfo->rating);
        $this->assertEquals(9.5, $imdbInfo->rating);
        $this->assertEquals(1800000, $imdbInfo->votes);

        // Verify persistence
        $this->assertDatabaseHas('tv_show_imdb_info', [
            'tv_show_id' => $tvShow->id,
            'imdb_id' => '0903747',
            'seasons' => 5,
            'year' => 2008
        ]);
    }

    /** @test */
    public function it_handles_show_not_found_on_imdb()
    {
        // Create a TV show
        $tvShow = TVShow::factory()->create([
            'name' => 'Non Existent Show',
            'permalink' => 'not-exist-' . Str::random(15),
            'has_imdb_info' => false,
            'last_imdb_check_date' => null,
        ]);

        // Mock the IMDb finder
        $mockFinder = Mockery::mock(TVShowImdbFinder::class);
        $mockFinder->shouldReceive('findSeries')
            ->once()
            ->with('Non Existent Show')
            ->andReturn([
                'found' => false,
                'is_tv_series' => false,
            ]);

        // Replace the real finder with our mock
        $this->app->instance(TVShowImdbFinder::class, $mockFinder);
        $tvShow->setImdbFinder($mockFinder);

        // Update IMDb info
        $imdbInfo = $tvShow->updateImdbInfo();

        // Assert TV show fields were updated
        $tvShow->refresh();
        $this->assertFalse($tvShow->has_imdb_info);
        $this->assertNotNull($tvShow->last_imdb_check_date);
        $this->assertNull($imdbInfo);
    }

    /** @test */
    public function it_preserves_has_imdb_info_when_finder_fails()
    {
        // Create a TV show that already has IMDb info
        $tvShow = TVShow::factory()->create([
            'name' => 'Breaking Bad',
            'permalink'=> 'breaking-bad-'. Str::random(15),
            'has_imdb_info' => true,
            'last_imdb_check_date' => now()->subDay(),
        ]);

        TVShowImdbInfo::factory()->create([
            'tv_show_id' => $tvShow->id,
            'imdb_id' => '0903747',
        ]);

        // Mock the IMDb finder to throw an exception
        $mockFinder = Mockery::mock(TVShowImdbFinder::class);
        $mockFinder->shouldReceive('findSeries')
            ->once()
            ->with('Breaking Bad')
            ->andThrow(new \Exception('IMDb API error'));

        // Replace the real finder with our mock
        $this->app->instance(TVShowImdbFinder::class, $mockFinder);
        $tvShow->setImdbFinder($mockFinder);

        // Update IMDb info should return null but not change has_imdb_info
        $imdbInfo = $tvShow->updateImdbInfo();

        // Assert TV show fields
        $tvShow->refresh();
        $this->assertTrue($tvShow->has_imdb_info); // Should still be true
        $this->assertNotNull($tvShow->last_imdb_check_date);
        $this->assertNull($imdbInfo);
    }
}
