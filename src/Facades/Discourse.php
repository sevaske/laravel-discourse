<?php

namespace Sevaske\LaravelDiscourse\Facades;

use Illuminate\Support\Facades\Facade;

class Discourse extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'discourse';
    }
}
