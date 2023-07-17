<?php

namespace App\Models;

use App\Data\TVShowData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\WithData;

class TVShow extends Model
{
    use HasFactory, WithData;

    protected $table = 'tv_shows';
    protected $dataClass = TVShowData::class;
    protected $guarded = [];
    protected $casts = [
//        'status' => PostStatus::class,

        'start_date' => 'immutable_date',
        'end_date' => 'immutable_date',
        'next_ep_date' => 'immutable_date',
        'genres' => 'array',
        'pictures' => 'array',
        'episodes' => 'array',
        'next_ep' => 'array',
    ];

    // is tvshow exists on db
    public static function isShowExist(string $permalink) : bool {
        return TVShow::where('permalink', $permalink)->count() == 1;
    }

    public static function getShowByPermalink(string $permalink): TVShow | null {
        return TVShow::where('permalink', $permalink)->first();
    }

    // dont crawl too often and use a minimum crawl cache hours from config:
    // tvshow.crawl_min_cache_hours
    public static function shouldShowBeCrawled(string $permalink): bool {
        if (!static::isShowExist($permalink))
            return true;

        $tvshow = static::getShowByPermalink($permalink);

        return now()->floatDiffInRealHours($tvshow->last_check_date) > config('tvshow.crawl_min_cache_hours');
    }


}
