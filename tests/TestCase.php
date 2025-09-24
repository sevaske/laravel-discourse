<?php

namespace Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Sevaske\LaravelDiscourse\Providers\DiscourseServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            DiscourseServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {

    }
}
