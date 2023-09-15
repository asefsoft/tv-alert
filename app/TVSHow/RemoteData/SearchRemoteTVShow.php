<?php

namespace App\TVShow\RemoteData;

use App\Data\SearchTVShowData;

class SearchRemoteTVShow
{
    private string $errorMessage = '';

    public function __construct(protected string $query, protected int $page = 1)
    {
    }

    public function doSearch(): ?SearchTVShowData
    {
        // tv show search remote api url
        $remoteUrl = sprintf('%s%s&page=%s', config('tvshow.api_url.search'), $this->query, $this->page);

        // send remote request
        $request = new RemoteRequest($remoteUrl);
        if ($request->sendRequest()) {
            $result = $request->getResponse()->json();
            try {
                // parse into SearchTVShowData
                $TVShowData = SearchTVShowData::from($result);

                // could not parse
            } catch (\Exception $e) {
                $this->errorMessage = 'Empty or invalid result from remote: '. $e->getMessage();

                return null;
            }

            return $TVShowData;
        } else {
            // any server or client errors?
            $this->errorMessage = $request->getErrorMessage();

            return null;
        }
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
