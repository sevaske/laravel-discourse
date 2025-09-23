<?php

namespace Sevaske\LaravelDiscourse;

use GuzzleHttp\Client;
use Illuminate\Support\Traits\Macroable;
use Sevaske\LaravelDiscourse\Api\AdminApi;
use Sevaske\LaravelDiscourse\Api\ApiService;
use Sevaske\LaravelDiscourse\Api\PostsApi;
use Sevaske\LaravelDiscourse\Api\SiteApi;
use Sevaske\LaravelDiscourse\Api\UsersApi;
use Sevaske\LaravelDiscourse\Traits\Http;

class Api
{
    use Http;
    use Macroable;

    /** @var array<string, ApiService> */
    protected array $apiServices = [];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function admin(): AdminApi
    {
        return $this->resolveApiService(AdminApi::class);
    }

    public function posts(): PostsApi
    {
        return $this->resolveApiService(PostsApi::class);
    }

    public function site(): SiteApi
    {
        return $this->resolveApiService(SiteApi::class);
    }

    public function users(): UsersApi
    {
        return $this->resolveApiService(UsersApi::class);
    }

    protected function resolveApiService(string $class)
    {
        if (! isset($this->apiServices[$class])) {
            $this->apiServices[$class] = new $class($this->client);
        }

        return $this->apiServices[$class];
    }
}
