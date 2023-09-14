<?php

namespace Tests\Feature\Livewire;

use App\Livewire\TVShowBox;
use App\Models\TVShow;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class TVShowBoxTest extends TestCase
{
    /** @test */
    public function renders_successfully_and_can_subscribe_and_unsubscribe()
    {
        /** @var TVShow $tvShow */
        $tvShow = TVShow::getRandomShow()->first();
        $user = User::factory()->create();

        $test = Livewire::actingAs($user)
            ->test(TVShowBox::class, ['tvShow' => $tvShow])
            ->assertSee([$tvShow->name, 'Next: '.$tvShow->getNextEpisodeDateText(), 'Next Episode: '.
                    $tvShow->getNextEpisodeDateText('default'), $tvShow->thumb_url, 'Subscribe']) // see contents
            ->assertDontSee(['Last: '.$tvShow->getLastEpisodeDateText()]) // should not display last ep date by default
            ->set('displayLastEpDate', true) // now we say display it
            ->assertSee(['Last: '.$tvShow->getLastEpisodeDateText()]) // now it should be seen
            ->call('subscribe')
            ->assertSee(['Unsubscribe']) // see Unsubscribe text on button
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
        $test = Livewire::test(TVShowBox::class, ['tvShow' => $tvShow])
            ->call('subscribe')
            ->assertSee(['Subscribe']) // did not change
            ->assertSet('isSubscribed', false) // did not change
            // dispatched register-required message
            ->assertDispatched('register-required')
            ->assertNotDispatched('swal')
            ->assertStatus(200);
    }
}
