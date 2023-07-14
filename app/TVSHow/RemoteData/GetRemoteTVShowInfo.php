<?php

namespace App\TVShow\RemoteData;

use App\Data\TVShowData;

class GetRemoteTVShowInfo
{

    private string $errorMessage = '';
    public function __construct(protected string $permalink) { }

    public function getTVShowInfo() : TVShowData | null {
        // tv show info remote api url
        $remoteUrl = sprintf("%s%s", config('tvshow.api_url.tvshow_info'), $this->permalink);

        // send remote request
        $request = new RemoteRequest($remoteUrl);
        if ($request->sendRequest()) {
            $result = $request->getResponse()->json();
            try {
                // parse into tvshow
                $TVShowData = TVShowData::from($result['tvShow']);

                // could not parse
            } catch (\Exception $e) {
                $this->errorMessage = "Empty or invalid result from remote: " . $e->getMessage();
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
