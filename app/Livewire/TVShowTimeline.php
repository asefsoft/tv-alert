<?php

namespace App\Livewire;

use App\TVShow\Timeline\Timeline;
use Livewire\Component;

class TVShowTimeline extends Component
{
    // refresh component whenever a subscription is changed from anywhere of app
    protected $listeners = ['subscriptions-changed' => '$refresh'];

    // how many days include in timeline at start of component
    public const DAYS_TO_SHOW_INIT = 60;
    public $lastPoll, $diffPoll;

    public int $daysToShow;
    private $timeline;

    public function mount() {
        $this->daysToShow = self::DAYS_TO_SHOW_INIT;
        $this->getTimeline();
        $this->updatePollingStats();
    }

    // show more days in timeline
    public function showMore() {
        $this->daysToShow += 30;
    }

    public function render()
    {
        $this->getTimeline();
        return view('livewire.tvshow-timeline', ['timeline' => $this->timeline]);
    }

    public function updatePollingStats(): void {
        $this->diffPoll = $this->lastPoll?->diffInSeconds();
        $this->lastPoll = now();
    }

    private function getTimeline(): void {
        $this->timeline = Timeline::makeTimeline($this->daysToShow);
    }
}
