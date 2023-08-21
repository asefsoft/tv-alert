<?php

namespace App\Models;

use App\Data\SearchTVShowData;
use App\Data\TVShowData;
use App\TVShow\TVShowStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Integer;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\WithData;

class TVShow extends Model
{
    use HasFactory, WithData;

    const ActiveShows = [TVShowStatus::Running, TVShowStatus::InDevelopment, TVShowStatus::NewSeries, TVShowStatus::TBD_OnTheBubble];

    protected $table = 'tv_shows';
    protected $dataClass = TVShowData::class;
    protected $guarded = [];
    protected $casts = [
        'start_date' => 'immutable_date',
        'end_date' => 'immutable_date',
        'next_ep_date' => 'immutable_datetime:Y-m-d H:i:s',
        'last_ep_date' => 'immutable_datetime',
        'last_check_date' => 'immutable_datetime',
        'last_aired_ep' => 'array',
        'genres' => 'array',
        'pictures' => 'array',
        'episodes' => 'array',
        'next_ep' => 'array',
    ];

    // users that subscribed to tvshow
    public function subscribers(): BelongsToMany {
        return $this->belongsToMany(User::class, 'subscriptions', 'tvshow_id', 'user_id');
    }

    // add
    public function addSubscriber(User | int $user): bool {
        try {
            $this->subscribers()->attach($user->id ?? $user);
        } catch (QueryException $e) {
            // skipping Duplicate entry exception which means subscription is already exist
            return str_contains($e->getMessage(), "Duplicate entry") ||
                   str_contains($e->getMessage(), "Integrity constraint violation");
        }

        return true;
    }

    public function removeSubscriber(User | int $user): void {
        $this->subscribers()->detach($user->id ?? $user);
    }

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
    public static function getCloseAirDateShows($page = 1, $perPage = 20): LengthAwarePaginator {
        $q = static::
            // only active shows, not ENDED shows
            whereIn("status", self::ActiveShows)
            // get shows with close air-date
            ->whereNotNull('next_ep_date')
            ->whereBetween('next_ep_date', [now()->subDay()->startOfDay(), now()->addDays(2)]) // only next 2 days shows
            ->where('last_check_date', '<', now()->subHours(6)) // dont include recently updated shows
            ->orderBy('next_ep_date', 'asc');
//            ->orderBy('updated_at', 'asc')
//            ->select(['name', 'next_ep_date', 'updated_at']);
        $q->toSql();
//        dd($q->paginate($perPage, ['*'], 'page', $page)->toArray(), $q->getBindings());
        return $q->paginate($perPage, ['*'], 'page', $page);
    }

    // get shows that not recently crawled and we should crawl them sooner
    public static function getNotRecentlyCrawledShows($total = 20): Collection {
        $q = static::select(['id','permalink','name','last_check_date'])
            // only active shows, not ENDED shows
            ->whereIn("status", self::ActiveShows)
            ->where('last_check_date', '<', now()->subHours(6)) // dont include recently updated shows
            // not recently crawled shows
            ->orderBy('last_check_date', 'asc')
            ->take($total);
//            ->select(['name', 'next_ep_date', 'updated_at']);
        return $q->get();
    }

    public static function getTodayShows($page = 1, $perPage = 20): LengthAwarePaginator {
        return static::getShowsByAirDateDistance(0, $page, $perPage);
    }

    public static function getYesterdayShows($page = 1, $perPage = 20): LengthAwarePaginator {
        return static::getShowsByAirDateDistance(-1, $page, $perPage);
    }

    public static function getTomorrowsShows($page = 1, $perPage = 20): LengthAwarePaginator {
        return static::getShowsByAirDateDistance(1, $page, $perPage);
    }

    // get yesterday, today, tomorrow shows
    public static function getRecentShows($page = 1, $perPage = 20): array {
        return [
            'yesterday' => static::getShowsByAirDateDistance(-1, $page, $perPage),
            'today'     => static::getShowsByAirDateDistance(0, $page, $perPage),
            'tomorrow'  => static::getShowsByAirDateDistance(1, $page, $perPage),
            ];
    }

    public static function getShowsByAirDateDistance(int $daysDistance = 0, $page = 1, $perPage = 20) {
        $q = static::
        // only active shows, not ENDED shows
            whereIn("status", self::ActiveShows);
            // for today and before today shows we use `last_ep_date` field. giving from X days ago until end of today
            // for tomorrow and beyond shows we use `next_ep_date` field. giving from start of tomorrow until end of X days after.
            if($daysDistance < 0) {
                $q->whereBetween("last_ep_date", [now()->addDays($daysDistance)->startOfDay(), now()->subDay()->endOfDay()])
                    ->orderBy("last_ep_date", 'asc')
                    ->orderBy('next_ep_date', 'asc');
            } elseif($daysDistance == 0) { // today
                $q->whereBetween("last_ep_date", [now()->startOfDay(), now()->endOfDay()])
                    ->orderBy("last_ep_date", 'asc')
                    ->orderBy('next_ep_date', 'asc');
            } else {
                $q->whereBetween("next_ep_date", [now()->addDays(1)->startOfDay(), now()->addDays($daysDistance)->endOfDay()])
                    ->orderBy('next_ep_date', 'asc')
                    ->orderBy('last_ep_date', 'asc');
            }

//        $q->toSql(); $q->getBindings();
//        dd($q->getBindings());
//        dump($q->paginate($perPage, ['*'], 'page', $page)->toArray());
        return $q->paginate($perPage, ['*'], 'page', $page);
    }



    public static function convertShowsToSearchData(LengthAwarePaginator $shows) {
        $showsData = new DataCollection(TVShowData::class, $shows->items());
        return new SearchTVShowData($shows->total(), $shows->currentPage(), $shows->lastPage(),$showsData);
    }


}
