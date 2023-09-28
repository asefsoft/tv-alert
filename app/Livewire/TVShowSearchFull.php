<?php

namespace App\Livewire;

use App\TVShow\SearchTVShow;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TVShowSearchFull extends Component
{
    use WithPagination;

    const PER_PAGE = 10;
    const MAX_RESULTS = 200;
    #[Url]
    public string $term = '';

    #[Url]
    public int $page = 1;
    private $shows;

    public function mount() {
        $this->setPage(request()->get('page', 1));
    }

    public function updated($property) {
        if ($property === 'term') {
            $this->resetPage();
            $this->getSearchResults();
        }
    }

    // livewire paginator hook
    public function updatedPage($page)
    {
        $this->page = $page;
        $this->getSearchResults();
    }

    // get search results
    private function getSearchResults(): void {
        if(!empty($this->term)) {
            $this->shows = SearchTVShow::fastSearch($this->term, self::PER_PAGE, $this->getPage(), self::MAX_RESULTS, $searcher);
        }else {
            // empty
            $this->shows = new LengthAwarePaginator(collect(), 0, self::PER_PAGE);
        }
    }

    public function render()
    {
        return view('livewire.tvshow-search-full', ['shows' => $this->shows]);
    }
}
