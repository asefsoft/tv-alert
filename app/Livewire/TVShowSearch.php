<?php

namespace App\Livewire;

use App\TVShow\SearchTVShow;
use Illuminate\Support\Collection;
use Livewire\Component;

class TVShowSearch extends Component
{
    public Collection $results;

    public string $term;

    public function updated($property) {
        if ($property === 'term') {
            if(!empty($this->term)) {
                $this->results = SearchTVShow::fastSearch($this->term, 7);
            }
        }
    }

    public function render()
    {

        return view('livewire.tvshow-search');
    }
}
