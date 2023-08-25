<?php

namespace App\Livewire;

use App\Models\TVShow;
use Livewire\Component;

class TVShowBox extends Component
{
    public bool $displayPoster = true;
    public TVShow $tvShow;

    public function subscribe() {
        $this->tvShow->name = 'subscribed';
    }

    public function render()
    {
        return view('livewire.tvshow-box');
    }
}
