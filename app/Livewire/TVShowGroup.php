<?php

namespace App\Livewire;

use App\Models\TVShow;
use App\Models\User;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class TVShowGroup extends Component
{
    use WithPagination;

    // it will be passed to sub component 'TVShowBox'
    public bool $displayLastEpDate = false;
    public bool $displayOnlySubscribedShows = false;

    public bool $canToggleSubscribedShowsFilter = false;

    public string $title = 'Group Title';
    public string $type = 'recent-shows';

    public int $perPage = 6;

    protected $shows;

    public  function mount(){
        // only auth user can use toggle option
        $this->canToggleSubscribedShowsFilter = auth()->check();
    }

    public function updated($property)
    {
        if ($property === 'displayOnlySubscribedShows') {
        }
    }

    public function render()
    {
        $this->getShowsByType();

        return view('livewire.tvshow-group', ['shows' => $this->shows]);
    }

    // get shows base on group type
    protected function getShowsByType() {

        $targetShows = $this->getSubscribedShows();

        switch ($this->type) {
            case "recent-shows":
                $this->shows = TVShow::getCloseAirDateShows($this->getPage(), $this->perPage, $targetShows);
                break;
            case "last-7-days-shows":
                $this->displayLastEpDate = true;
                $this->shows = TVShow::getShowsByAirDateDistance(-7, $this->getPage(), $this->perPage, $targetShows);
                break;
            case "subscribed-shows":
                // unauthorized if user is not logged-in
                abort_if(!auth()->check(), 403);

                // in this type we show subscribed shows, then it is meaningless to toggle subscribed shows
                $this->canToggleSubscribedShowsFilter = false;
                $this->shows = auth()->user()->getSubscribedShows($this->getPage(), $this->perPage, $targetShows);
                break;
            default:
                throw new Exception("Invalid 'type' is set for tvshow-group: " . $this->type);
        }
    }

    protected function getSubscribedShows() : array {
        return $this->displayOnlySubscribedShows ? User::getAuthUserSubscribedShows() : [];
    }
}
