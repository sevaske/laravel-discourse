<?php

namespace Sevaske\LaravelDiscourse\Api;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Sevaske\LaravelDiscourse\Contracts\DiscourseResponseContract;
use Sevaske\LaravelDiscourse\Http\DiscourseResponse;

class ApiService
{
    public function __construct(protected Client $client) {}

    protected function get(string $uri, array $params = [])
    {
        $response = $this->client->get($uri, ['query' => $params]);

        return $this->decodeResponse($response);
    }

    protected function post(string $uri, array $data = [])
    {
        $response = $this->client->post($uri, ['json' => $data]);

        return $this->decodeResponse($response);
    }

    protected function put(string $uri, array $data = [])
    {
        $response = $this->client->put($uri, ['json' => $data]);

        return $this->decodeResponse($response);
    }

    protected function delete(string $uri)
    {
        $response = $this->client->delete($uri);

        return $this->decodeResponse($response);
    }

    protected function decodeResponse(ResponseInterface $response): DiscourseResponseContract
    {
        return new DiscourseResponse($response);
    }
}
