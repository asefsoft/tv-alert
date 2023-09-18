<?php

namespace App\TVShow;

use App\Models\TVShow;
use Illuminate\Database\Eloquent\Collection;
use TeamTNT\TNTSearch\TNTSearch;

class SearchTVShow
{
    protected bool $hasResult = false;
    protected int $totalResult = -1;
    protected bool $searchDone = false;
    private int $maxResults;

    protected bool $usedFuzzy = false;

    public function __construct(int $maxResults = 10) {
        $this->maxResults = $maxResults;
    }

    // quick static search function
    public static function fastSearch(string $term, int $maxResults = 10, &$searcher = null): Collection {
        $searcher = new SearchTVShow($maxResults);
        return $searcher->doSearch($term);
    }

    // do search via tntsearch scout
    public function doSearch(string $term): Collection {
        $result = TVShow::search($term)->take($this->maxResults)->get();
        TVShow::search($term, function (TNTSearch $tnt) use ($term)  {

            $result = $tnt->search($term);

            if($result['hits'] == 0) {
                $tnt->fuzziness(true);
                $this->usedFuzzy = true;
                return $tnt->search($term);
            }

            return $result;

        })->take($this->maxResults)->get();

        $this->totalResult = count($result ?? []);
        $this->searchDone = true;

        return $result;
    }

    public function hasResult(): bool {
        return $this->hasResult;
    }

    public function getTotalResult(): int {
        return $this->totalResult;
    }

    public function isSearchDone(): bool {
        return $this->searchDone;
    }

    public function setMaxResults(int $maxResults): void {
        if($maxResults > 50)
            $maxResults = 50;

        if($maxResults < 1)
            $maxResults = 10;

        $this->maxResults = $maxResults;
    }

    public function isUsedFuzzy(): bool {
        return $this->usedFuzzy;
    }


}
