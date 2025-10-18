<?php

namespace App\Livewire;

use App\Models\TVShow;
use App\Models\User;
use Exception;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class TVShowGroup extends Component
{
    use WithPagination, WithoutUrlPagination;

    const VALID_SORT_FIELDS = [
        ['id' => 1, 'text' => 'Airing Soon', 'name' => 'next_ep_date', 'order' => 'asc', 'putBeforeTodayToEnd'=> true ],
        ['id' => 2, 'text' => 'Recently Released', 'name' => 'last_ep_date', 'order' => 'desc', 'putBeforeTodayToEnd'=> true],
        ['id' => 3, 'text' => 'Newest', 'name' => 'start_date', 'order' => 'desc'],
        ['id' => 4, 'text' => 'Oldest', 'name' => 'start_date', 'order' => 'asc'],
        ['id' => 5, 'text' => 'Most Popular', 'name' => 'popularity_score', 'order' => 'desc'],
        ['id' => 6, 'text' => 'Top Rated', 'name' => 'rating', 'order' => 'desc']
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
        $this->setDefaultSortField();
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
            $this->resetPage();
        }
    }


    public function setSortFieldByName($fieldName) {
        $fieldData = collect(self::VALID_SORT_FIELDS)->firstWhere('name', $fieldName);
        if ($fieldData) {
            $this->sortField = $fieldData['name'];
            $this->sortOrder = $fieldData['order'];
            $this->putBeforeTodayToEnd = $fieldData['putBeforeTodayToEnd'] ?? false;
//            $this->resetPage();
        }
    }

    // get shows base on group type
    protected function getShowsByType(): void
    {
        $userTvShows = $this->getUserSubscribedShowsIDs();

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

                // in this type we show subscribed shows, so it is meaningless to toggle subscribed shows
                $this->canToggleSubscribedShowsFilter = false;
                $this->shows = auth()->user()->getSubscribedShows($this->getPage(), $this->perPage,
                    $this->sortField, $this->sortOrder, $this->putBeforeTodayToEnd, $this->query);
                break;
            case 'recent-popular-shows':
            case 'ongoing-popular-shows':
            case 'popular-shows':
                $this->canToggleSubscribedShowsFilter = false;
                $query = $this->getPopularShows($this->type);
                $this->query = $query->toRawSql();
                $this->shows = $query->paginate($this->perPage);
                break;
            default:
                throw new Exception("Invalid 'type' is set for tvshow-group: ".$this->type);
        }
//        $this->shows->setPageName($this->type);
    }

    private function getPopularShows($type) {
        $query = TVShow::with('imdbInfo')
            ->popular()
            ->sortOrderBy($this->sortField, $this->sortOrder);

        switch ($type) {
            case 'recent-popular-shows':
                $query = $query->recentlyStarted();
                break;
            case 'ongoing-popular-shows':
                $query = $query->activeShows();
                break;
        }

        return $query;
    }

    // just for debug and test
    public function getQuery(): ?string {
        return $this->query;
    }

    protected function getUserSubscribedShowsIDs(): array
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

    private function setDefaultSortField(): void {
        switch ($this->type) {
            case 'recent-shows':
            case 'last-7-days-shows':
            case 'subscribed-shows':
                break;

            case 'popular-shows':
                $this->setSortFieldByName('popularity_score');
                break;

            case 'recent-popular-shows':
                $this->setSortFieldByName('rating');
                break;

            case 'ongoing-popular-shows':
                $this->setSortFieldByName('popularity_score');
                break;
        }
    }
}
