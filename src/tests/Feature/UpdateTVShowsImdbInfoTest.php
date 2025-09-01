<?php

namespace Tests\Feature;

use App\Models\TVShow;
use App\TVShow\TVShowStatus;
use App\TVShow\UpdateTVShowsImdbInfo;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateTVShowsImdbInfoTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        TVShow::truncate();
    }

    /** @test */
    public function it_updates_shows_ordered_by_last_check_date()
    {
        // Create shows with different last check dates
        $oldestShow = TVShow::factory()->create([
            'name' => 'Breaking Bad',
            'permalink' => 'breaking-bad-' . Str::random(20),
            'status' => TVShowStatus::Running,
            'has_imdb_info'=> false,
            'last_imdb_check_date' => now()->subDays(30),
        ]);

        $middleShow = TVShow::factory()->create([
            'name' => 'Better Call Saul',
            'permalink' => 'better-' . Str::random(20),
            'status' => TVShowStatus::Running,
            'has_imdb_info'=> false,
            'last_imdb_check_date' => now()->subDays(15),
        ]);

        $newestShow = TVShow::factory()->create([
            'name' => 'The Wire',
            'permalink' => 'wire-' . Str::random(20),
            'status' => TVShowStatus::Running,
            'has_imdb_info'=> false,
            'last_imdb_check_date' => now()->subDays(5),
        ]);

        // Create a mock finder that returns success for all shows
//        $mockFinder = Mockery::mock(TVShowImdbFinder::class);
//        $mockFinder->shouldReceive('findSeries')
//            ->times(2) // We'll set batch size to 2
//            ->andReturn([
//                'found' => true,
//                'is_tv_series' => true,
//                'imdb_id' => '0903747',
//                'imdb_url' => 'https://www.imdb.com/title/tt0903747',
//                'seasons' => 5,
//                'lang' => 'en',
//                'year' => 2008,
//                'yearspan' => ['start' => 2008, 'end' => '2013'],
//                'endyear' => 2013,
//                'keywords' => ['drama'],
//                'rating' => 9.5,
//                'votes' => 1800000,
//            ]);

        // Set the mock finder only on shows we expect to be processed (batch size is 2)
//        $oldestShow->setImdbFinder($mockFinder);
//        $middleShow->setImdbFinder($mockFinder);
//        $newestShow->setImdbFinder($mockFinder);

        // Create updater with batch size of 2 and set test show IDs
        $updater = new UpdateTVShowsImdbInfo(2);
//        $updater->setTestShowIds([$oldestShow->id, $middleShow->id, $newestShow->id]);

        // Run the update
        $stats = $updater->update();

        // Assert stats are correct
        $this->assertEquals(2, $stats['updated']);
        $this->assertEquals(0, $stats['failed']);
        $this->assertEquals(2, $stats['total']);

        // Assert the two oldest shows were updated
        $oldestShow->refresh();
        $middleShow->refresh();
        $newestShow->refresh();

        $this->assertTrue($oldestShow->has_imdb_info);
        $this->assertTrue($middleShow->has_imdb_info);
        $this->assertFalse($newestShow->has_imdb_info);
    }

    /** @test */
    public function it_handles_failed_updates_gracefully()
    {

        TVShow::factory()->create([
            'name' => 'Non Existent Show',
            'status' => TVShowStatus::Running,
            'last_imdb_check_date' => now()->subDays(29),
        ]);

        // Fake the logger to assert logging
        Log::spy();

        // Run the update with specific test shows
        $updater = new UpdateTVShowsImdbInfo(1);
        $stats = $updater->update();

        // Assert stats
        $this->assertEquals(0, $stats['updated']);
        $this->assertEquals(1, $stats['failed']);
        $this->assertEquals(1, $stats['total']);

        // Assert log messages
        Log::shouldHaveReceived('warning')->once()->with("Failed to find IMDb info for show: Non Existent Show");
    }

    /** @test */
    public function it_processes_shows_with_null_check_dates_first()
    {
        // Create shows with different last check dates
        $neverCheckedShow = TVShow::factory()->create([
            'name' => 'Breaking Bad',
            'last_imdb_check_date' => null,
        ]);

        $oldShow = TVShow::factory()->create([
            'name' => 'Old Check Show',
            'last_imdb_check_date' => now()->subDays(30),
        ]);

        // Mock finder
//        $mockFinder = Mockery::mock(TVShowImdbFinder::class);
//        $mockFinder->shouldReceive('findSeries')
//            ->once()
//            ->with('Never Checked Show')
//            ->andReturn([
//                'found' => true,
//                'is_tv_series' => true,
//                'imdb_id' => '1234567',
//                'imdb_url' => 'https://www.imdb.com/title/tt1234567',
//                'seasons' => 3,
//                'lang' => 'en',
//                'year' => 2020,
//                'yearspan' => ['start' => 2020, 'end' => 'present'],
//                'endyear' => null,
//                'keywords' => ['drama'],
//                'rating' => 8.5,
//                'votes' => 500000,
//            ]);
//
//        // Set the mock finder only on the show we expect to be processed (batch size is 1)
//        $neverCheckedShow->setImdbFinder($mockFinder);

        // Run update with batch size of 1 and specific test shows
        $updater = new UpdateTVShowsImdbInfo(1);
        $stats = $updater->update();

        // Should process the never checked show first
        $this->assertEquals(1, $stats['updated']);
        $this->assertEquals(0, $stats['failed']);
        $this->assertEquals(1, $stats['total']);

        $neverCheckedShow->refresh();
        $this->assertNotNull($neverCheckedShow->last_imdb_check_date);
        $this->assertTrue($neverCheckedShow->has_imdb_info);
    }
}
