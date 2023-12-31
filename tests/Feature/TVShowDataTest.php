<?php

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Data\EpisodeData;
use App\Data\SearchTVShowData;
use App\Data\TVShowData;
use App\Events\TVShowCreated;
use App\Events\TVShowUpdated;
use App\Models\TVShow;
use App\TVShow\CreateOrUpdateStatus;
use App\TVShow\CreateOrUpdateTVShow;
use App\TVShow\TVShowStatus;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\DataCollection;
use Tests\TestCase;

class TVShowDataTest extends TestCase
{
    // dont change data of this string

    private string $showInfoJson = '{"id":4228,"name":"Lost","permalink":"lost","last_aired_ep":{"season":6,"episode":18,"name":"The End (2)","air_date":"2010-05-24 01:00:00"},"url":"https://www.episodate.com/tv-show/lost","countdown":{"season":1,"episode":3,"name":"Draw a Blank","air_date":"2023-07-05 12:00:00"},"description":"Out of the blackness, the first thing Jack senses is pain. Then burning sun.","description_source":null,"start_date":"2004-09-22","end_date":null,"country":"US","status":"Ended","runtime":60,"network":"ABC","youtube_link":null,"image_path":"https://static.episodate.com/images/tv-show/full/4228.jpg","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/4228.jpg","rating":"9.1667","rating_count":"30","genres":["Drama","Adventure","Supernatural"],"pictures":["https://static.episodate.com/images/episode/4228-552.jpg","https://static.episodate.com/images/episode/4228-168.jpg","https://static.episodate.com/images/episode/4228-150.jpg","https://static.episodate.com/images/episode/4228-193.jpg","https://static.episodate.com/images/episode/4228-620.jpg","https://static.episodate.com/images/episode/4228-769.jpg","https://static.episodate.com/images/episode/4228-665.jpg"],"episodes":[{"season":1,"episode":1,"name":"Pilot (1)","air_date":"2004-09-23 00:00:00"},{"season":1,"episode":2,"name":"Pilot (2)","air_date":"2004-09-30 00:00:00"},{"season":1,"episode":3,"name":"Tabula Rasa","air_date":"2004-10-07 00:00:00"},{"season":1,"episode":4,"name":"Walkabout","air_date":"2004-10-14 00:00:00"},{"season":1,"episode":5,"name":"White Rabbit","air_date":"2004-10-21 00:00:00"},{"season":1,"episode":6,"name":"House of the Rising Sun","air_date":"2004-10-28 00:00:00"},{"season":1,"episode":7,"name":"The Moth","air_date":"2004-11-04 01:00:00"},{"season":1,"episode":8,"name":"Confidence Man","air_date":"2004-11-11 01:00:00"},{"season":1,"episode":9,"name":"Solitary","air_date":"2004-11-18 01:00:00"},{"season":1,"episode":10,"name":"Raised by Another","air_date":"2004-12-02 01:00:00"},{"season":1,"episode":11,"name":"All the Best Cowboys Have Daddy Issues","air_date":"2004-12-09 01:00:00"},{"season":1,"episode":12,"name":"Whatever the Case May Be","air_date":"2005-01-06 01:00:00"},{"season":1,"episode":13,"name":"Hearts and Minds","air_date":"2005-01-13 01:00:00"},{"season":1,"episode":14,"name":"Special","air_date":"2005-01-20 01:00:00"},{"season":1,"episode":15,"name":"Homecoming","air_date":"2005-02-10 01:00:00"},{"season":1,"episode":16,"name":"Outlaws","air_date":"2005-02-17 01:00:00"},{"season":1,"episode":17,"name":"...In Translation","air_date":"2005-02-24 01:00:00"},{"season":1,"episode":18,"name":"Numbers","air_date":"2005-03-03 01:00:00"},{"season":1,"episode":19,"name":"Deus ex Machina","air_date":"2005-03-31 01:00:00"},{"season":1,"episode":20,"name":"Do No Harm","air_date":"2005-04-07 00:00:00"},{"season":1,"episode":21,"name":"The Greater Good","air_date":"2005-05-05 00:00:00"},{"season":1,"episode":22,"name":"Born to Run","air_date":"2005-05-12 00:00:00"},{"season":1,"episode":23,"name":"Exodus (1)","air_date":"2005-05-19 00:00:00"},{"season":1,"episode":24,"name":"Exodus (2)","air_date":"2005-05-26 00:00:00"},{"season":1,"episode":25,"name":"Exodus (3)","air_date":"2005-05-26 01:00:00"},{"season":2,"episode":1,"name":"Man of Science, Man of Faith","air_date":"2005-09-22 01:00:00"},{"season":2,"episode":2,"name":"Adrift","air_date":"2005-09-29 01:00:00"},{"season":2,"episode":3,"name":"Orientation","air_date":"2005-10-06 01:00:00"},{"season":2,"episode":4,"name":"Everybody Hates Hugo","air_date":"2005-10-13 01:00:00"},{"season":2,"episode":5,"name":"...And Found","air_date":"2005-10-20 01:00:00"},{"season":2,"episode":6,"name":"Abandoned","air_date":"2005-11-10 02:00:00"},{"season":2,"episode":7,"name":"The Other 48 Days","air_date":"2005-11-17 02:00:00"},{"season":2,"episode":8,"name":"Collision","air_date":"2005-11-24 02:00:00"},{"season":2,"episode":9,"name":"What Kate Did","air_date":"2005-12-01 02:00:00"},{"season":2,"episode":10,"name":"The 23rd Psalm","air_date":"2006-01-12 02:00:00"},{"season":2,"episode":11,"name":"The Hunting Party","air_date":"2006-01-19 02:00:00"},{"season":2,"episode":12,"name":"Fire + Water","air_date":"2006-01-26 02:00:00"},{"season":2,"episode":13,"name":"The Long Con","air_date":"2006-02-09 02:00:00"},{"season":2,"episode":14,"name":"One of Them","air_date":"2006-02-16 02:00:00"},{"season":2,"episode":15,"name":"Maternity Leave","air_date":"2006-03-02 02:00:00"},{"season":2,"episode":16,"name":"The Whole Truth","air_date":"2006-03-23 02:00:00"},{"season":2,"episode":17,"name":"Lockdown","air_date":"2006-03-30 02:00:00"},{"season":2,"episode":18,"name":"Dave","air_date":"2006-04-06 01:00:00"},{"season":2,"episode":19,"name":"S.O.S.","air_date":"2006-04-13 01:00:00"},{"season":2,"episode":20,"name":"Two for the Road","air_date":"2006-05-04 01:00:00"},{"season":2,"episode":21,"name":"?","air_date":"2006-05-11 01:00:00"},{"season":2,"episode":22,"name":"Three Minutes","air_date":"2006-05-18 01:00:00"},{"season":2,"episode":23,"name":"Live Together, Die Alone (1)","air_date":"2006-05-25 00:00:00"},{"season":2,"episode":24,"name":"Live Together, Die Alone (2)","air_date":"2006-05-25 01:00:00"},{"season":3,"episode":1,"name":"A Tale of Two Cities","air_date":"2006-10-05 01:00:00"},{"season":3,"episode":2,"name":"The Glass Ballerina","air_date":"2006-10-12 01:00:00"},{"season":3,"episode":3,"name":"Further Instructions","air_date":"2006-10-19 01:00:00"},{"season":3,"episode":4,"name":"Every Man for Himself","air_date":"2006-10-26 01:00:00"},{"season":3,"episode":5,"name":"The Cost of Living","air_date":"2006-11-02 02:00:00"},{"season":3,"episode":6,"name":"I Do","air_date":"2006-11-09 02:00:00"},{"season":3,"episode":7,"name":"Not in Portland","air_date":"2007-02-08 03:00:00"},{"season":3,"episode":8,"name":"Flashes Before Your Eyes","air_date":"2007-02-15 03:00:00"},{"season":3,"episode":9,"name":"Stranger in a Strange Land","air_date":"2007-02-22 03:00:00"},{"season":3,"episode":10,"name":"Tricia Tanaka is Dead","air_date":"2007-03-01 03:00:00"},{"season":3,"episode":11,"name":"Enter 77","air_date":"2007-03-08 03:00:00"},{"season":3,"episode":12,"name":"Par Avion","air_date":"2007-03-15 02:00:00"},{"season":3,"episode":13,"name":"The Man from Tallahassee","air_date":"2007-03-22 02:00:00"},{"season":3,"episode":14,"name":"Exposé","air_date":"2007-03-29 02:00:00"},{"season":3,"episode":15,"name":"Left Behind","air_date":"2007-04-05 02:00:00"},{"season":3,"episode":16,"name":"One of Us","air_date":"2007-04-12 02:00:00"},{"season":3,"episode":17,"name":"Catch-22","air_date":"2007-04-19 02:00:00"},{"season":3,"episode":18,"name":"D.O.C.","air_date":"2007-04-26 02:00:00"},{"season":3,"episode":19,"name":"The Brig","air_date":"2007-05-03 02:00:00"},{"season":3,"episode":20,"name":"The Man Behind the Curtain","air_date":"2007-05-10 02:00:00"},{"season":3,"episode":21,"name":"Greatest Hits","air_date":"2007-05-17 02:00:00"},{"season":3,"episode":22,"name":"Through the Looking Glass (1)","air_date":"2007-05-24 01:00:00"},{"season":3,"episode":23,"name":"Through the Looking Glass (2)","air_date":"2007-05-24 01:00:00"},{"season":4,"episode":1,"name":"The Beginning of the End","air_date":"2008-02-01 02:00:00"},{"season":4,"episode":2,"name":"Confirmed Dead","air_date":"2008-02-08 02:00:00"},{"season":4,"episode":3,"name":"The Economist","air_date":"2008-02-15 02:00:00"},{"season":4,"episode":4,"name":"Eggtown","air_date":"2008-02-22 02:00:00"},{"season":4,"episode":5,"name":"The Constant","air_date":"2008-02-29 02:00:00"},{"season":4,"episode":6,"name":"The Other Woman","air_date":"2008-03-07 02:00:00"},{"season":4,"episode":7,"name":"Ji Yeon","air_date":"2008-03-14 01:00:00"},{"season":4,"episode":8,"name":"Meet Kevin Johnson","air_date":"2008-03-21 01:00:00"},{"season":4,"episode":9,"name":"The Shape of Things to Come","air_date":"2008-04-25 02:00:00"},{"season":4,"episode":10,"name":"Something Nice Back Home","air_date":"2008-05-02 02:00:00"},{"season":4,"episode":11,"name":"Cabin Fever","air_date":"2008-05-09 02:00:00"},{"season":4,"episode":12,"name":"Theres No Place Like Home (1)","air_date":"2008-05-16 02:00:00"},{"season":4,"episode":13,"name":"Theres No Place Like Home (2)","air_date":"2008-05-30 01:00:00"},{"season":4,"episode":14,"name":"Theres No Place Like Home (3)","air_date":"2008-05-30 01:00:00"},{"season":5,"episode":1,"name":"Because You Left","air_date":"2009-01-22 02:00:00"},{"season":5,"episode":2,"name":"The Lie","air_date":"2009-01-22 02:00:00"},{"season":5,"episode":3,"name":"Jughead","air_date":"2009-01-29 02:00:00"},{"season":5,"episode":4,"name":"The Little Prince","air_date":"2009-02-05 02:00:00"},{"season":5,"episode":5,"name":"This Place is Death","air_date":"2009-02-12 02:00:00"},{"season":5,"episode":6,"name":"316","air_date":"2009-02-19 02:00:00"},{"season":5,"episode":7,"name":"The Life and Death of Jeremy Bentham","air_date":"2009-02-26 02:00:00"},{"season":5,"episode":8,"name":"LaFleur","air_date":"2009-03-05 02:00:00"},{"season":5,"episode":9,"name":"Namaste","air_date":"2009-03-19 01:00:00"},{"season":5,"episode":10,"name":"Hes Our You","air_date":"2009-03-26 01:00:00"},{"season":5,"episode":11,"name":"Whatever Happened, Happened","air_date":"2009-04-02 01:00:00"},{"season":5,"episode":12,"name":"Dead is Dead","air_date":"2009-04-09 01:00:00"},{"season":5,"episode":13,"name":"Some Like It Hoth","air_date":"2009-04-16 01:00:00"},{"season":5,"episode":14,"name":"The Variable","air_date":"2009-04-30 01:00:00"},{"season":5,"episode":15,"name":"Follow the Leader","air_date":"2009-05-07 01:00:00"},{"season":5,"episode":16,"name":"The Incident (1)","air_date":"2009-05-14 01:00:00"},{"season":5,"episode":17,"name":"The Incident (2)","air_date":"2009-05-14 01:00:00"},{"season":6,"episode":1,"name":"LA X (1)","air_date":"2010-02-03 01:00:00"},{"season":6,"episode":2,"name":"LA X (2)","air_date":"2010-02-03 02:00:00"},{"season":6,"episode":3,"name":"What Kate Does","air_date":"2010-02-10 02:00:00"},{"season":6,"episode":4,"name":"The Substitute","air_date":"2010-02-17 02:00:00"},{"season":6,"episode":5,"name":"Lighthouse","air_date":"2010-02-24 02:00:00"},{"season":6,"episode":6,"name":"Sundown","air_date":"2010-03-03 02:00:00"},{"season":6,"episode":7,"name":"Dr. Linus","air_date":"2010-03-10 02:00:00"},{"season":6,"episode":8,"name":"Recon","air_date":"2010-03-17 01:00:00"},{"season":6,"episode":9,"name":"Ab Aeterno","air_date":"2010-03-24 01:00:00"},{"season":6,"episode":10,"name":"The Package","air_date":"2010-03-31 01:00:00"},{"season":6,"episode":11,"name":"Happily Ever After","air_date":"2010-04-07 01:00:00"},{"season":6,"episode":12,"name":"Everybody Loves Hugo","air_date":"2010-04-14 01:00:00"},{"season":6,"episode":13,"name":"The Last Recruit","air_date":"2010-04-21 01:00:00"},{"season":6,"episode":14,"name":"The Candidate","air_date":"2010-05-05 01:00:00"},{"season":6,"episode":15,"name":"Across the Sea","air_date":"2010-05-12 01:00:00"},{"season":6,"episode":16,"name":"What They Died For","air_date":"2010-05-19 01:00:00"},{"season":6,"episode":17,"name":"The End (1)","air_date":"2010-05-24 01:00:00"},{"season":6,"episode":18,"name":"The End (2)","air_date":"2010-05-24 01:00:00"}]}';

