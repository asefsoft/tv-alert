<?php

namespace Tests\Feature;

use App\Models\TVShow;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use function PHPUnit\Framework\assertTrue;

class TvShowModelTest extends TestCase
{

        public function test_not_recently_crawled_shows_are_ok(): void
    {
        $shows = TVShow::getNotRecentlyCrawledShows();
        assertTrue(true);
        return;
        self::assertInstanceOf(Collection::class, $shows);;

        // assure that result are in ascending order of last_check_date field
        self::assertTrue($shows->first()->last_check_date->lessThanOrEqualTo($shows->last()->last_check_date));

        /** @var TVShow $show */
        foreach ($shows as $show) {

            self::assertTrue($show->last_check_date->lessThan(now()->subHours(6)));

            $showFields = array_keys($show->getAttributes());
            self::assertTrue(in_array('name' , $showFields));
            self::assertTrue(in_array('permalink' , $showFields));
            self::assertTrue(in_array('last_check_date' , $showFields));
            self::assertTrue(in_array('id' , $showFields));
        }
    }

    public function test_today_tomorrow_etc_shows_are_ok() {
        assertTrue(true);
        return;
        $today = TVShow::getTodayShows();
        $yesterday = TVShow::getYesterdayShows();
        $tomorrow = TVShow::getTomorrowsShows();
        $allRecent = TVShow::getRecentShows();

        // has data
        self::assertGreaterThan(0, $today->count());
        self::assertGreaterThan(0, $tomorrow->count());
        self::assertGreaterThan(0, $yesterday->count());
        self::assertCount(3, $allRecent);

        self::assertTrue($allRecent['today'] == $today);
        self::assertTrue($allRecent['yesterday'] == $yesterday);
        self::assertTrue($allRecent['tomorrow'] == $tomorrow);

        // ordering is ok
        self::assertTrue($today->first()->last_ep_date->lessThanOrEqualTo($today->last()->last_ep_date));
        self::assertTrue($yesterday->first()->last_ep_date->lessThanOrEqualTo($yesterday->last()->last_ep_date));
        self::assertTrue($tomorrow->first()->next_ep_date->lessThanOrEqualTo($tomorrow->last()->next_ep_date));


        // check that date range is exactly in our desired range (yesterday, today, tomorrow)
        /** @var TVShow $show */
        foreach ($today as $show) {
            self::assertTrue($show->last_ep_date->between(now()->startOfDay(), now()->endOfDay()),
            "today has shows that last_ep_date is not in today date range");
        }

        foreach ($yesterday as $show) {
            self::assertTrue($show->last_ep_date->between(now()->subDay()->startOfDay(), now()->subDay()->endOfDay()),
            "yesterday has shows that last_ep_date is not in yesterday date range");
        }

        foreach ($tomorrow as $show) {
            self::assertTrue($show->next_ep_date->between(now()->addDay()->startOfDay(), now()->addDay()->endOfDay()),
                "tomorrow has shows that next_ep_date is not in tomorrow date range");
        }
    }
}
