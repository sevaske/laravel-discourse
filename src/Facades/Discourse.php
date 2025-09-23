<?php

namespace Sevaske\LaravelDiscourse\Facades;

use Illuminate\Support\Facades\Facade;
use Sevaske\Discourse\Services\Api;
use Sevaske\Discourse\Services\Signer;
use Sevaske\LaravelDiscourse\Contracts\DiscourseUser;

/**
 * @method static Signer signer()
 * @method static Api api()
 * @method static string connect(DiscourseUser|array $user, string $sso)
 *
 * @see Discourse
 */
class Discourse extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'discourse';
    }
}
