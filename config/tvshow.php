<?php

return [
    'api_url' => [
        'search' => env('EPISODATE_SEARCH_URL'),
        'tvshow_info' => env('EPISODATE_TVSHOW_INFO_URL'),
        'most_popular' => env('EPISODATE_MOST_POPULAR_URL'),
    ],

    'crawl_min_cache_hours' => env('CRAWL_MIN_CACHE_HOURS', 8),
];
