<?php

namespace Sevaske\LaravelDiscourse\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
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
    /**
     * @throws ContainerExceptionInterface
     * @throws InvalidConfigurationException
     * @throws NotFoundExceptionInterface
     */
    public function register(): void
    {
        // merge config (equivalent of ->hasConfigFile())
        $this->mergeConfigFrom(__DIR__.'/../../config/discourse.php', 'discourse');

        $config = (array) $this->app['config']->get('discourse');

        // sso
        if ($config['sso']['enabled']) {
            if (! $ssoSecret = $config['sso']['secret']) {
                throw new InvalidConfigurationException('Discourse sso secret is missing.');
            }

            $this->app->singleton(Signer::class, fn () => new Signer($ssoSecret));
            $this->app->singleton(SsoService::class, function ($app) {
                return new SsoService($app->make(Signer::class));
            });
        }

        // webhook
        if ($config['webhook']['enabled']) {
            if (! $webhookSecret = $config['webhook']['secret']) {
                throw new InvalidConfigurationException('Discourse webhook secret is missing.');
            }

            $this->app->singleton(WebhookSigner::class, function () use ($webhookSecret) {
                return new WebhookSigner($webhookSecret);
            });
        }

        // api client & facade
        if ($config['base_url']) {
            if (empty($config['api_key']) || empty($config['api_username'])) {
                throw new InvalidConfigurationException('Discourse API configuration is missing.');
            }

            $this->app->singleton(Api::class, function () use ($config) {
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

            // main class & facade alias
            $this->app->singleton(Discourse::class, function ($app) {
                return new Discourse(apiFactory: fn() => $app->make(Api::class));
            });

            $this->app->alias(Discourse::class, 'discourse');
        }
    }

    public function boot(Router $router): void
    {
        // load routes (equivalent of ->hasRoute())
        $this->loadRoutesFrom(__DIR__.'/../../routes/discourse.php');

        // register middlewares
        $router->aliasMiddleware('discourse.sso.signature', VerifySsoSignature::class);
        $router->aliasMiddleware('discourse.webhook.signature', VerifyWebhookSignature::class);

        // publish config
        $this->publishes([
            __DIR__.'/../../config/discourse.php' => config_path('discourse.php'),
        ], 'discourse-config');
    }
}
