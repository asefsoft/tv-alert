<?php

namespace Tests\Feature\Livewire;

use App\Livewire\TVShowSearch;
use App\Models\TVShow;
use App\TVShow\SearchTVShow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Str;
use TeamTNT\TNTSearch\TNTSearch;
use Tests\TestCase;

class TVShowSearchTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        /** @var TVShow $tvShow */
        $tvShow = TVShow::getRandomShow()->first();

        $term = $tvShow->name;

        // highlight tvshow name so we can look for it in results
        $tnt = new TNTSearch();
        $showNameHighlighted = $tnt->highlight($tvShow->name, $term, 'hl' ,['wholeWord' => false]);

        $test = Livewire::test(TVShowSearch::class)->set('term', $term);

        self::assertInstanceOf(Collection::class, $test->get('results'));
        self::assertGreaterThan(0, $test->get('results')->count());

        $test
            ->assertSee([$tvShow->status, $tvShow->thumb_url, $tvShow->country,
            'wire:model.live.debounce', 'wire:loading', 'x-on:click.prevent=', "tvShowClicked(\$wire, $tvShow->id)"])
            ->assertSee([strLimitHighlighted($showNameHighlighted, 35)], false)
            ->assertSeeText([$tvShow->network], false)
            ->set('term', 'some-not-exist-search-term') // not exist search term
            ->assertSee('No results!')
            ->assertCount('results', 0)
            ->assertStatus(200);
    }
}
