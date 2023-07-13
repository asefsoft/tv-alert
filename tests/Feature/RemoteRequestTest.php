<?php

use App\Data\SearchTVShowData;
use App\Data\TVShowData;
use App\TVSHow\RemoteData\GetRemoteTVShowInfo;
use App\TVSHow\RemoteData\RemoteRequest;
use App\TVSHow\RemoteData\SearchRemoteTVShow;
use Spatie\LaravelData\DataCollection;
use Tests\TestCase;

class RemoteRequestTest extends TestCase
{
    // some fake tvshow result to be used instead of actually sending request to remote server
    protected $fakeTvShowResult = '{"tvShow":{"id":77303,"name":"Hijack","permalink":"hijack-apple-tv","url":"https://www.episodate.com/tv-show/hijack-apple-tv","description":"Told in real time, <b>Hijack </b>is a tense thriller that follows the journey of a hijacked plane as it makes its way to London over a seven-hour flight, as authorities on the ground scramble for answers. Sam Nelson is an accomplished negotiator in the business world who needs to step up and use all his guile to try and save the lives of the passengers — but his high-risk strategy could be his undoing.","description_source":null,"start_date":"2023-06-28","end_date":null,"country":"US","status":"Running","runtime":10,"network":"Apple TV+","youtube_link":null,"image_path":"https://static.episodate.com/images/tv-show/full/77303.jpg","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/77303.jpg","rating":0,"rating_count":"0","countdown":{"season":1,"episode":3,"name":"Draw a Blank","air_date":"2023-07-05 12:00:00"},"genres":["Thriller"],"pictures":["https://static.episodate.com/images/episode/77303-496.jpg","https://static.episodate.com/images/episode/77303-115.jpg","https://static.episodate.com/images/episode/77303-734.jpg","https://static.episodate.com/images/episode/77303-506.jpg","https://static.episodate.com/images/episode/77303-468.jpg","https://static.episodate.com/images/episode/77303-806.jpg"],"episodes":[{"season":1,"episode":1,"name":"Final Call","air_date":"2023-06-28 12:00:00"},{"season":1,"episode":2,"name":"3 Degrees","air_date":"2023-06-28 12:00:00"},{"season":1,"episode":3,"name":"Draw a Blank","air_date":"2023-07-05 12:00:00"},{"season":1,"episode":4,"name":"Not Responding","air_date":"2023-07-12 12:00:00"},{"season":1,"episode":5,"name":"Less Than an Hour","air_date":"2023-07-19 12:00:00"},{"season":1,"episode":6,"name":"Comply Slowly","air_date":"2023-07-26 12:00:00"},{"season":1,"episode":7,"name":"Brace Brace Brace","air_date":"2023-08-02 12:00:00"}]}}';
    protected $fakeSearchResult = '{"total":"44","page":2,"pages":3,"tv_shows":[{"id":40525,"name":"Iron Maiden: Somewhere Back in Time","permalink":"iron-maiden-somewhere-back-in-time","start_date":"Apr/25/2014","end_date":"","country":"UK","network":"sky ARTS 1","status":"New Series","image_thumbnail_path":"https://static.episodate.com/images/no-image.png"},{"id":60651,"name":"Iron Majdan","permalink":"iron-majdan","start_date":"2018-03-05","end_date":null,"country":"PL","network":"TVN","status":"Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/60651.jpg"},{"id":342,"name":"Iron Man","permalink":"iron-man","start_date":"Sep/24/1994","end_date":"Feb/24/1996","country":"US","network":"Syndicated","status":"Canceled/Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/342.jpg"},{"id":42342,"name":"Iron Man & Captain America: Heroes United","permalink":"iron-man-captain-america-heroes-united","start_date":"Jul/29/2014","end_date":"","country":"US","network":"DiSNEY.com","status":"New Series","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/42342.jpg"},{"id":27329,"name":"Iron Man (2011)","permalink":"iron-man-2011","start_date":"Jul/29/2011","end_date":"Oct/14/2011","country":"US","network":"G4","status":"Canceled/Ended","image_thumbnail_path":"https://static.episodate.com/images/no-image.png"},{"id":25659,"name":"Iron Man (JP)","permalink":"iron-man-jp","start_date":"Oct/01/2010","end_date":"Dec/17/2010","country":"JP","network":"Animax","status":"Canceled/Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/25659.jpg"},{"id":41552,"name":"Iron Man (SK)","permalink":"iron-man-sk","start_date":"Sep/03/2014","end_date":"Nov/13/2014","country":"KR","network":"KBS2","status":"Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/41552.png"},{"id":48266,"name":"Iron Man UK Bolton","permalink":"iron-man-uk-bolton","start_date":"Jul/25/2015","end_date":"","country":"UK","network":"Channel 4","status":"New Series","image_thumbnail_path":"https://static.episodate.com/images/no-image.png"},{"id":19033,"name":"Iron Man: Armored Adventures","permalink":"iron-man-armored-adventures","start_date":"2009-04-24","end_date":null,"country":"US","network":"Nicktoons","status":"Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/19033.jpg"},{"id":37134,"name":"Iron Men","permalink":"iron-men","start_date":"May/08/2012","end_date":"","country":"US","network":"Weather Channel","status":"Returning Series","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/37134.jpg"},{"id":53338,"name":"Iron Resurrection","permalink":"iron-resurrection","start_date":"2016-04-13","end_date":null,"country":"US","network":"MotorTrend","status":"Running","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/53338.jpg"},{"id":18082,"name":"Iron Ring","permalink":"iron-ring","start_date":"Mar/18/2008","end_date":"Jun/10/2008","country":"US","network":"BET","status":"Canceled/Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/18082.jpg"},{"id":14675,"name":"Iron Road","permalink":"iron-road","start_date":"2009-08-09","end_date":null,"country":"CA","network":"CBC","status":"Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/14675.jpg"},{"id":69231,"name":"Iron Sharpens Iron","permalink":"iron-sharpens-iron","start_date":"2020-04-20","end_date":null,"country":"US","network":"Quibi","status":"Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/69231.jpg"},{"id":73704,"name":"Iron Within","permalink":"iron-within","start_date":null,"end_date":null,"country":"US","network":"Warhammer+","status":"Running","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/73704.jpg"},{"id":19568,"name":"Linebarrels of Iron","permalink":"linebarrels-of-iron","start_date":"Oct/03/2008","end_date":"Mar/20/2009","country":"JP","network":"TBS","status":"Canceled/Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/19568.jpg"},{"id":37446,"name":"Marvel\'s Iron Fist","permalink":"iron-fist","start_date":"2017-03-17","end_date":null,"country":"US","network":"Netflix","status":"Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/37446.jpg"},{"id":44037,"name":"Monsters Behind the Iron Curtain","permalink":"monsters-behind-the-iron-curtain","start_date":"Oct/26/2014","end_date":"Oct/25/2014","country":"US","network":"Animal Planet","status":"Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/44037.jpg"},{"id":51525,"name":"The Celts: Blood, Iron and Sacrifice with Alice Roberts and Neil Oliver","permalink":"the-celts-blood-iron-and-sacrifice-with-alice-roberts-and-neil-oliver","start_date":"2015-10-05","end_date":null,"country":"GB","network":"BBC Two","status":"Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/51525.jpg"},{"id":49730,"name":"The Grill Iron","permalink":"the-grill-iron","start_date":"2015-09-05","end_date":null,"country":"US","network":"Cooking Channel","status":"Ended","image_thumbnail_path":"https://static.episodate.com/images/tv-show/thumbnail/49730.jpg"}]}';

