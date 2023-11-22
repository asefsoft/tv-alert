<?php

namespace App\Livewire;

use App\TVShow\Timeline\Timeline;
use Livewire\Component;

class TVShowTimeline extends Component
{

    // how many days include in timeline at start of component
    public const DAYS_TO_SHOW_INIT = 60;
    public $lastPoll;
    public $diffPoll;

    public int $daysToShow;

    public bool $userEmailSubscribed;
    // refresh component whenever a subscription is changed from anywhere of app
    protected $listeners = ['subscriptions-changed' => '$refresh'];
    private $timeline;

    public function mount()
    {
        $this->userEmailSubscribed = $this->isAuthUserEmailSubscribed();
        $this->daysToShow = self::DAYS_TO_SHOW_INIT;
        $this->getTimeline();
        $this->updatePollingStats();
    }

    public function updated($property)
    {
        if ($property === 'userEmailSubscribed') {
            // if it changed and should be update on db
            if ($this->userEmailSubscribed !== $this->isAuthUserEmailSubscribed()) {
                // toggle email subscription
                auth()->user()->toggleEmailSubscription();

                // show msg
                $this->dispatch('swal', [
                    'title' => $this->userEmailSubscribed ? "You'll get email notification for new episodes." : "You won't get email for new episodes anymore.",
                    'timer' => 4000,
                    'icon' => 'success',
                    'toast' => true,
                    'position' => 'top',
                ]);
            }
        }
    }

    // show more days in timeline
    public function showMore()
    {
        $this->daysToShow += 30;
    }

    public function render()
    {
        $this->getTimeline();
        return view('livewire.tvshow-timeline', ['timeline' => $this->timeline]);
    }

    public function updatePollingStats(): void
    {
        $this->diffPoll = $this->lastPoll?->diffInSeconds();
        $this->lastPoll = now();
    }

    private function getTimeline(): void
    {
        $this->timeline = Timeline::makeTimeline($this->daysToShow);
    }

    private function isAuthUserEmailSubscribed()
    {
        return auth()->user()->isEmailSubscribed();
    }
}
