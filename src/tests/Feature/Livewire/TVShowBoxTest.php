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
                    $tvShow->getNextEpisodeDateText('default'), $tvShow->thumb_url, 'Subscribe'], $tvShow->getFullInfoUrl()) // see contents
            ->assertDontSee(['Last: '.$tvShow->getLastEpisodeDateText()]) // should not display last ep date by default
            ->set('displayLastEpDate', true) // now we say display it
            ->assertSee(['Last: '.$tvShow->getLastEpisodeDateText()]) // now it should be seen
            ->assertStatus(200);

    }

}
