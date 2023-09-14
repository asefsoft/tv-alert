<?php

namespace Tests\Feature\Livewire;

use App\Livewire\TVShowGroup;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class TVShowGroupTest extends TestCase
{
    /** @test */
    public function recent_shows_renders_successfully()
    {
        // first test without auth user
        Livewire::test(TVShowGroup::class, ['type' => 'recent-shows', 'title' => 'Recent Shows'])
            ->assertSee(['Recent Shows', '</livewire:tvshow-box>',
                'alt="TV Show Poster"', 'aria-label="Pagination Navigation"'], false) // see all necessary parts
            ->assertDontSee(['Only Subscribed Shows?', 'No tv show available!'])
            ->assertSet('canToggleSubscribedShowsFilter', false)
            ->assertSet('displayOnlySubscribedShows', false)
            ->assertStatus(200);

        // now with auth user
        $user = User::factory()->create();
        Livewire::actingAs($user)->test(TVShowGroup::class, ['type' => 'recent-shows', 'title' => 'Recent Shows'])
            ->assertSee(['Recent Shows', '</livewire:tvshow-box>', 'Only Subscribed Shows?',
                'alt="TV Show Poster"', 'aria-label="Pagination Navigation"'], false) // see all necessary parts
            ->assertDontSee(['No tv show available!'])
            ->assertSet('canToggleSubscribedShowsFilter', true) // can toggle var now must be true
            ->assertSet('displayOnlySubscribedShows', false)
            ->set('displayOnlySubscribedShows', true) // enable it to display only user's subscribed tv-shows, which is none show
            ->assertDontSee(['alt="TV Show Poster"'], false) // we expect to dont see tv-box data coz user dont have any subscribed shows
            ->assertStatus(200);
    }

    /** @test */
    public function subscribed_renders_successfully()
    {
        // access is forbidden without auth
        Livewire::test(TVShowGroup::class, ['type' => 'subscribed-shows'])
            ->assertForbidden();

        // now with auth user
        $user = User::inRandomOrder()->first();
        Livewire::actingAs($user)->test(TVShowGroup::class, ['type' => 'subscribed-shows', 'title' => 'Your shows'])
            ->assertSee(['Your shows', 'No tv show available!'], false)
            ->assertDontSee(['Only Subscribed Shows?'])
            ->assertSet('canToggleSubscribedShowsFilter', false)
            ->assertSet('displayOnlySubscribedShows', false)
            ->assertStatus(200);

    }

    public function last_7_days_shows_renders_successfully()
    {
        Livewire::test(TVShowGroup::class, ['type' => 'last-7-days-shows', 'title' => 'Last 7 days shows'])
            ->assertSee(['Last 7 days shows', '</livewire:tvshow-box>'], false)
            ->assertDontSee(['Only Subscribed Shows?', 'No tv show available!'])
            ->assertSet('canToggleSubscribedShowsFilter', false)
            ->assertSet('displayOnlySubscribedShows', false)
            ->assertStatus(200);
    }

    /** @test */
    public function dont_accept_invalid_group_type()
    {
        $this->expectExceptionMessage("Invalid 'type' is set for tvshow-group: some-invalid-type");
        Livewire::test(TVShowGroup::class, ['type' => 'some-invalid-type']);
    }
}
