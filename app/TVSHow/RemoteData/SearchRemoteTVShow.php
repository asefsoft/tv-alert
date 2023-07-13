<?php

namespace App\TVSHow\RemoteData;

use App\Data\SearchTVShowData;
use App\Data\TVShowData;

class SearchRemoteTVShow
{

    private string $errorMessage = '';
    public function __construct(protected string $query) { }

    public function getSearchData() : SearchTVShowData | null {
        // tv show info remote api url
        $remoteUrl = sprintf("%s%s", config('tvshow.api_url.search'), $this->query);

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
