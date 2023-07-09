<?php

namespace App\TVSHow\RemoteData;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class RemoteRequest
{
    private $response;

    public function __construct(protected string $url) {

    }

    public function sendRequest(): bool {
        $this->response = Http::get($this->url);
        return $this->response->ok();
    }

    /**
     * @return mixed
     */
    public function getResponse() : Response {
        return $this->response;
    }


}
