<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Discourse Base Settings
    |--------------------------------------------------------------------------
    */
    'base_url' => env('DISCOURSE_BASE_URL'),
    'api_key' => env('DISCOURSE_API_KEY'),
    'api_username' => env('DISCOURSE_API_USERNAME'),

    /*
    |--------------------------------------------------------------------------
    | Discourse Connect (SSO)
    |--------------------------------------------------------------------------
    |
    | Define the route where Discourse will redirect the user for SSO.
    |
    */
    'sso' => [
        'enabled' => env('DISCOURSE_SSO_ENABLED', false),
        'secret' => env('DISCOURSE_SSO_SECRET'),
        'uri' => env('DISCOURSE_SSO_URI', '/discourse/sso'),
        'controller' => env(
            'DISCOURSE_SSO_CONTROLLER',
            \Sevaske\LaravelDiscourse\Http\Controllers\SsoController::class
        ),
        'middleware' => env_array('DISCOURSE_SSO_MIDDLEWARE', 'web,auth,discourse.sso.signature'),

        // user attributes to provide discourse
        'user' => [
            // required:
            'id' => env('DISCOURSE_SSO_USER_ID', 'id'), // external ID
            'email' => env('DISCOURSE_SSO_USER_EMAIL', 'email'), // verified email
            // optional:
            'name' => env('DISCOURSE_SSO_USER_NAME'),
            'username' => env('DISCOURSE_SSO_USER_USERNAME'),
            'avatar_url' => env('DISCOURSE_SSO_USER_AVATAR_URL'),
            'bio' => env('DISCOURSE_SSO_USER_BIO'),
            'admin' => env('DISCOURSE_SSO_USER_ADMIN'),
            'moderator' => env('DISCOURSE_SSO_USER_MODERATOR'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Discourse Webhook
    |--------------------------------------------------------------------------
    |
    | Configure the route and options for handling incoming Discourse webhooks.
    |
    */
    'webhook' => [
        'enabled' => env('DISCOURSE_WEBHOOK_ENABLED', false),
        'secret' => env('DISCOURSE_WEBHOOK_SECRET'),
        'uri' => env('DISCOURSE_WEBHOOK_URI', '/discourse/webhook'),
        'controller' => env(
            'DISCOURSE_WEBHOOK_CONTROLLER',
            \Sevaske\LaravelDiscourse\Http\Controllers\WebhookController::class
        ),
        'middleware' => env_array('DISCOURSE_WEBHOOK_MIDDLEWARE', 'discourse.webhook.signature'),
    ],
];
