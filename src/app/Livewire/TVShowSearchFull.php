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

    public const PER_PAGE = 10;
    public const MAX_RESULTS = 200;
    #[Url]
    public string $term = '';

    #[Url]
    public int $page = 1;
    private $shows;

    public function mount()
    {
        $this->setPage(request()->get('page', 1));
        $this->getSearchResults();
    }

    public function updated($property)
    {
        if ($property === 'term') {
            $this->resetPage();
            $this->getSearchResults();
        }
    }

    // livewire paginator hook
    public function updatedPage($page)
    {
        $pageChanged = $this->page !== $page;
        $this->page = $page;

        if ($pageChanged) {
            $this->getSearchResults();
        }
    }

    public function render()
    {
        return view('livewire.tvshow-search-full', ['shows' => $this->shows]);
    }

    // get search results
    private function getSearchResults(): void
    {
        if (! empty($this->term)) {
            $this->shows = SearchTVShow::fastSearch($this->term, self::PER_PAGE, $this->getPage(), self::MAX_RESULTS, $searcher);
        } else {
            // empty
            $this->shows = new LengthAwarePaginator(collect(), 0, self::PER_PAGE);
        }
    }
}
