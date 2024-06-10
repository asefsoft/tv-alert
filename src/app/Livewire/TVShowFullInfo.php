<?php

namespace App\Livewire;

use App\Models\TVShow;
use Livewire\Attributes\On;
use Livewire\Component;

class TVShowFullInfo extends Component
{
    public bool $isLoadingShowInfo = false;

    // is loaded from modal?
    public bool $isModalMode = false;

    public TVShow $tvShow;

    // can be set from outside
    public int $tvShowId;

    // set new tvshow dynamically
    #[On('tvshow-changed')]
    public function tvShowChanged($tvshowId)
    {
        $this->fetchTvShowByID($tvshowId);
    }

    public function mount()
    {
        if (! empty($this->tvShowId)) {
            $this->fetchTvShowByID($this->tvShowId);
        }
    }

    public function render()
    {
        return view('livewire.tvshow-full-info');
    }

    private function fetchTvShowByID($tvshowId): void
    {
        $this->tvShow = TVShow::whereId($tvshowId)->first();
        // this will show tvshow info and hides "Loading..." indicator
        $this->isLoadingShowInfo = false;
    }
}
