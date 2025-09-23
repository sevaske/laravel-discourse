<?php

namespace Sevaske\LaravelDiscourse\Api;

use GuzzleHttp\Client;
use Sevaske\LaravelDiscourse\Traits\Http;

abstract class ApiService
{
    use Http;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
