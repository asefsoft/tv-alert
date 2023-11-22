<?php

namespace App\TVShow\EmailSubscriptions;

use App\Jobs\SendEmailTVShowUpdate;
use App\Models\EmailSubscription;
use App\Models\User;

class EmailSubscriptionManager
{
    public const DELAY_BETWEEN_EMAILS = 5;

    // get all users that are subscribed to tvshows email updates system
    public function getSubscribedUsers()
    {
        return User::emailSubscribed()->get();
    }

    // run it once a day to add records to email subscriptions table
    public function addTodayEmailSubscriptionRecords(): void
    {
        $users = $this->getSubscribedUsers();

        $total = 0;

        /** @var User $user */
        foreach ($users as $user) {
            $recentShows = $user->getRecentShows(1, 10);

            // user has shows for today?
            // then create a subscription record in email subscriptions table
            if (count($recentShows['today'])) {
                $total++;
                EmailSubscription::addSubscriptionRecord($user);
            }
        }

        // log info
        logError(sprintf('%s email subscription records has been added for today', $total), 'info');
    }

    // run it frequently until it send all emails in email subscriptions table
    public function sendEmailSubscriptions(): void
    {
        $subscriptions = EmailSubscription::getTodayEmailSubscriptions(10);

        foreach ($subscriptions as $emailSub) {
            SendEmailTVShowUpdate::dispatch($emailSub)->onQueue('emails');

            if (! isTesting()) {
                sleep(self::DELAY_BETWEEN_EMAILS);
            }
        }
    }
}
