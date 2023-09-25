<?php

namespace Tests\Feature\Livewire;

use App\Livewire\TVShowSearch;
use App\Models\TVShow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Str;
use Tests\TestCase;

class TVShowSearchTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        /** @var TVShow $tvShow */
        $tvShow = TVShow::getRandomShow()->first();

        $test = Livewire::test(TVShowSearch::class)->set('term', $tvShow->name);

        self::assertInstanceOf(Collection::class, $test->get('results'));
        self::assertGreaterThan(0, $test->get('results')->count());

        $test
            ->assertSee([$tvShow->status, $tvShow->thumb_url, $tvShow->country,
            'wire:model.live.debounce', 'wire:loading', 'x-on:click.prevent=', "tvShowClicked(\$wire, $tvShow->id)"])
            ->assertSeeText([Str::limit($tvShow->name, 35), $tvShow->network], false)
            ->set('term', 'some-not-exist-search-term') // not exist search term
            ->assertSee('No results!')
            ->assertCount('results', 0)
            ->assertStatus(200);
    }
}
