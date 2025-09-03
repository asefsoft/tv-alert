<?php

namespace App\TVShow;

use App\Models\TVShow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class UpdateTVShowsImdbInfo
{
    const DELAY_BETWEEN_UPDATED = 5;

    /**
     * Create a new instance of the updater.
     */
    public function __construct(
        private readonly int $batchSize = 20
    ) {}

    /**
     * Update IMDb information for a batch of TV shows.
     *
     * @return array{updated: int, failed: int, total: int} Statistics about the update operation
     */
    public function update(): array
    {
        $shows = $this->getShowsToUpdate();

        $stats = [
            'updated' => 0,
            'failed' => 0,
            'total' => $shows->count()
        ];

        foreach ($shows as $show) {
            try {
                $imdbInfo = $show->updateImdbInfo();

                if (!isTesting())
                    sleep(self::DELAY_BETWEEN_UPDATED);

                if ($imdbInfo) {
                    $stats['updated']++;
                    Log::info("Updated IMDb info for show: {$show->name}({$imdbInfo['year']}), {$imdbInfo['rating']}, Seasons: {$imdbInfo['seasons']}, {$imdbInfo['imdb_url']}");
                } else {
                    $stats['failed']++;
                    Log::warning("Failed to find IMDb info for show: {$show->name}");
                }
            } catch (\Exception $e) {
                $stats['failed']++;
                Log::error("Error updating IMDb info for show: {$show->name}", [
                    'error' => $e->getMessage(),
                    'show_id' => $show->id
                ]);
            }
        }

        return $stats;
    }

    /**
     * Get the shows that need their IMDb information updated.
     * Prioritizes shows that haven't been checked in the longest time.
     */
    protected function getShowsToUpdate(): Collection
    {
        return TVShow::query()
            ->orderBy('last_imdb_check_date', 'asc')
            ->take($this->batchSize)
            ->get();
    }
}
