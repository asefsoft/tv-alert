<?php

namespace App\TVShow;

use App\Models\TVShow;
use Illuminate\Database\Eloquent\Collection;
use TeamTNT\TNTSearch\TNTSearch;

class SearchTVShow
{
    protected bool $hasResult = false;
    protected int $fetchedResults = -1;
    protected int $possibleResults = -1;
    protected bool $searchDone = false;
    protected bool $doHighlight = true;
    private int $maxResults;

    protected bool $usedFuzzy = false;

    protected Collection $searchResults;

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
        $term = strtolower(trim($term));

        $result = TVShow::search($term, function (TNTSearch $tnt) use ($term) {

            $result = $tnt->search($term, $this->maxResults);

            if($result['hits'] == 0) {
                $tnt->fuzziness(true);
                $this->usedFuzzy = true;
                $result = $tnt->search($term, $this->maxResults);
            }

            $this->possibleResults = $result['hits'] ?? [];

            return $result;

        })->get();

        $this->searchResults = $result;

        if($this->doHighlight) {
            $this->highlightResults($term);
        }

        $this->fetchedResults = count($result ?? []);
        $this->searchDone = true;

        return $result;
    }

    public function highlightResults(string $term): void {
        /** @var TVShow $tvShow */
        $tnt = new TNTSearch();
        foreach ($this->searchResults as $tvShow) {
            $tvShow->name = $tnt->highlight($tvShow->name, $term, 'hl' ,['wholeWord' => false]);
            $tvShow->network = $tnt->highlight($tvShow->network, $term, 'hl' ,['wholeWord' => false]);
        }
    }

    public function hasResult(): bool {
        return $this->hasResult;
    }

    public function getFetchedResultsCount(): int {
        return $this->fetchedResults;
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

    public function doHighlight(bool $doHighlight): void {
        $this->doHighlight = $doHighlight;
    }

    public function getPossibleResults(): int {
        return $this->possibleResults;
    }


}
