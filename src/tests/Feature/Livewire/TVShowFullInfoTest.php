<?php

namespace Tests\Feature\Livewire;

use App\Livewire\TVShowFullInfo;
use App\Models\TVShow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class TVShowFullInfoTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        $tvShow = TVShow::getRandomShow()->first();
        $secondTvShow = TVShow::getRandomShow()->first();

        Livewire::test(TVShowFullInfo::class, ['tvShowId' => $tvShow->id])
            ->assertSee([$tvShow->name, $tvShow->image_url, $tvShow->status, $tvShow?->network,
                $tvShow?->getNextEpisodeDateText(shouldBeFuture: true), 'Loading...', $tvShow->getShowDescription()])
            ->assertSee($tvShow->genres)
            ->assertDontSee('Read More')
            ->assertSet('isLoadingShowInfo', false)
            ->assertSet('isModalMode', false)
            ->set('isModalMode', true) // testing Modal Mode
            ->assertSee('Read More', $tvShow->getShowDescription(400)) // in modal mode we should see 'Read More' and limited text
            ->dispatch('tvshow-changed', [$secondTvShow->id]) // dispatch an event to switch into the second tvshow
            ->assertSee([$secondTvShow->name, $secondTvShow->image_url]) // should see second tvshow info
            ->assertStatus(200);
    }
}
