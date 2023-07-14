<?php

namespace App\TVShow\RemoteData;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class RemoteRequest
{
    private ?Response $response;
    private string $errorMessage;

    public function __construct(protected string $url) {

    }

    public function sendRequest(): bool {
        try {
            $this->response = Http::connectTimeout(3)->get($this->url);
            return $this->response->ok();
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getResponse() : Response {
        return $this->response;
    }

    public function getErrorMessage(): string {
        if(!empty($this->response))
            return $this->response->reason();

        return $this->errorMessage;
    }


}
