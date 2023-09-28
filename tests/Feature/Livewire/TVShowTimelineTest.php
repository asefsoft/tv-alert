<?php

namespace Tests\Feature\Livewire;

use App\Livewire\TVShowTimeline;
use App\Models\TVShow;
use App\Models\User;
use App\TVShow\Timeline\Timeline;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class TVShowTimelineTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        // preparation
        $user = User::first();
        list($totalSubscription, $daysToShow, $pastTvShows, $todayTvShows, $futureTvShows, $pastTimePeriod,
            $todayTimePeriod, $futureTimePeriod)
            = $this->preparation($user);

        /** @var \Livewire\Features\SupportTesting\Testable $test */
        $test = Livewire::actingAs($user)->test(TVShowTimeline::class);

            $test->assertSeeText([sprintf("You have subscribed to %s TV shows.", $totalSubscription),
                    'Past Episodes', "Today Episodes", "Future Episodes",
                ], false)
                ->assertSeeText(sprintf("Show More Days >> (%s)", $daysToShow), false)
                // checking that all series name appeared in the timeline
                ->assertSeeText($pastTvShows->pluck('name')->toArray())
                ->assertSeeText($todayTvShows->pluck('name')->toArray())
                ->assertSeeText($futureTvShows->pluck('name')->toArray())
                // time periods
                ->assertSeeText([$pastTimePeriod, $todayTimePeriod, $futureTimePeriod], false)
                ->call('showMore') // click on show more days
                ->assertSeeText(sprintf("Show More Days >> (%s)", $daysToShow + 30), false)
                ->assertStatus(200);
    }

    private function preparation(User $user): array {
        auth()->login($user);

        // subscribe to 15 series for given user
        $totalSubscription = User::getAuthUserTotalSubscribedShows(true); // force to clear possible cached data
        if ($totalSubscription < 15) {
            $shows = TVShow::inRandomOrder()->take(15);
            $user->subscriptions()->sync($shows->pluck('id'));
        }

        // making a manual timeline, so we could use it to validate rendered timeline component data
        $daysToShow = TVShowTimeline::DAYS_TO_SHOW_INIT;
        $timeline = Timeline::makeTimeline($daysToShow);
        $sections = $timeline->getSections();
        // tv shows of each section
        $pastTvShows = $sections[0]->getTvShows()->getCollection();
        $todayTvShows = $sections[1]->getTvShows()->getCollection();
        $futureTvShows = $sections[2]->getTvShows()->getCollection();
        // time periods
        $pastTimePeriod = $sections[0]->fm->getSectionTimePeriod();
        $todayTimePeriod = $sections[0]->fm->getSectionTimePeriod();
        $futureTimePeriod = $sections[0]->fm->getSectionTimePeriod();
        return array($totalSubscription, $daysToShow, $pastTvShows, $todayTvShows, $futureTvShows, $pastTimePeriod, $todayTimePeriod, $futureTimePeriod);
    }
}
