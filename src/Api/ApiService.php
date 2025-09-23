<?php

namespace Sevaske\LaravelDiscourse\Api;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Sevaske\LaravelDiscourse\Contracts\DiscourseResponseContract;
use Sevaske\LaravelDiscourse\Http\DiscourseResponse;

class ApiService
{
    public function __construct(protected Client $client) {}

    protected function get(string $uri, array $params = []): DiscourseResponseContract
    {
        $response = $this->client->get($uri, ['query' => $this->filterData($params)]);

        return $this->decodeResponse($response);
    }

    protected function post(string $uri, array $data = []): DiscourseResponseContract
    {
        $response = $this->client->post($uri, ['json' => $this->filterData($data)]);

        return $this->decodeResponse($response);
    }

    protected function put(string $uri, array $data = []): DiscourseResponseContract
    {
        $response = $this->client->put($uri, ['json' => $this->filterData($data)]);

        return $this->decodeResponse($response);
    }

    protected function delete(string $uri, array $data = []): DiscourseResponseContract
    {
        $response = $this->client->delete($uri, ['json' => $this->filterData($data)]);

        return $this->decodeResponse($response);
    }

    protected function filterData(array $data): array
    {
        return array_filter(
            $data,
            static fn ($value) => $value !== null
        );
    }

    protected function decodeResponse(ResponseInterface $response): DiscourseResponseContract
    {
        return new DiscourseResponse($response);
    }
}
