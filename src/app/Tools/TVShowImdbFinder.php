<?php
namespace App\Tools;

use Imdb\TitleSearch;
use Imdb\Config;

class TVShowImdbFinder
{
    protected $search;

    public function __construct()
    {
        $config = new Config();
        $config->language = "en-US";
        $this->search = new TitleSearch($config);
    }

    /**
     * Search for a TV series by title using IMDbPHP
     * @param string $title
     * @return array [found => bool, is_tv_series => bool, matched_title => string|null, imdb_id => string|null]
     */
    public function findSeries(string $title): array
    {
        $results = $this->search->search($title);
        $resultCount = count($results);

        foreach ($results as $result) {
            $isTvSeries = ($result->movietype() === 'TV Series');
            // Check if the title matches and is a TV series
            if (strcasecmp($result->title(), $title) === 0 && $isTvSeries) {

                return [
                    'found' => true,
                    'total_results' => $resultCount,
                    'is_tv_series' => $isTvSeries,
                    'seasons' => $result->seasons(),
                    'matched_title' => $result->title(),
                    'imdb_id' => $result->imdbid(),
                    'imdb_url' => sprintf("https://www.imdb.com/title/tt%s", $result->imdbid()),
//                    'photo' => $result->photo(),
                    'lang' => $result->language(),
                    'year' => $result->year(),
                    'yearspan' => $result->yearspan(),
                    'endyear' => $result->endyear(),
                    'keywords' => $result->keywords(),
                    'rating' => $result->rating(),
                    'votes' => $result->votes(),
                    'result' => $result
                ];
            }
        }
        // Not found
        return [
            'found' => false,
            'total_results' => $resultCount,
            'is_tv_series' => false,
            'matched_title' => null,
            'imdb_id' => null,
        ];
    }
}
