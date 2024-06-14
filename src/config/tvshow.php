<?php

return [
    'api_url' => [
        'search' => env('EPISODATE_SEARCH_URL', "https://www.episodate.com/api/search?q="),
        'tvshow_info' => env('EPISODATE_TVSHOW_INFO_URL', "https://www.episodate.com/api/show-details?q="),
        'most_popular' => env('EPISODATE_MOST_POPULAR_URL', "https://www.episodate.com/api/most-popular"),
    ],

    'crawl_min_cache_hours' => env('CRAWL_MIN_CACHE_HOURS', 8),
];
