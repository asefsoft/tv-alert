<?php

namespace App\TVShow\EmailSubscriptions;

use App\Models\EmailSubscription;
use App\Models\User;

class EmailSubscriptionManager
{
    // get all users that are subscribed to tvshows email updates system
    public function getSubscribedUsers() {
        return User::emailSubscribed()->get();
    }

    public function addTodayEmailSubscriptionRecords(): void {

        $users = $this->getSubscribedUsers();

        /** @var User $user */
        foreach ($users as $user) {

            $recentShows = $user->getRecentShows(1, 10);

            // user has shows for today?
            // then create a subscription record in email subscriptions table
            if(count($recentShows['today'])) {
                EmailSubscription::addSubscriptionRecord($user);
            }
        }
    }
}
