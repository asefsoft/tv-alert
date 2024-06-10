<?php

namespace App\Livewire;

use App\Models\TVShow;
use App\Models\User;
use Livewire\Component;

class SubscribeButton extends Component
{
    public TVShow $tvShow;
    public bool $isSubscribed;
    public bool $showLoadingIndicator = true;

    public string $cssClasses = '';

    public function mount($class = '')
    {
        $this->cssClasses = $class;
        $this->checkSubscription();
    }

    public function subscribe()
    {
        if (auth()->check()) {
            $this->tvShow->toggleSubscriber(auth()->user());
            $this->checkSubscription(true);

            $this->dispatch('swal', [
                'title' => $this->isSubscribed ? "You've subscribed to this TV show." : "You've unsubscribed from this TV show.",
                'timer' => 4000,
                'icon' => 'success',
                'toast' => true,
                'position' => 'top',
            ]);

            $this->dispatch('subscriptions-changed');
        } else {
            $this->dispatch('register-required');
        }
    }

    public function render()
    {
        return view('livewire.subscribe-button');
    }

    private function checkSubscription(bool $subscriptionsUpdated = false): void
    {
        if (auth()->check()) {
            $this->isSubscribed = User::isAuthUserSubscribedFor($this->tvShow, $subscriptionsUpdated);
        }
    }
}
