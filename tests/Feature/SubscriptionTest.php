<?php

namespace Tests\Feature;

use App\Models\TVShow;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    public function test_user_can_subscribe_to_a_show()
    {
        $user = User::inRandomOrder()->first();
        $show = TVShow::inRandomOrder()->first();

        $done = $user->addSubscription($show);

        self::assertTrue($done);

        // we test twice to make sure there won't be an exception for multiple adding subscriptions
        $done = $user->addSubscription($show);
        self::assertTrue($done);

        $subscribedUsers = $show->subscribers()->get()->pluck('id')->toArray();

        self::assertTrue(in_array($user->id, $subscribedUsers));

        self::assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'tvshow_id' => $show->id,
        ]);

        return [$user, $show];
    }

    /**
     * @depends test_user_can_subscribe_to_a_show
     */
    public function test_user_can_unsubscribe_a_show($userAndShow)
    {
        /** @var User $user */
        [$user, $show] = $userAndShow;

        $user->removeSubscription($show);

        self::assertDatabaseMissing('subscriptions', [
            'user_id' => $user->id,
            'tvshow_id' => $show->id,
        ]);
    }

    public function test_show_can_add_subscription_for_a_user_to_itself()
    {
        $user = User::inRandomOrder()->first();
        $show = TVShow::inRandomOrder()->first();

        $done = $show->addSubscriber($user);
        self::assertTrue($done);

        // we test twice to make sure there won't be an exception for multiple adding subscriptions
        $done = $show->addSubscriber($user);
        self::assertTrue($done);

        $subscribedUsers = $show->subscribers()->get()->pluck('id')->toArray();

        self::assertTrue(in_array($user->id, $subscribedUsers));

        self::assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'tvshow_id' => $show->id,
        ]);

        return [$user, $show];
    }

    /**
     * @depends test_show_can_add_subscription_for_a_user_to_itself
     */
    public function test_show_can_unsubscribe_a_user_from_itself($userAndShow)
    {
        /** @var User $user */
        /** @var TVShow $show */
        [$user, $show] = $userAndShow;

        $show->removeSubscriber($user);

        self::assertDatabaseMissing('subscriptions', [
            'user_id' => $user->id,
            'tvshow_id' => $show->id,
        ]);
    }

    public static function tearDownAfterClass(): void
    {
        // clear subscriptions table
        DB::table('subscriptions')->truncate();
        parent::tearDown();
    }
}
