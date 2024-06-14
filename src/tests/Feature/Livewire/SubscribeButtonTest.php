<?php

namespace Tests\Feature\Livewire;

use App\Livewire\SubscribeButton;
use App\Livewire\TVShowBox;
use App\Models\TVShow;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class SubscribeButtonTest extends TestCase
{
    /** @test */
    public function renders_successfully_and_can_subscribe_and_unsubscribe()
    {
        /** @var TVShow $tvShow */
        $tvShow = TVShow::getRandomShow()->first();
        $user = User::factory()->create();

        $test = Livewire::actingAs($user)
            ->test(SubscribeButton::class, ['tvShow' => $tvShow])
            ->assertSee(['Subscribe', "wire:loading", "bg-blue-500 hover:bg-blue-600"]) // see contents
            ->call('subscribe')
            ->assertSee(['Unsubscribe', "bg-green-500 hover:bg-green-600"]) // see Unsubscribe text on button
            ->assertSet('isSubscribed', true) // property is set
            // dispatched show success message
            ->assertDispatched('swal', [
                'title' => "You've subscribed to this TV show.",
                'timer' => 4000,
                'icon' => 'success',
                'toast' => true,
                'position' => 'top',
            ])
            ->assertNotDispatched('register-required')
            ->assertStatus(200);

        // assert user has subscribed to tvshow
        self::assertTrue($user->subscriptions()->get()->contains('id', $tvShow->id));
        self::assertTrue($user->isAuthUserSubscribedFor($tvShow));

        // call subscribe again to un-subscribe
        $test->call('subscribe')
            ->assertSet('isSubscribed', false); // property is set

        // assert user has un-subscribed to tvshow
        self::assertFalse($user->subscriptions()->get()->contains('id', $tvShow->id));
        self::assertFalse($user->isAuthUserSubscribedFor($tvShow));
    }

    public function test_unregistered_user_cant_subscribe()
    {
        $tvShow = TVShow::getRandomShow()->first();

        // unregistered user can not subscribe to show and when click on subscribe btn h'd see register message
        $test = Livewire::test(SubscribeButton::class, ['tvShow' => $tvShow])
            ->call('subscribe')
            ->assertSee(['Subscribe']) // did not change
            ->assertSet('isSubscribed', false) // did not change
            // dispatched register-required message
            ->assertDispatched('register-required')
            ->assertNotDispatched('swal')
            ->assertStatus(200);
    }
}