    public function test_can_get_show_info_from_remote() {
        $url = "https://www.episodate.com/api/show-details?q=hijack-apple-tv";

        // in 70% of times fake the request
        if(rand(1,100) < 70) {
            Http::fake([
                // Stub a JSON response
                'episodate.com/api/show-details*' => Http::response(json_decode($this->fakeTvShowResult, true)),
            ]);
        }
        else
            echo "\nSending request...\n";

        $request = new RemoteRequest($url);
        $request->sendRequest();
        $result = $request->getResponse()->json();

        $this->assertTrue($request->getResponse()->ok());
        $this->assertIsArray($result);
        $this->assertArrayHasKey('tvShow', $result);

        $TVShowData = TVShowData::from($result['tvShow']);
        $this->assertInstanceOf(TVShowData::class, $TVShowData);
    }

    public function test_can_get_search_result_from_remote() {
        $url = "https://www.episodate.com/api/search?q=iron&page=1";

        if(rand(1,100) < 70) {
            Http::fake([
                // Stub a JSON response
                'episodate.com/api/search*' => Http::response(json_decode($this->fakeSearchResult, true)),
            ]);
        }
        else
            echo "\nSending request...\n";

        $request = new RemoteRequest($url);
        $request->sendRequest();
        $result = $request->getResponse()->json();

        $this->assertTrue($request->getResponse()->ok());
        $this->assertIsArray($result);
        $this->assertArrayHasKey('total', $result);
        $this->assertArrayHasKey('page', $result);
        $this->assertArrayHasKey('tv_shows', $result);

        $searchResults = SearchTVShowData::from($result);
        $this->assertInstanceOf(SearchTVShowData::class, $searchResults);
    }

    // in this test we use GetRemoteTVShowInfo class to get tvshow info from remote
    public function test_can_get_show_info_from_remote_class() {
        $requester = new GetRemoteTVShowInfo('hijack-apple-tv');
        $TVShowInfo = $requester->getTVShowInfo();
        $this->assertInstanceOf(TVShowData::class, $TVShowInfo);

        // some not-exist tvshow
        $requester = new GetRemoteTVShowInfo('vlodikarama');
        $TVShowInfo = $requester->getTVShowInfo();
        $this->assertNull($TVShowInfo);
        $this->assertStringStartsWith("Empty or invalid result from remote:", $requester->getErrorMessage());

    }

    // in this test we use SearchRemoteTVShow class to get search result for tvshows info from remote
    public function test_can_get_search_result_from_remote_class() {
        $requester = new SearchRemoteTVShow('pacific');
        $searchData = $requester->getSearchData();
        $this->assertInstanceOf(SearchTVShowData::class, $searchData);
        $this->assertEmpty($requester->getErrorMessage());

        // some not-exist search
        $requester = new SearchRemoteTVShow('invlodika');
        $searchData = $requester->getSearchData();
        $this->assertInstanceOf(SearchTVShowData::class, $searchData);
        $this->assertEquals(0, $searchData->total);
        $this->assertInstanceOf(DataCollection::class, $searchData->tv_shows);
        $this->assertEmpty($requester->getErrorMessage());

    }
}
