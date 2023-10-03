<?php

namespace Tests\Feature\Livewire;

use App\Livewire\TVShowSearchFull;
use App\Models\TVShow;
use Livewire\Livewire;
use Tests\TestCase;

class TVShowSearchFullTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
//        Livewire::test(TVShowSearchFull::class)
//            ->assertStatus(200);

        /** @var TVShow $tvShow */
        $tvShow = TVShow::getRandomShow()->first();

        $test = Livewire::test(TVShowSearchFull::class)->set('term', $tvShow->name);

        $test
            ->assertSee([$tvShow->status, $tvShow->thumb_url, $tvShow->country,
                'subscribeClicked($wire)', 'Search TV Shows', sprintf("show-%s", $tvShow->id),
                $tvShow->getFullInfoUrl(),
                'wire:model.live.debounce', 'wire:loading', 'x-on:click.prevent=', "tvShowClicked(\$wire, $tvShow->id)"])
            ->assertSeeText([strLimitHighlighted($tvShow->name, 70), $tvShow->network,
                $tvShow->getShowDescription(130), $tvShow->getGenresText(6)])
            ->set('term', 'some-not-exist-search-term') // not exist search term
            ->assertSee('No results!')
            ->assertStatus(200);
    }
}
