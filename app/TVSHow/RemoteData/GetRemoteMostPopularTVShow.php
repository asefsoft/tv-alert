<?php

namespace App\TVShow\RemoteData;

use App\Data\SearchTVShowData;
use App\Data\TVShowData;

class GetRemoteMostPopularTVShow
{

    private string $errorMessage = '';
    public function __construct(protected int $page = 1) { }

    public function getMostPopularShows() : SearchTVShowData | null {
        // tv show most popular remote api url
        $remoteUrl = sprintf("%s?page=%s", config('tvshow.api_url.most_popular'), $this->page);

        // send remote request
        $request = new RemoteRequest($remoteUrl);
        if ($request->sendRequest()) {
            $result = $request->getResponse()->json();
            try {
                // parse into SearchTVShowData
                $TVShowData = SearchTVShowData::from($result);

                // could not parse
            } catch (\Exception $e) {
                $this->errorMessage = "Empty or invalid result from remote:" . $e->getMessage();
                return null;
            }
            return $TVShowData;
        }
        else {
            // any server or client errors?
            $this->errorMessage = $request->getErrorMessage();
            return null;
        }
    }

    public function getErrorMessage(): string {
        return $this->errorMessage;
    }
}