    private string $searchResultJson = '{"total":"1000","page":2,"pages":50,"tv_shows":[{"id":70634,"name":"All the Feels by the Dodo","permalink":"all-the-feels-by-the-dodo","start_date":"2020-04-06","end_date":null,"country":"US","network":"Quibi","status":"Ended","image_thumbnail_path":"https://static.episodate.com/images/no-image.png"},{"id":47566,"name":"Along For The Ride","permalink":"along-for-the-ride","start_date":"Feb/14/2015","end_date":"","country":"NZ","network":"ONE","status":"TBD/On The Bubble","image_thumbnail_path":"https://static.episodate.com/images/no-image.png"},{"id":30941,"name":"American Colony: Meet the Hutterites","permalink":"american-colony-meet-the-hutterites","start_date":"May/29/2012","end_date":"","country":"US","network":"National Geographic Channel","status":"TBD/On The Bubble","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/30941.jpg"},{"id":30604,"name":"Ancient Egypt: Life and Death in the Valley of the Kings","permalink":"mardock-scramble","start_date":"2013-03-22","end_date":null,"country":"JP","network":"BBC Two","status":"Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/30604.jpg"},{"id":42501,"name":"Andrew Marr’s Great Scots: The Writers Who Shaped A Nation","permalink":"andrew-marr-s-great-scots-the-writers-who-shaped-a-nation","start_date":"Aug/16/2014","end_date":"","country":"UK","network":"BBC TWO","status":"TBD/On The Bubble","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/42501.jpg"},{"id":39765,"name":"Animals Through the Night: Sleepover at the Zoo","permalink":"animals-through-the-night-sleepover-at-the-zoo","start_date":"Mar/03/2014","end_date":"","country":"UK","network":"BBC FOUR","status":"Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/39765.jpg"},{"id":44087,"name":"Annabel Langbein: The Free Range Cook","permalink":"annabel-langbein-the-free-range-cook","start_date":"Aug/29/2011","end_date":"","country":"NZ","network":"ONE","status":"TBD/On The Bubble","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/44087.jpg"},{"id":31528,"name":"Antiques to the Rescue","permalink":"antiques-to-the-rescue","start_date":"Sep/27/2012","end_date":"","country":"UK","network":"BBC TWO","status":"TBD/On The Bubble","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/31528.jpg"},{"id":862,"name":"Arc the Lad","permalink":"arc-the-lad","start_date":"Apr/05/1999","end_date":"Oct/11/1999","country":"JP","network":"WOWOW","status":"Canceled/Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/862.jpg"},{"id":43939,"name":"Architects Of The Divine: The First Gothic Age","permalink":"architects-of-the-divine-the-first-gothic-age","start_date":"Oct/28/2014","end_date":"Oct/28/2014","country":"UK","network":"BBC FOUR","status":"Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/43939.jpg"},{"id":43686,"name":"Are You The One? The Aftermatch","permalink":"are-you-the-one-the-aftermatch","start_date":"Oct/06/2014","end_date":"","country":"US","network":"MTV","status":"New Series","image_thumbnail_path":"https://static.episodate.com/images/no-image.png"},{"id":60464,"name":"Art, Passion & Power: The Story of the Royal Collection","permalink":"art-passion-power-the-story-of-the-royal-collection","start_date":"2018-01-16","end_date":null,"country":"GB","network":"BBC Four","status":"Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/60464.jpg"},{"id":7524,"name":"Arthur! And the Square Knights of the Round Table","permalink":"arthur-and-the-square-knights-of-the-round-table","start_date":"1966","end_date":"1968","country":"AU","network":"Unknown","status":"Canceled/Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/7524.jpg"},{"id":629,"name":"At Last the 1948 Show","permalink":"at-last-the-1948-show","start_date":"1967-02-15","end_date":null,"country":"UK","network":"ITV1","status":"Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/629.jpg"},{"id":21345,"name":"Attack on Terror: The FBI vs. the Ku Klux Klan","permalink":"attack-on-terror-the-fbi-vs-the-ku-klux-klan","start_date":"Feb/20/1975","end_date":"Feb/21/1975","country":"US","network":"CBS","status":"Canceled/Ended","image_thumbnail_path":"https://static.episodate.com/images/no-image.png"},{"id":17572,"name":"Auschwitz: The Nazis and the \'Final Solution\'","permalink":"auschwitz-the-nazis-and-the-final-solution","start_date":"Jan/11/2005","end_date":"Feb/15/2005","country":"UK","network":"BBC TWO","status":"Canceled/Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/17572.jpg"},{"id":30705,"name":"Australia: The Time Traveller\'s Guide","permalink":"australia-the-time-traveller-s-guide","start_date":"Mar/25/2012","end_date":"","country":"AU","network":"ABC","status":"TBD/On The Bubble","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/30705.jpg"},{"id":31015,"name":"Babies in the Office","permalink":"babies-in-the-office","start_date":"Jul/16/2012","end_date":"","country":"UK","network":"BBC TWO","status":"TBD/On The Bubble","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/31015.jpg"},{"id":74863,"name":"Banished from the Heroes\' Party, I Decided to Live a Quiet Life in the Countryside","permalink":"banished-from-the-heroes-party-i-decided-to-live-a-quiet-life-in-the-countryside","start_date":"2021-10-06","end_date":null,"country":"US","network":"AT-X","status":"Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/74863.jpg"},{"id":994,"name":"Barbara Mandrell and the Mandrell Sisters","permalink":"barbara-mandrell-and-the-mandrell-sisters","start_date":"1980-11-18","end_date":null,"country":"US","network":"NBC","status":"Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/994.jpg"}]}';

