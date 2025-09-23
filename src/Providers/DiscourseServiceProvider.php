<?php

namespace Sevaske\LaravelDiscourse\Providers;

use GuzzleHttp\Client;
use Sevaske\Discourse\Services\Signer;
use Sevaske\LaravelDiscourse\Api;
use Sevaske\LaravelDiscourse\Discourse;
use Sevaske\LaravelDiscourse\Exceptions\InvalidConfigurationException;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DiscourseServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('discourse')
            ->hasConfigFile();
    }

    public function registeringPackage(): void
    {
        $this->app->singleton(Signer::class, function ($app) {
            return new Signer($app['config']->get('discourse.secret'));
        });

        // api client
        $this->app->singleton(Api::class, function ($app) {
            $config = $app['config']->get('discourse');

            if (empty($config['base_uri']) || empty($config['api_key']) || empty($config['api_username'])) {
                throw new InvalidConfigurationException('Discourse API configuration is missing.');
            }

            $client = new Client([
                'base_uri' => $config['base_uri'],
                'headers' => [
                    'Api-Key' => $config['api_key'],
                    'Api-Username' => $config['api_username'],
                ],
            ]);

            return new Api($client);
        });

        $this->app->singleton(Discourse::class, function ($app) {
            return new Discourse($app->make(Signer::class), $app->make(Api::class));
        });

        $this->app->alias(Discourse::class, 'discourse');
    }
}
