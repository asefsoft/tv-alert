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

    const VALID_SORT_FIELDS = [
        ['id' => 1, 'text' => 'Release Soon', 'name' => 'next_ep_date', 'order' => 'asc', 'putBeforeTodayToEnd'=> true ],
        ['id' => 2, 'text' => 'Recently Released', 'name' => 'last_ep_date', 'order' => 'asc'],
        ['id' => 3, 'text' => 'Newest', 'name' => 'start_date', 'order' => 'desc'],
        ['id' => 4, 'text' => 'Oldest', 'name' => 'start_date', 'order' => 'asc'],
//        ['id' => 5, 'text' => 'Recently Ended', 'name' => 'end_date', 'order' => 'asc']
    ];

    // it will be passed to sub component 'TVShowBox'
    public bool $displayLastEpDate = false;

    public bool $displayOnlySubscribedShows = false;

    public bool $canToggleSubscribedShowsFilter = false;

    public bool $canSort = false;

    // Sorting properties
    public string $sortField = 'next_ep_date';
    public string $sortOrder = 'asc';
    private bool $putBeforeTodayToEnd = true;
    private ?string $query = '';

    public string $title = 'Group Title';

    public string $type = 'recent-shows';

    public int $perPage = 6;

    protected $listeners = ['subscriptions-changed' => '$refresh'];

    protected mixed $shows;

    public function mount()
    {
        // only auth user can use toggle option
        $this->canToggleSubscribedShowsFilter = auth()->check();
    }

    public function render()
    {
            $this->getShowsByType();
            return view('livewire.tvshow-group', [
                'shows' => $this->shows,
                'sortField' => $this->sortField,
                'validSortFields' => self::VALID_SORT_FIELDS
            ]);
    }

    public function changeSortField($fieldId) {
        $fieldData = collect(self::VALID_SORT_FIELDS)->firstWhere('id', $fieldId);
        if ($fieldData) {
            $this->sortField = $fieldData['name'];
            $this->sortOrder = $fieldData['order'];
            $this->putBeforeTodayToEnd = $fieldData['putBeforeTodayToEnd'] ?? false;
        }
    }

    // get shows base on group type
    protected function getShowsByType(): void
    {
        $userTvShows = $this->getSubscribedShows();

        switch ($this->type) {
            case 'recent-shows':
                $this->shows = TVShow::getCloseAirDateShows($this->getPage(), $this->perPage, $userTvShows);
                break;
            case 'last-7-days-shows':
                $this->displayLastEpDate = true;
                $this->shows = TVShow::getShowsByAirDateDistance(-7, $this->getPage(), $this->perPage, $userTvShows);
                break;
            case 'subscribed-shows':
                // unauthorized if user is not logged-in
                abort_if(! auth()->check(), 403);

                // in this type we show subscribed shows, then it is meaningless to toggle subscribed shows
                $this->canToggleSubscribedShowsFilter = false;
                $this->shows = auth()->user()->getSubscribedShows($this->getPage(), $this->perPage,
                    $this->sortField, $this->sortOrder, $this->putBeforeTodayToEnd, $this->query);
                break;
            default:
                throw new Exception("Invalid 'type' is set for tvshow-group: ".$this->type);
        }
    }

    protected function getSubscribedShows(): array
    {
        $userTvShows = [];

        if ($this->displayOnlySubscribedShows) {
            $userTvShows = User::getAuthUserSubscribedShows();
            if (count($userTvShows) === 0) {
                $userTvShows = [-999]; // an invalid tvshow id
            }
        }

        return $userTvShows;
    }
}
