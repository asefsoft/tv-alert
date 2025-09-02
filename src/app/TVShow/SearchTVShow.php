<?php

namespace App\TVShow;

use App\Models\TVShow;
use Illuminate\Pagination\LengthAwarePaginator;
use TeamTNT\TNTSearch\TNTSearch;

class SearchTVShow
{
    protected bool $hasResult = false;
    protected int $possibleResults = -1;
    protected bool $searchDone = false;
    protected bool $doHighlight = true;

    protected bool $usedFuzzy = false;

    protected $searchResults;

    public function __construct(protected int $perPage = 10, protected int $page = 1, protected int $maxResults = 200)
    {
    }

    // quick static search function
    public static function fastSearch(
        string $term,
        int $perPage = 10,
        int $page = 1,
        int $maxResults = 200,
        &$searcher = null
    ): LengthAwarePaginator {
        $searcher = new SearchTVShow($perPage, $page, $maxResults);
        return $searcher->doSearch($term);
    }

    // do search via tntsearch scout
    public function doSearch(string $term): LengthAwarePaginator
    {
        $term = strtolower(trim($term));

        $result = TVShow::search($term, function (TNTSearch $tnt) use ($term) {
            $result = $tnt->search($term, $this->maxResults);

            if ($result['hits'] === 0) {
                $tnt->fuzziness(true);
                $this->usedFuzzy = true;
                $result = $tnt->search($term, $this->maxResults);
            }

            $this->possibleResults = $result['hits'] ?? [];

            return $result;
        })->query(function ($builder) {
            $builder->with('imdbinfo'); //eager load imdbinfo relation
        });

        $this->searchResults = $result->paginate($this->perPage, 'page', $this->page);

        if ($this->doHighlight) {
            $this->highlightResults($term);
        }

        $this->searchDone = true;

        return $this->searchResults;
    }

    public function highlightResults(string $term): void
    {
        $tnt = new TNTSearch();
        /** @var TVShow $tvShow */
        foreach ($this->searchResults as $tvShow) {
            $tvShow->name = $tnt->highlight($tvShow->name, $term, 'hl', ['wholeWord' => false]);
            $tvShow->network = $tnt->highlight($tvShow->network, $term, 'hl', ['wholeWord' => false]);
        }
    }

    public function hasResult(): bool
    {
        return $this->hasResult;
    }

    public function isSearchDone(): bool
    {
        return $this->searchDone;
    }

    public function isUsedFuzzy(): bool
    {
        return $this->usedFuzzy;
    }

    public function doHighlight(bool $doHighlight): void
    {
        $this->doHighlight = $doHighlight;
    }

    public function getPossibleResults(): int
    {
        return $this->possibleResults;
    }
}
