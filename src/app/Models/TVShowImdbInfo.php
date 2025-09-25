<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TVShowImdbInfo extends Model
{
    use HasFactory;

    protected $table = 'tv_show_imdb_info';

    protected $fillable = [
        'tv_show_id',
        'imdb_id',
        'imdb_url',
        'seasons',
        'lang',
        'year',
        'yearspan',
        'endyear',
        'keywords',
        'rating',
        'votes',
        'popularity_score',
    ];

    protected $casts = [
        'yearspan' => 'array',
        'keywords' => 'array',
        'rating' => 'float',
        'votes' => 'integer',
        'popularity_score' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        if (!isTesting()) {
            // update series popularity whenever model is saving
            static::saving(function ($model) {
                $model->updatePopularityScore();
            });
        }
    }


    public function tvShow(): BelongsTo
    {
        return $this->belongsTo(TVShow::class, 'tv_show_id');
    }

    /**
     * Calculate and update the popularity score
     */
    public function updatePopularityScore(): void
    {
        $this->popularity_score = $this->calculatePopularityScore();
    }

    /**
     * Calculate popularity score using weighted rating, vote count, and recency
     * @return float Score between 0-100
     */
    public function calculatePopularityScore(): float
    {
        if (!$this->rating || !$this->votes || $this->year < 1900) {
            return 0;
        }

        // Minimum votes required for reliability
        $minVotes = 1000;

        // Weight factors
        $ratingWeight = 0.6;    // 60% importance
        $votesWeight = 0.3;     // 30% importance
        $recencyWeight = 0.1;   // 10% importance

        // Calculate vote count score (0-1)
        $voteScore = min(1, $this->votes / 200000); // Normalize votes, cap at 200k

        // Calculate recency score (0-1)
        $currentYear = (int)date('Y');
        $year = $this->year ?? $currentYear;
        $yearsOld = $currentYear - $year;
        $recencyScore = max(0, 1 - ($yearsOld / 20)); // Linear decay over 20 years

        // Calculate weighted rating (Bayesian estimate)
        $weightedRating = (($minVotes * 7.0) + ($this->votes * $this->rating)) / ($minVotes + $this->votes);

        // Combine scores
        $popularityScore = (
            ($weightedRating * 10 * $ratingWeight) +
            ($voteScore * 100 * $votesWeight) +
            ($recencyScore * 100 * $recencyWeight)
        );

        return round($popularityScore, 2);
    }

    /**
     * Determine if the show is considered popular
     * @return bool
     */
    public function isPopular(): bool
    {
        return $this->popularity_score >= 70;
    }

    /**
     * Get popularity level
     * @return string
     */
    public function getPopularityLevel(): string
    {
        if ($this->popularity_score >= 90) return 'Extremely Popular';
        if ($this->popularity_score >= 75) return 'Very Popular';
        if ($this->popularity_score >= 65) return 'Popular';
        return 'Normal';
    }

}
