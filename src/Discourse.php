<?php

namespace Sevaske\LaravelDiscourse;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Sevaske\Discourse\Exceptions\DiscourseException;
use Sevaske\Discourse\Services\Api;
use Sevaske\Discourse\Services\Signer;
use Sevaske\LaravelDiscourse\Contracts\DiscourseUser;
use Sevaske\LaravelDiscourse\Services\SsoService;

class Discourse
{
    use Macroable;

    protected ?Api $api = null;

    public function __construct(protected Signer $signer, protected Closure $apiFactory) {}

    public function signer(): Signer
    {
        return $this->signer;
    }

    public function api(): Api
    {
        if ($this->api === null) {
            $this->api = ($this->apiFactory)();
        }

        return $this->api;
    }


    /**
     * @throws DiscourseException
     */
    public function connect(string $sso, Authenticatable|DiscourseUser|Collection|array $user): string
    {
        return app(SsoService::class)->connect($sso, $user);
    }
}
