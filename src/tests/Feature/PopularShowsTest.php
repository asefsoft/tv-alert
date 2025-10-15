<?php

namespace Tests\Feature;

use App\Livewire\TVShowGroup;
use App\Models\TVShow;
use App\Models\TVShowImdbInfo;
use App\TVShow\TVShowStatus;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class PopularShowsTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        TVShowImdbInfo::truncate();
        TVShow::truncate();

        $this->createTestShows();
    }

    /** @test */
    public function it_shows_all_time_popular_shows()
    {
        Livewire::test(TVShowGroup::class, [
            'type' => 'popular-shows',
            'perPage' => 12
        ])
        ->assertViewHas('shows', function ($shows) {
            // Should only include shows with popularity_score >= 70
            return $shows->every(function ($show) {
                return $show->imdbInfo->popularity_score >= 70;
            }) && $shows->count() === 2; // We expect 2 popular shows from our test data
        });
    }

    /** @test */
    public function it_shows_recent_popular_shows()
    {
        Livewire::test(TVShowGroup::class, [
            'type' => 'recent-popular-shows',
            'perPage' => 6
        ])
        ->assertViewHas('shows', function ($shows) {
            return $shows->every(function ($show) {
                // Check if show is recent (within last 5 years) and has high popularity
                $isRecent = $show->start_date >= now()->subYears(5);
                $isPopular = $show->imdbInfo->popularity_score >= 75;
                return $isRecent && $isPopular;
            }) && $shows->count() === 1; // We expect 1 recent popular show from our test data
        });
    }

    /** @test */
    public function it_shows_ongoing_popular_shows()
    {
        Livewire::test(TVShowGroup::class, [
            'type' => 'ongoing-popular-shows',
            'perPage' => 6
        ])
        ->assertViewHas('shows', function ($shows) {
            return $shows->every(function ($show) {
                // Check if show is ongoing
                $isOngoing = $show->isActive() ;
                $isPopular = $show->imdbInfo->popularity_score >= 70;
                    return $isOngoing && $isPopular;
            });
        });
    }

    /** @test */
    public function it_properly_sorts_popular_shows_by_popularity_score()
    {
        Livewire::test(TVShowGroup::class, [
            'type' => 'popular-shows',
            'sortField' => 'popularity_score',
            'sortOrder' => 'desc',
            'perPage' => 12
        ])
        ->assertViewHas('shows', function ($shows) {
            $scores = $shows->pluck('imdbInfo.popularity_score')->toArray();
            return collect($scores)->every(fn($score, $index) =>
                $index === 0 || $scores[$index - 1] >= $score
            );
        });
    }

    /** @test */
    public function it_properly_sorts_popular_shows_by_rating()
    {
        Livewire::test(TVShowGroup::class, [
            'type' => 'popular-shows',
            'sortField' => 'rating',
            'sortOrder' => 'desc',
            'perPage' => 12
        ])
            ->assertViewHas('shows', function ($shows) {
                $ratings = $shows->pluck('imdbInfo.rating')->toArray();
                return collect($ratings)->every(fn($rating, $index) =>
                    $index === 0 || $ratings[$index - 1] >= $rating
                );
            });
    }

    private function createTestShows(): void
    {
        // Create a very popular show from 7 years ago (ended)
        $oldPopularShow = TVShow::factory()->create([
            'start_date' => now()->subYears(7),
            'end_date' => now()->subYear(),
        ]);
        TVShowImdbInfo::factory()->create([
            'tv_show_id' => $oldPopularShow->id,
            'rating' => 9.0,
            'votes' => 100000,
            'year' => now()->subYears(3)->year,
            'popularity_score' => 85
        ]);

        // Create a recent popular show (ongoing)
        $recentPopularShow = TVShow::factory()->create([
            'start_date' => now()->subMonths(3),
            'status' => TVShowStatus::Running,
            'end_date' => null,
        ]);
        TVShowImdbInfo::factory()->create([
            'tv_show_id' => $recentPopularShow->id,
            'rating' => 8.5,
            'votes' => 50000,
            'year' => now()->subMonths(3)->year,
            'popularity_score' => 78
        ]);

        // Create a recent but not popular enough show
        $recentUnpopularShow = TVShow::factory()->create([
            'start_date' => now()->subMonths(6),
            'end_date' => null,
        ]);
        TVShowImdbInfo::factory()->create([
            'tv_show_id' => $recentUnpopularShow->id,
            'rating' => 6.5,
            'votes' => 1000,
            'year' => now()->year,
            'popularity_score' => 65
        ]);

        // Create an old unpopular show
        $oldUnpopularShow = TVShow::factory()->create([
            'start_date' => now()->subYears(7),
            'end_date' => now()->subYears(3),
        ]);
        TVShowImdbInfo::factory()->create([
            'tv_show_id' => $oldUnpopularShow->id,
            'rating' => 5.5,
            'votes' => 500,
            'year' => now()->subYears(7)->year,
            'popularity_score' => 45
        ]);
    }
}
