<?php

namespace App\Livewire\Modals;

use App\Models\TVShow;
use Livewire\Attributes\On;
use Livewire\Component;

class FullInfoModal extends Component
{
    public bool $displayTvShowModal = false;


    public function render()
    {
        return view('livewire.modals.full-info');
    }
}
