<?php

namespace Sevaske\LaravelDiscourse;

use GuzzleHttp\Client;
use Illuminate\Support\Traits\Macroable;
use Sevaske\LaravelDiscourse\Api\ApiService;
use Sevaske\LaravelDiscourse\Api\AuthApi;
use Sevaske\LaravelDiscourse\Api\PostsApi;
use Sevaske\LaravelDiscourse\Api\UsersApi;

class Api
{
    use Macroable;

    /** @var array<string, ApiService> */
    protected array $apiServices = [];

    public function __construct(protected Client $client) {}

    public function auth(): AuthApi
    {
        return $this->getApiService(AuthApi::class);
    }

    public function posts(): PostsApi
    {
        return $this->getApiService(PostsApi::class);
    }

    public function users(): UsersApi
    {
        return $this->getApiService(UsersApi::class);
    }

    protected function getApiService(string $class)
    {
        if (! isset($this->apiServices[$class])) {
            $this->apiServices[$class] = new $class($this->client);
        }

        return $this->apiServices[$class];
    }
}
