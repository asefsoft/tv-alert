<?php

namespace Tests\Feature;

use App\Jobs\SendEmailTVShowUpdate;
use App\Mail\TVShowsUpdatesNotif;
use App\Models\EmailSubscription;
use App\Models\TVShow;
use App\Models\User;
use App\TVShow\EmailSubscriptions\EmailSubscriptionManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Tests\Integration\Queue\Job;
use Tests\TestCase;

class EmailSubscriptionTest extends TestCase
{
    public function setUp(): void {
        parent::setUp();
        DB::beginTransaction();
    }

    public function tearDown(): void {
        DB::rollback();
        parent::tearDown();
    }

    public function test_user_can_toggle_email_subscription(): void
    {
        $user = User::inRandomOrder()->first();
        $user->tvshows_updates_subscription = 0;

        $user->toggleEmailSubscription();
        self::assertEquals(1, $user->tvshows_updates_subscription);

        // toggle again
        $user->toggleEmailSubscription();
        self::assertEquals(0, $user->tvshows_updates_subscription);
    }

    public function test_can_add_email_subscription_model_record() {
        $user = User::inRandomOrder()->first();

        // add subs
        EmailSubscription::addSubscriptionRecord($user);

        $todaySubscriptions = EmailSubscription::getTodayEmailSubscriptions();

        // user is in subscribed users list
        $subsRecord = $todaySubscriptions->where('user_id', $user->id)->first();

        // assert that our user is equal to user in email subscription
        self::assertEquals($user, $subsRecord->user);

        $this->assertDatabaseHas(EmailSubscription::class, [
            'user_id' => $user->id,
            'target_day' => now()->format('Y-m-d'),
            'is_sent' => 0,
            'tries' => 0
        ]);

        // add user again to test that there won't be any exception
        EmailSubscription::addSubscriptionRecord($user);

        // add new try email which is not sent
        $subsRecord->addNewTry(false);

        $this->assertDatabaseHas(EmailSubscription::class, [
            'user_id' => $user->id,
            'target_day' => now()->format('Y-m-d'),
            'is_sent' => 0,
            'tries' => 1
        ]);

        // add new try email which is sent
        $subsRecord->addNewTry(true);

        $this->assertDatabaseHas(EmailSubscription::class, [
            'user_id' => $user->id,
            'target_day' => now()->format('Y-m-d'),
            'is_sent' => 1,
            'tries' => 2
        ]);

        return $user;

    }

    public function test_target_day_format_saves_correctly() {
        $record = new EmailSubscription();
        $record->user_id = 1;
        $record->target_day = "2023-12-01 18:01:00";
        $record->save();

        // asset it dont keep the 'time' part
        self::assertEquals('2023-12-01', $record->target_day);
    }

    public function test_add_today_email_subscription_records_is_working() {

        $user = $this->getUserWithTodaySubscribedShow();

        $subsManager = new EmailSubscriptionManager();

        // the test
        $subsManager->addTodayEmailSubscriptionRecords();

        // assert record is added
        $expectedRecord = [
            'user_id' => $user->id,
            'target_day' => now()->format('Y-m-d'),
            'is_sent' => 0,
            'tries' => 0
        ];
        $this->assertDatabaseHas(EmailSubscription::class, $expectedRecord);

    }

    public function test_email_will_send() {

        // create a subscription record for user 1
        $record = new EmailSubscription();
        $record->user_id = 1;
        $record->target_day = now();
        $record->save();

        $subsManager = new EmailSubscriptionManager();

        Mail::fake();
        $subsManager->sendEmailSubscriptions();
        Mail::assertSent(TVShowsUpdatesNotif::class);
    }

    public function test_email_content() {
        $user = $this->getUserWithTodaySubscribedShow();

        $todayShows = $user->getRecentShows(1, 10)['today'];

        $message = new TVShowsUpdatesNotif($user);

        $message->assertTo($user->email);
        $message->assertSeeInText("Today TV Shows");
        $message->assertHasSubject(sprintf('Series Alert: Your today TV shows (%s)', now()->format("Y/m/d")));
        $message->assertSeeInText($user->name);
        $message->assertSeeInText($todayShows->first()->name);
        $message->assertSeeInText("See Your Timeline");
        $message->assertSeeInText(config('app.name'));
        $message->assertSeeInHtml(route('display-timeline'));
        $message->assertSeeInHtml($todayShows->first()->getFullInfoUrl());
    }

    // add a tvshow subscription with a today air date
    private function getUserWithTodaySubscribedShow(): User {
        $user = User::emailSubscribed()->inRandomOrder()->first();
        $show = TVShow::getRandomShow(1)->first();

        // subscribing user to show
        $show->addSubscriber($user);

        // make sure that tvshow is on air today
        $show->last_ep_date = now();
        $show->status = "Running";
        $show->save();

        return $user;
    }
}
