<?php

namespace Sevaske\LaravelDiscourse\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Illuminate\Routing\Router;
use Sevaske\Discourse\Services\Api;
use Sevaske\Discourse\Services\Signer;
use Sevaske\LaravelDiscourse\Discourse;
use Sevaske\LaravelDiscourse\Exceptions\InvalidConfigurationException;
use Sevaske\LaravelDiscourse\Http\Middleware\VerifySsoSignature;
use Sevaske\LaravelDiscourse\Services\SsoService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DiscourseServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('discourse')
            ->hasRoute('discourse')
            ->hasConfigFile();
    }

    public function registeringPackage(): void
    {
        // discourse connect request signer
        $this->app->singleton(Signer::class, function ($app) {
            return new Signer($app['config']->get('discourse.secret'));
        });

        // api client
        $this->app->singleton(Api::class, function ($app) {
            $config = $app['config']->get('discourse');

            if (empty($config['base_uri']) || empty($config['api_key']) || empty($config['api_username'])) {
                throw new InvalidConfigurationException('Discourse API configuration is missing.');
            }

            $httpFactory = new HttpFactory;
            $client = new Client([
                'base_uri' => $config['base_uri'],
                'headers' => [
                    'Api-Key' => $config['api_key'],
                    'Api-Username' => $config['api_username'],
                ],
            ]);

            return new Api($client, $httpFactory, $httpFactory);
        });

        // discourse connect service
        $this->app->singleton(SsoService::class, function ($app) {
            return new SsoService($app->make(Signer::class));
        });

        // main class
        $this->app->singleton(Discourse::class, function ($app) {
            return new Discourse(
                signer: $app->make(Signer::class),
                apiFactory: fn () => $app->make(Api::class),
            );
        });

        // facade
        $this->app->alias(Discourse::class, 'discourse');
    }

    public function bootingPackage(): void
    {
        // sso middleware
        $this->app->afterResolving(Router::class, function (Router $router) {
            $router->aliasMiddleware('discourse.sso.signature', VerifySsoSignature::class);
        });
    }
}
