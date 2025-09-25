<?php

namespace Sevaske\LaravelDiscourse\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Sevaske\Discourse\Services\Api;
use Sevaske\Discourse\Services\Signer;
use Sevaske\Discourse\Services\WebhookSigner;
use Sevaske\LaravelDiscourse\Discourse;
use Sevaske\LaravelDiscourse\Exceptions\InvalidConfigurationException;
use Sevaske\LaravelDiscourse\Http\Middleware\VerifySsoSignature;
use Sevaske\LaravelDiscourse\Http\Middleware\VerifyWebhookSignature;
use Sevaske\LaravelDiscourse\Services\SsoService;

class DiscourseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // merge config (equivalent of ->hasConfigFile())
        $this->mergeConfigFrom(__DIR__ . '/../../config/discourse.php', 'discourse');

        // sso signer
        $this->app->singleton(Signer::class, fn ($app) =>
        new Signer($app['config']->get('discourse.sso.secret'))
        );

        // webhook signer
        $this->app->singleton(WebhookSigner::class, fn ($app) =>
        new WebhookSigner($app['config']->get('discourse.webhook.secret'))
        );

        // api client
        $this->app->singleton(Api::class, function ($app) {
            $config = $app['config']->get('discourse');

            if (empty($config['base_url']) || empty($config['api_key']) || empty($config['api_username'])) {
                throw new InvalidConfigurationException('Discourse API configuration is missing.');
            }

            $httpFactory = new HttpFactory;
            $client = new Client([
                'base_uri' => $config['base_url'],
                'headers' => [
                    'Api-Key' => $config['api_key'],
                    'Api-Username' => $config['api_username'],
                ],
            ]);

            return new Api($client, $httpFactory, $httpFactory);
        });

        // sso service
        $this->app->singleton(SsoService::class, fn ($app) =>
        new SsoService($app->make(Signer::class))
        );

        // main class
        $this->app->singleton(Discourse::class, fn ($app) =>
        new Discourse(apiFactory: fn () => $app->make(Api::class))
        );

        // facade accessor
        $this->app->alias(Discourse::class, 'discourse');
    }

    public function boot(Router $router): void
    {
        // load routes (equivalent of ->hasRoute())
        $this->loadRoutesFrom(__DIR__ . '/../../routes/discourse.php');

        // register middlewares
        $router->aliasMiddleware('discourse.sso.signature', VerifySsoSignature::class);
        $router->aliasMiddleware('discourse.webhook.signature', VerifyWebhookSignature::class);

        // publish config
        $this->publishes([
            __DIR__ . '/../../config/discourse.php' => config_path('discourse.php'),
        ], 'discourse-config');
    }
}
