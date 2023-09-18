<?php

namespace App\Livewire;

use App\Models\TVShow;
use App\Models\User;
use Livewire\Component;

class TVShowBox extends Component
{
    public bool $displayPoster = true;

    public bool $displayLastEpDate = false;

    public TVShow $tvShow;

    public function render()
    {
        return view('livewire.tvshow-box');
    }

}
