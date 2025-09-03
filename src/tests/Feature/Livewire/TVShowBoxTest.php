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
            ->assertSeeText([$tvShow->name, 'Next: '.$tvShow->getNextEpisodeDateText(shouldBeFuture: true)]) // see contents
            ->assertSee(['Next Episode: '. $tvShow->getNextEpisodeDateText('default', shouldBeFuture: true), $tvShow->thumb_url, 'Subscribe', $tvShow->getFullInfoUrl()])
            ->assertDontSeeText(['Last: ' . $tvShow->getLastEpisodeDateText()]) // should not display last ep date by default
            ->set('displayLastEpDate', true) // now we say display it
            ->assertSeeText(['Last: '.$tvShow->getLastEpisodeDateText()]) // now it should be seen
            ->assertStatus(200);

    }

}
