<?php

namespace App\Models;

use App\Data\SearchTVShowData;
use App\Data\TVShowData;
use App\TVShow\TVShowStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\DataCollection;
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

    // get on-air tvshows which the air date is close
    public static function getToBeCrawledShows($page = 1, $perPage = 20): LengthAwarePaginator {
        $q = static::
            // only active shows, not ENDED shows
        whereIn("status", [TVShowStatus::Running, TVShowStatus::InDevelopment, TVShowStatus::NewSeries,TVShowStatus::TBD_OnTheBubble])
            // get shows with close air-date
            ->whereBetween('next_ep_date', [now()->subHours(6), now()->addDays(2)]) // only next 2 days shows
            ->where('updated_at', '<', now()->subHours(6)) // dont include recently updated shows
            ->orderBy('next_ep_date', 'asc');
//            ->orderBy('updated_at', 'asc')
//            ->select(['name', 'next_ep_date', 'updated_at']);
//        $q->toSql();
//        dd($q->paginate($perPage, ['*'], 'page', $page)->toArray(), $q->getBindings());
        return $q->paginate($perPage, ['*'], 'page', $page);
    }

    public static function convertShowsToSearchData(LengthAwarePaginator $shows) {
        $showsData = new DataCollection(TVShowData::class, $shows->items());
        return new SearchTVShowData($shows->total(), $shows->currentPage(), $shows->lastPage(),$showsData);
    }


}
