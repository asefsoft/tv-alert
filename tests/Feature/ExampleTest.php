<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Data\TVShowData;
use App\Models\TVShow;
use App\TVShow\Crawling\MainCrawler;
use TeamTNT\TNTSearch\TNTSearch;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_internals()
    {
        $this->markTestSkipped('temporarily test');

        $tnt =  new TNTSearch();
        $tnt->loadConfig([
            "storage" => storage_path(),
        ]);

        $tnt->selectIndex("tv_shows.index");
        $tnt->asYouType = true;
        $term = "the big bang theory";
        $tnt->search($term);
        $tnt->searchBoolean($term);

        $a = TVShow::factory()->create();
        exit();

        // update last_aired_ep of all tv shows
        //        $shows = TVShow::where('episodes', '<>', '[]')->orderBy('updated_at')->take(4000)->get();
        //        foreach ($shows as $showData) {
        //            if(isset($showData->episodes) && count($showData->episodes)){
        //                $last_aired_ep = collect(array_reverse($showData->episodes))->firstWhere("air_date","<=", now()->endOfDay());
        //                $showData->last_aired_ep = $last_aired_ep;
        //                $showData->last_ep_date = $last_aired_ep['air_date'] ?? null;
        //
        //                $next_ep = collect($showData->episodes)->firstWhere("air_date",">=", now()->startOfDay());
        //                if(!empty($next_ep)){
        //                    $showData->next_ep = $next_ep;
        //                    $showData->next_ep_date = $next_ep['air_date'] ?? null;
        //                }
        //
        //                $showData->updated_at = now();
        //                $showData->save();
        //
        //                $a=1;
        //            }
        //        }

        exit;

        MainCrawler::crawlMostPopular(200);

        $seriesInfo = '{"id":4228,"name":"Lost","permalink":"lost","url":"https://www.episodate.com/tv-show/lost","description":"Out of the blackness, the first thing Jack senses is pain. Then burning sun.","description_source":null,"start_date":"2004-09-22","end_date":null,"country":"US","status":"Ended","runtime":60,"network":"ABC","youtube_link":null,"image_path":"https://static.episodate.com/images/tv-show/full/4228.jpg","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/4228.jpg","rating":"9.1667","rating_count":"30","countdown":null,"genres":["Drama","Adventure","Supernatural"],"pictures":["https://static.episodate.com/images/episode/4228-552.jpg","https://static.episodate.com/images/episode/4228-168.jpg","https://static.episodate.com/images/episode/4228-150.jpg","https://static.episodate.com/images/episode/4228-193.jpg","https://static.episodate.com/images/episode/4228-620.jpg","https://static.episodate.com/images/episode/4228-769.jpg","https://static.episodate.com/images/episode/4228-665.jpg"],"episodes":[{"season":1,"episode":1,"name":"Pilot (1)","air_date":"2004-09-23 00:00:00"},{"season":1,"episode":2,"name":"Pilot (2)","air_date":"2004-09-30 00:00:00"},{"season":1,"episode":3,"name":"Tabula Rasa","air_date":"2004-10-07 00:00:00"},{"season":1,"episode":4,"name":"Walkabout","air_date":"2004-10-14 00:00:00"},{"season":1,"episode":5,"name":"White Rabbit","air_date":"2004-10-21 00:00:00"},{"season":1,"episode":6,"name":"House of the Rising Sun","air_date":"2004-10-28 00:00:00"},{"season":1,"episode":7,"name":"The Moth","air_date":"2004-11-04 01:00:00"},{"season":1,"episode":8,"name":"Confidence Man","air_date":"2004-11-11 01:00:00"},{"season":1,"episode":9,"name":"Solitary","air_date":"2004-11-18 01:00:00"},{"season":1,"episode":10,"name":"Raised by Another","air_date":"2004-12-02 01:00:00"},{"season":1,"episode":11,"name":"All the Best Cowboys Have Daddy Issues","air_date":"2004-12-09 01:00:00"},{"season":1,"episode":12,"name":"Whatever the Case May Be","air_date":"2005-01-06 01:00:00"},{"season":1,"episode":13,"name":"Hearts and Minds","air_date":"2005-01-13 01:00:00"},{"season":1,"episode":14,"name":"Special","air_date":"2005-01-20 01:00:00"},{"season":1,"episode":15,"name":"Homecoming","air_date":"2005-02-10 01:00:00"},{"season":1,"episode":16,"name":"Outlaws","air_date":"2005-02-17 01:00:00"},{"season":1,"episode":17,"name":"...In Translation","air_date":"2005-02-24 01:00:00"},{"season":1,"episode":18,"name":"Numbers","air_date":"2005-03-03 01:00:00"},{"season":1,"episode":19,"name":"Deus ex Machina","air_date":"2005-03-31 01:00:00"},{"season":1,"episode":20,"name":"Do No Harm","air_date":"2005-04-07 00:00:00"},{"season":1,"episode":21,"name":"The Greater Good","air_date":"2005-05-05 00:00:00"},{"season":1,"episode":22,"name":"Born to Run","air_date":"2005-05-12 00:00:00"},{"season":1,"episode":23,"name":"Exodus (1)","air_date":"2005-05-19 00:00:00"},{"season":1,"episode":24,"name":"Exodus (2)","air_date":"2005-05-26 00:00:00"},{"season":1,"episode":25,"name":"Exodus (3)","air_date":"2005-05-26 01:00:00"},{"season":2,"episode":1,"name":"Man of Science, Man of Faith","air_date":"2005-09-22 01:00:00"},{"season":2,"episode":2,"name":"Adrift","air_date":"2005-09-29 01:00:00"},{"season":2,"episode":3,"name":"Orientation","air_date":"2005-10-06 01:00:00"},{"season":2,"episode":4,"name":"Everybody Hates Hugo","air_date":"2005-10-13 01:00:00"},{"season":2,"episode":5,"name":"...And Found","air_date":"2005-10-20 01:00:00"},{"season":2,"episode":6,"name":"Abandoned","air_date":"2005-11-10 02:00:00"},{"season":2,"episode":7,"name":"The Other 48 Days","air_date":"2005-11-17 02:00:00"},{"season":2,"episode":8,"name":"Collision","air_date":"2005-11-24 02:00:00"},{"season":2,"episode":9,"name":"What Kate Did","air_date":"2005-12-01 02:00:00"},{"season":2,"episode":10,"name":"The 23rd Psalm","air_date":"2006-01-12 02:00:00"},{"season":2,"episode":11,"name":"The Hunting Party","air_date":"2006-01-19 02:00:00"},{"season":2,"episode":12,"name":"Fire + Water","air_date":"2006-01-26 02:00:00"},{"season":2,"episode":13,"name":"The Long Con","air_date":"2006-02-09 02:00:00"},{"season":2,"episode":14,"name":"One of Them","air_date":"2006-02-16 02:00:00"},{"season":2,"episode":15,"name":"Maternity Leave","air_date":"2006-03-02 02:00:00"},{"season":2,"episode":16,"name":"The Whole Truth","air_date":"2006-03-23 02:00:00"},{"season":2,"episode":17,"name":"Lockdown","air_date":"2006-03-30 02:00:00"},{"season":2,"episode":18,"name":"Dave","air_date":"2006-04-06 01:00:00"},{"season":2,"episode":19,"name":"S.O.S.","air_date":"2006-04-13 01:00:00"},{"season":2,"episode":20,"name":"Two for the Road","air_date":"2006-05-04 01:00:00"},{"season":2,"episode":21,"name":"?","air_date":"2006-05-11 01:00:00"},{"season":2,"episode":22,"name":"Three Minutes","air_date":"2006-05-18 01:00:00"},{"season":2,"episode":23,"name":"Live Together, Die Alone (1)","air_date":"2006-05-25 00:00:00"},{"season":2,"episode":24,"name":"Live Together, Die Alone (2)","air_date":"2006-05-25 01:00:00"},{"season":3,"episode":1,"name":"A Tale of Two Cities","air_date":"2006-10-05 01:00:00"},{"season":3,"episode":2,"name":"The Glass Ballerina","air_date":"2006-10-12 01:00:00"},{"season":3,"episode":3,"name":"Further Instructions","air_date":"2006-10-19 01:00:00"},{"season":3,"episode":4,"name":"Every Man for Himself","air_date":"2006-10-26 01:00:00"},{"season":3,"episode":5,"name":"The Cost of Living","air_date":"2006-11-02 02:00:00"},{"season":3,"episode":6,"name":"I Do","air_date":"2006-11-09 02:00:00"},{"season":3,"episode":7,"name":"Not in Portland","air_date":"2007-02-08 03:00:00"},{"season":3,"episode":8,"name":"Flashes Before Your Eyes","air_date":"2007-02-15 03:00:00"},{"season":3,"episode":9,"name":"Stranger in a Strange Land","air_date":"2007-02-22 03:00:00"},{"season":3,"episode":10,"name":"Tricia Tanaka is Dead","air_date":"2007-03-01 03:00:00"},{"season":3,"episode":11,"name":"Enter 77","air_date":"2007-03-08 03:00:00"},{"season":3,"episode":12,"name":"Par Avion","air_date":"2007-03-15 02:00:00"},{"season":3,"episode":13,"name":"The Man from Tallahassee","air_date":"2007-03-22 02:00:00"},{"season":3,"episode":14,"name":"Exposé","air_date":"2007-03-29 02:00:00"},{"season":3,"episode":15,"name":"Left Behind","air_date":"2007-04-05 02:00:00"},{"season":3,"episode":16,"name":"One of Us","air_date":"2007-04-12 02:00:00"},{"season":3,"episode":17,"name":"Catch-22","air_date":"2007-04-19 02:00:00"},{"season":3,"episode":18,"name":"D.O.C.","air_date":"2007-04-26 02:00:00"},{"season":3,"episode":19,"name":"The Brig","air_date":"2007-05-03 02:00:00"},{"season":3,"episode":20,"name":"The Man Behind the Curtain","air_date":"2007-05-10 02:00:00"},{"season":3,"episode":21,"name":"Greatest Hits","air_date":"2007-05-17 02:00:00"},{"season":3,"episode":22,"name":"Through the Looking Glass (1)","air_date":"2007-05-24 01:00:00"},{"season":3,"episode":23,"name":"Through the Looking Glass (2)","air_date":"2007-05-24 01:00:00"},{"season":4,"episode":1,"name":"The Beginning of the End","air_date":"2008-02-01 02:00:00"},{"season":4,"episode":2,"name":"Confirmed Dead","air_date":"2008-02-08 02:00:00"},{"season":4,"episode":3,"name":"The Economist","air_date":"2008-02-15 02:00:00"},{"season":4,"episode":4,"name":"Eggtown","air_date":"2008-02-22 02:00:00"},{"season":4,"episode":5,"name":"The Constant","air_date":"2008-02-29 02:00:00"},{"season":4,"episode":6,"name":"The Other Woman","air_date":"2008-03-07 02:00:00"},{"season":4,"episode":7,"name":"Ji Yeon","air_date":"2008-03-14 01:00:00"},{"season":4,"episode":8,"name":"Meet Kevin Johnson","air_date":"2008-03-21 01:00:00"},{"season":4,"episode":9,"name":"The Shape of Things to Come","air_date":"2008-04-25 02:00:00"},{"season":4,"episode":10,"name":"Something Nice Back Home","air_date":"2008-05-02 02:00:00"},{"season":4,"episode":11,"name":"Cabin Fever","air_date":"2008-05-09 02:00:00"},{"season":4,"episode":12,"name":"Theres No Place Like Home (1)","air_date":"2008-05-16 02:00:00"},{"season":4,"episode":13,"name":"Theres No Place Like Home (2)","air_date":"2008-05-30 01:00:00"},{"season":4,"episode":14,"name":"Theres No Place Like Home (3)","air_date":"2008-05-30 01:00:00"},{"season":5,"episode":1,"name":"Because You Left","air_date":"2009-01-22 02:00:00"},{"season":5,"episode":2,"name":"The Lie","air_date":"2009-01-22 02:00:00"},{"season":5,"episode":3,"name":"Jughead","air_date":"2009-01-29 02:00:00"},{"season":5,"episode":4,"name":"The Little Prince","air_date":"2009-02-05 02:00:00"},{"season":5,"episode":5,"name":"This Place is Death","air_date":"2009-02-12 02:00:00"},{"season":5,"episode":6,"name":"316","air_date":"2009-02-19 02:00:00"},{"season":5,"episode":7,"name":"The Life and Death of Jeremy Bentham","air_date":"2009-02-26 02:00:00"},{"season":5,"episode":8,"name":"LaFleur","air_date":"2009-03-05 02:00:00"},{"season":5,"episode":9,"name":"Namaste","air_date":"2009-03-19 01:00:00"},{"season":5,"episode":10,"name":"Hes Our You","air_date":"2009-03-26 01:00:00"},{"season":5,"episode":11,"name":"Whatever Happened, Happened","air_date":"2009-04-02 01:00:00"},{"season":5,"episode":12,"name":"Dead is Dead","air_date":"2009-04-09 01:00:00"},{"season":5,"episode":13,"name":"Some Like It Hoth","air_date":"2009-04-16 01:00:00"},{"season":5,"episode":14,"name":"The Variable","air_date":"2009-04-30 01:00:00"},{"season":5,"episode":15,"name":"Follow the Leader","air_date":"2009-05-07 01:00:00"},{"season":5,"episode":16,"name":"The Incident (1)","air_date":"2009-05-14 01:00:00"},{"season":5,"episode":17,"name":"The Incident (2)","air_date":"2009-05-14 01:00:00"},{"season":6,"episode":1,"name":"LA X (1)","air_date":"2010-02-03 01:00:00"},{"season":6,"episode":2,"name":"LA X (2)","air_date":"2010-02-03 02:00:00"},{"season":6,"episode":3,"name":"What Kate Does","air_date":"2010-02-10 02:00:00"},{"season":6,"episode":4,"name":"The Substitute","air_date":"2010-02-17 02:00:00"},{"season":6,"episode":5,"name":"Lighthouse","air_date":"2010-02-24 02:00:00"},{"season":6,"episode":6,"name":"Sundown","air_date":"2010-03-03 02:00:00"},{"season":6,"episode":7,"name":"Dr. Linus","air_date":"2010-03-10 02:00:00"},{"season":6,"episode":8,"name":"Recon","air_date":"2010-03-17 01:00:00"},{"season":6,"episode":9,"name":"Ab Aeterno","air_date":"2010-03-24 01:00:00"},{"season":6,"episode":10,"name":"The Package","air_date":"2010-03-31 01:00:00"},{"season":6,"episode":11,"name":"Happily Ever After","air_date":"2010-04-07 01:00:00"},{"season":6,"episode":12,"name":"Everybody Loves Hugo","air_date":"2010-04-14 01:00:00"},{"season":6,"episode":13,"name":"The Last Recruit","air_date":"2010-04-21 01:00:00"},{"season":6,"episode":14,"name":"The Candidate","air_date":"2010-05-05 01:00:00"},{"season":6,"episode":15,"name":"Across the Sea","air_date":"2010-05-12 01:00:00"},{"season":6,"episode":16,"name":"What They Died For","air_date":"2010-05-19 01:00:00"},{"season":6,"episode":17,"name":"The End (1)","air_date":"2010-05-24 01:00:00"},{"season":6,"episode":18,"name":"The End (2)","air_date":"2010-05-24 01:00:00"}]}';
        //        $dd = TVShowData::from('{"name":"lost","start_date":"2021-10-01", "status": "New Series","last_aired_ep":{"season":6,"episode":18,"name":"The End (2)","air_date":"2010-05-24 01:00:00"}}');
        $dd = TVShowData::from($seriesInfo);
        $bb = TVShowData::from(TVShow::first());
    }
}
