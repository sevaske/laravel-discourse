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
        $app['config']->set('discourse.base_url', 'https://discourse.test/');
        $app['config']->set('discourse.api_username', 'api-username');
        $app['config']->set('discourse.sso.enabled', true);
        $app['config']->set('discourse.sso.secret', 'secret');
        $app['config']->set('discourse.sso.uri', 'discourse/connect');

    }
}
