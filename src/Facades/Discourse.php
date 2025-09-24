<?php

namespace Sevaske\LaravelDiscourse\Facades;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Sevaske\Discourse\Services\Api;
use Sevaske\LaravelDiscourse\Contracts\DiscourseUser;

/**
 * @method static Api api()
 * @method static string connect(string $sso, Authenticatable|DiscourseUser|Collection|array $user)
 *
 * @see \Sevaske\LaravelDiscourse\Discourse
 */
class Discourse extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'discourse';
    }
}
