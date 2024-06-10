<?php

namespace App\Livewire;

use App\TVShow\SearchTVShow;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;

class TVShowSearch extends Component
{
    public Collection $results;

    #[Url]
    public string $term;
    public bool $usedFuzzy = false;
    public int $possibleResults = -1;

    public function updated($property)
    {
        if ($property === 'term') {
            if (! empty($this->term)) {
                $this->results = SearchTVShow::fastSearch($this->term, 7, 1, 7, $searcher)->getCollection();
                $this->usedFuzzy = $searcher->isUsedFuzzy();
                $this->possibleResults = $searcher->getPossibleResults();
            }
        }
    }

    public function render()
    {
        return view('livewire.tvshow-search');
    }
}
