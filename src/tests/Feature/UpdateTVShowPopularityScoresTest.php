<?php

namespace Tests\Feature\Commands;

use App\Models\TVShow;
use App\Models\TVShowImdbInfo;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTVShowPopularityScoresTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        TVShowImdbInfo::truncate();
    }

    public function test_can_update_popularity_scores(): void
    {
        // Arrange
        $tvShow = TVShow::factory()->create();
        $imdbInfo = TVShowImdbInfo::factory()->create([
            'tv_show_id' => $tvShow->id,
            'rating' => 8.5,
            'votes' => 50000,
            'year' => 2020,
            'popularity_score' => 0, // Initial score
        ]);

        // double chack that there is not already set a popularity_score on db
        $this->assertDatabaseHas('tv_show_imdb_info', [
            'popularity_score' => 0
        ]);

        // Act
        $this->artisan('tvshow:update-popularity')
            ->assertSuccessful();

        // Assert
        $this->assertDatabaseHas('tv_show_imdb_info', [
            'id' => $imdbInfo->id,
            'popularity_score' => 65.82
        ]);
    }

    public function test_handles_empty_database(): void
    {
        // Act & Assert
        $this->artisan('tvshow:update-popularity')
            ->assertSuccessful()
            ->expectsOutput('No TV shows found to update.');
    }

    public function test_updates_multiple_shows(): void
    {
        // Arrange
        $showCount = 3;
        $shows = TVShow::factory()
            ->count($showCount)
            ->create()
            ->each(function ($show) {
                TVShowImdbInfo::factory()->create([
                    'tv_show_id' => $show->id,
                    'popularity_score' => 0
                ]);
            });

        // double chack that there is not already set a popularity_score on db
        $this->assertDatabaseHas('tv_show_imdb_info', [
            'popularity_score' => 0
        ]);

        // Act
        $this->artisan('tvshow:update-popularity')
            ->assertSuccessful();

        // Assert
        $this->assertDatabaseCount('tv_show_imdb_info', $showCount);
        $this->assertDatabaseMissing('tv_show_imdb_info', [
            'popularity_score' => 0
        ]);
    }
}
