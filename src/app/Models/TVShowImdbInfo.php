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
    ];

    protected $casts = [
        'yearspan' => 'array',
        'keywords' => 'array',
        'rating' => 'float',
        'votes' => 'integer',
    ];

    public function tvShow(): BelongsTo
    {
        return $this->belongsTo(TVShow::class, 'tv_show_id');
    }
}
