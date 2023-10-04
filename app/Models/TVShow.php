<?php

namespace App\Models;

use App\Data\SearchTVShowData;
use App\Data\TVShowData;
use App\TVShow\TVShowStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravel\Scout\Searchable;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\WithData;

class TVShow extends Model
{
    use HasFactory, WithData, Searchable;

    // for tnt scout search
    public $asYouType = true;

    public const ActiveShows = [TVShowStatus::Running, TVShowStatus::InDevelopment, TVShowStatus::NewSeries, TVShowStatus::TBD_OnTheBubble];

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
        'last_ep' => 'array',
    ];

    // users that subscribed to tvshow
    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subscriptions', 'tvshow_id', 'user_id');
    }

    // add
    public function addSubscriber(User|int $user): bool
    {
        try {
            $this->subscribers()->attach($user->id ?? $user);
        } catch (QueryException $e) {
            // skipping Duplicate entry exception which means subscription is already exist
            return str_contains($e->getMessage(), 'Duplicate entry') ||
                   str_contains($e->getMessage(), 'Integrity constraint violation');
        }

        return true;
    }

    public function removeSubscriber(User|int $user): void
    {
        $this->subscribers()->detach($user->id ?? $user);
    }

    public function toggleSubscriber(User|int $user): void
    {
        $this->subscribers()->toggle($user->id ?? $user);
    }

    public function getShowDescription($maxLen = 0) {
        $description = strip_tags($this->description);
        return $maxLen > 0 ? substr($description, 0, $maxLen) . ' ...' : $description;
    }

    public function isRunning(): bool {
        return strtolower($this->status) == 'running';
    }

    public function scopeActiveShows(Builder $builder)
    {
        return $builder->whereIn('status', self::ActiveShows);
    }

    public function scopeHasNextEpisodeDate(Builder $builder)
    {
        return $builder->whereNotNull('next_ep_date');
    }

    // limit results to given show ids
    // usually use for filtering shows to current user subscribed shows
    public function scopeLimitToIDs(Builder $builder, array $showIDs)
    {
        return count($showIDs) ? $builder->whereIn('id', $showIDs) : $builder;
    }

    public function getFullInfoUrl() {
        return route("display-show-full-info", $this);
    }

    public function hasNexEpDate() : bool {
        return ! empty($this->next_ep_date);
    }

    public function hasLastEpDate() : bool {
        return ! empty($this->next_ep_date);
    }

    public function getNextEpisodeDateText($format = 'diffForHumans'): string
    {
        if (! $this->hasNexEpDate()) {
            return 'N/A';
        }

        if (empty($format) || $format == 'default') {
            $format = 'Y/m/d H:i';
        }

        return $format == 'diffForHumans' ? $this->next_ep_date->diffForHumans() : $this->next_ep_date->format($format);
    }

    public function getLastEpisodeDateText($format = 'diffForHumans'): string
    {
        if (! $this->last_ep_date) {
            return 'N/A';
        }

        if (empty($format) || $format == 'default') {
            $format = 'Y/m/d H:i';
        }

        return $format == 'diffForHumans' ? $this->last_ep_date->diffForHumans() : $this->last_ep_date->format($format);
    }

    public function getGenresText($max = -1): string {
        $genres = $max > 0 ? array_slice($this->genres, 0, $max) : $this->genres;
        return implode(', ', $genres ?? []);
    }

    public static function getRandomShow($count = 1): Collection
    {
        //where('id', '>', rand(1, 20000))
        return static::activeShows()->hasNextEpisodeDate()->inRandomOrder()->take($count)->get();
    }

    // is tvshow exists on db
    public static function isShowExist(string $permalink): bool
    {
        return TVShow::where('permalink', $permalink)->count() == 1;
    }

    public static function getShowByPermalink(string $permalink): ?TVShow
    {
        return TVShow::where('permalink', $permalink)->first();
    }

    // dont crawl too often and use a minimum crawl cache hours from config:
    // tvshow.crawl_min_cache_hours
    public static function shouldShowBeCrawled(string $permalink): bool
    {
        if (! static::isShowExist($permalink)) {
            return true;
        }

        $tvshow = static::getShowByPermalink($permalink);

        return now()->floatDiffInRealHours($tvshow->last_check_date) > config('tvshow.crawl_min_cache_hours');
    }

    // get on-air tvshows which the air date is close
    public static function getCloseAirDateShows($page = 1, $perPage = 20, $targetShows = []): LengthAwarePaginator
    {
        $q = static::
            // only active shows, not ENDED shows
            activeShows()
                ->LimitToIDs($targetShows) // only return shows we want, usually user's subscribed shows
            // get shows with close air-date
                ->hasNextEpisodeDate()
                ->whereBetween('next_ep_date', [now(), now()->addDays(2)]) // only next 2 days shows
                ->orderBy('next_ep_date', 'asc');
        //            ->orderBy('updated_at', 'asc')
        //            ->select(['name', 'next_ep_date', 'updated_at']);
        $q->toSql();
        //        dd($q->paginate($perPage, ['*'], 'page', $page)->toArray(), $q->getBindings());
        return $q->paginate($perPage, ['*'], 'page', $page);
    }

    // get shows that not recently crawled and we should crawl them sooner
    public static function getNotRecentlyCrawledShows($total = 20): Collection
    {
        $q = static::select(['id', 'permalink', 'name', 'last_check_date'])
            // only active shows, not ENDED shows
            ->whereIn('status', self::ActiveShows)
            ->where('last_check_date', '<', now()->subHours(6)) // dont include recently updated shows
            // not recently crawled shows
            ->orderBy('last_check_date', 'asc')
            ->take($total);
        //            ->select(['name', 'next_ep_date', 'updated_at']);
        return $q->get();
    }

    public static function getTodayShows($page = 1, $perPage = 20, $targetShows = []): LengthAwarePaginator
    {
        return static::getShowsByAirDateDistance(0, $page, $perPage, $targetShows);
    }

    public static function getYesterdayShows($page = 1, $perPage = 20, $targetShows = []): LengthAwarePaginator
    {
        return static::getShowsByAirDateDistance(-1, $page, $perPage, $targetShows);
    }

    public static function getTomorrowsShows($page = 1, $perPage = 20, $targetShows = []): LengthAwarePaginator
    {
        return static::getShowsByAirDateDistance(1, $page, $perPage, $targetShows);
    }

    // get yesterday, today, tomorrow shows
    public static function getRecentShows($page = 1, $perPage = 20, $targetShows = []): array
    {
        return [
            'yesterday' => static::getShowsByAirDateDistance(-1, $page, $perPage, $targetShows),
            'today' => static::getShowsByAirDateDistance(0, $page, $perPage, $targetShows),
            'tomorrow' => static::getShowsByAirDateDistance(1, $page, $perPage, $targetShows),
        ];
    }

    public static function getShowsByAirDateDistance(int $daysDistance = 0, $page = 1, $perPage = 20, $targetShows = [])
    {
        $q = static::
        // only active shows, not ENDED shows
            whereIn('status', self::ActiveShows)
                ->LimitToIDs($targetShows); // only return shows we want, usually user's subscribed shows

        // for today and before today shows we use `last_ep_date` field. giving from X days ago until end of today
        // for tomorrow and beyond shows we use `next_ep_date` field. giving from start of tomorrow until end of X days after.
        if ($daysDistance < 0) {
            $q->whereBetween('last_ep_date', [now()->addDays($daysDistance)->startOfDay(), now()->subDay()->endOfDay()])
                ->orderBy('last_ep_date', 'asc')
                ->orderBy('next_ep_date', 'asc');
        } elseif ($daysDistance == 0) { // today
            $q->whereBetween('last_ep_date', [now()->startOfDay(), now()->endOfDay()])
//                ->orwhereBetween('next_ep_date', [now()->startOfDay(), now()->endOfDay()])
                ->orderBy('last_ep_date', 'asc')
                ->orderBy('next_ep_date', 'asc');
        } else {
            $q->whereBetween('next_ep_date', [now()->addDays(1)->startOfDay(), now()->addDays($daysDistance)->endOfDay()])
                ->orderBy('next_ep_date', 'asc')
                ->orderBy('last_ep_date', 'asc');
        }

        // is there any target show ids?
        // we use this to filter shows that a user is subscribed to
        if (count($targetShows)) {
            $q->whereIn('id', $targetShows);
        }

        //        $q->toSql(); $q->getBindings();
        //        dd($q->getBindings());
        //        dump($q->paginate($perPage, ['*'], 'page', $page)->toArray());
        return $q->paginate($perPage, ['*'], 'page', $page);
    }

    public static function convertShowsToSearchData(LengthAwarePaginator $shows)
    {
        $showsData = new DataCollection(TVShowData::class, $shows->items());

        return new SearchTVShowData($shows->total(), $shows->currentPage(), $shows->lastPage(), $showsData);
    }

    public function toSearchableArray()
    {
        $array = [
            'id' => $this->id,
            'name' => $this->name,
            'network' => $this->network,
            'genre' => implode(" ", $this->genres ?? []),
        ];

        // Customize array...

        return $array;
    }
}
