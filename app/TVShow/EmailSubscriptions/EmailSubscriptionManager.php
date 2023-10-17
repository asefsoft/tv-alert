<?php

namespace App\TVShow\EmailSubscriptions;

use App\Jobs\SendEmailTVShowUpdate;
use App\Models\EmailSubscription;
use App\Models\User;

class EmailSubscriptionManager
{
    const DELAY_BETWEEN_EMAILS = 1;

    // get all users that are subscribed to tvshows email updates system
    public function getSubscribedUsers() {
        return User::emailSubscribed()->get();
    }

    // run it once a day to add records to email subscriptions table
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

    // run it frequently until it send all emails in email subscriptions table
    public function sendEmailSubscriptions() {
        $subscriptions = EmailSubscription::getTodayEmailSubscriptions(10);

        foreach ($subscriptions as $emailSub) {
            SendEmailTVShowUpdate::dispatch($emailSub)->onQueue('emails');
            sleep(self::DELAY_BETWEEN_EMAILS);
        }
    }
}