    public function test_tv_show_data_is_working()
    {

        // from json
        //        $dd = TVShowData::from('{"name":"lost","start_date":"2021-10-01", "status": "New Series","last_aired_ep":{"season":6,"episode":18,"name":"The End (2)","air_date":"2010-05-24 01:00:00"}}');
        $showData = TVShowData::from($this->showInfoJson);

        $this->assertEquals('Lost', $showData->name);
        $this->assertEquals('lost', $showData->permalink);
        $this->assertEquals('The End (2)', $showData->last_aired_ep->name);
        $this->assertInstanceOf(EpisodeData::class, $showData->last_aired_ep);
        $this->assertInstanceOf(EpisodeData::class, $showData->next_ep);
        $this->assertInstanceOf(Carbon::class, $showData->start_date);
        $this->assertInstanceOf(TVShowStatus::class, $showData->status);
        $this->assertInstanceOf(DataCollection::class, $showData->episodes);
        $this->assertInstanceOf(EpisodeData::class, $showData->episodes->first());
        $this->assertIsArray($showData->genres);
        $this->assertIsArray($showData->pictures);
        $this->assertNotEmpty($showData->image_url);
        $this->assertNotEmpty($showData->thumb_url);

        // from db
        if (TVShow::count() < 1) {
            try {
                TVShow::factory(3)->create();
            } catch (Exception $e) {
            }
        }

        $showFromDB = TVShowData::from(TVShow::inRandomOrder()->first());
        $this->assertHasAllFields($showFromDB->toArray());

    }

