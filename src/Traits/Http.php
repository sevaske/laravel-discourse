<?php

namespace Sevaske\LaravelDiscourse\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Sevaske\Discourse\Exceptions\DiscourseException;
use Sevaske\LaravelDiscourse\Contracts\DiscourseResponseContract;
use Sevaske\LaravelDiscourse\Exceptions\BadApiRequestException;
use Sevaske\LaravelDiscourse\Http\DiscourseResponse;

trait Http
{
    protected Client $client;

    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @throws DiscourseException
     */
    public function request(string $method, string $uri, array $options = []): DiscourseResponseContract
    {
        $options = $this->prepareOptions($options);

        try {
            $response = $this->getClient()->request($method, $uri, $options);
        } catch (GuzzleException $e) {
            throw new BadApiRequestException($e->getMessage(), previous: $e);
        }

        return $this->decodeResponse($response);
    }

    protected function prepareOptions(array $options): array
    {
        if (isset($options['json'])) {
            $options['json'] = $this->filterData($options['json']);
        }

        if (isset($options['query'])) {
            $options['query'] = $this->filterData($options['query']);
        }

        return $options;
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