    protected function assertHasAllFields(array $data): void
    {
        $allFields = array_keys($data);
        $allRequiredFieldNames = ['name', 'permalink', 'description', 'status', 'country', 'network', 'thumb_url', 'image_url',
            'start_date', 'end_date', 'next_ep_date', 'last_aired_ep', 'next_ep', 'genres', 'pictures', 'episodes'];
        $hasAllFields = ! array_diff($allRequiredFieldNames, $allFields);
        $this->assertTrue($hasAllFields);
    }

    public function test_create_and_update_tv_show_is_working()
    {
        // make it possible to assert that an event is dispatched
        Event::fake();

        $showInfoArray = json_decode($this->showInfoJson, true);
        // setting lathe data on a limited field
        $showInfoArray['network'] = 'SOME LARGE DATA WHICH SHOULD BE LIMIT WHILE SAVING';
        $creator = new CreateOrUpdateTVShow($showInfoArray);

        // assert events are dispatched
        if ($creator->getCreationStatus() == CreateOrUpdateStatus::Created) {
            Event::assertDispatched(TVShowCreated::class);
        } elseif ($creator->getCreationStatus() == CreateOrUpdateStatus::Updated) {
            Event::assertDispatched(TVShowUpdated::class);
        }

        $createdTVShow = $creator->getShowOnDB();
        $this->assertInstanceOf(TVShow::class, $createdTVShow);
        $this->assertInstanceOf(CarbonImmutable::class, $createdTVShow->last_check_date);
        $this->assertInstanceOf(CarbonImmutable::class, $createdTVShow->next_ep_date);
        // assert that last_check_date is updated to now
        $this->assertEquals(now()->format('Y-m-d H:i'), $createdTVShow->last_check_date->format('Y-m-d H:i'));
        $this->assertEquals('2023-07-05', $createdTVShow->next_ep_date->format('Y-m-d'));
        $this->assertHasAllFields($createdTVShow->toArray());

        // assert preparing data before save is working
        self::assertEquals('SOME LARGE DATA WHICH SHOULD B', $createdTVShow->network);

    }

    public function test_search_results_is_working()
    {
        $searchResult = SearchTVShowData::from($this->searchResultJson);

        $this->assertEquals(1000, $searchResult->total);
        $this->assertEquals(2, $searchResult->page);
        $this->assertEquals(50, $searchResult->pages);
        $this->assertCount(20, $searchResult->tv_shows);
        $this->assertInstanceOf(DataCollection::class, $searchResult->tv_shows);
        $this->assertInstanceOf(TVShowData::class, $searchResult->tv_shows->first());
    }
}
