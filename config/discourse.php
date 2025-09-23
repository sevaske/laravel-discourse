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
    'secret' => env('DISCOURSE_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Discourse SSO Route
    |--------------------------------------------------------------------------
    |
    | Define the route where Discourse will redirect the user for SSO.
    |
    */
    'sso' => [
        'enabled' => env('DISCOURSE_SSO_ENABLED', false),
        'uri' => env('DISCOURSE_SSO_URI', '/discourse/sso'),
        'middleware' => ['discourse.sso.enabled', 'web', 'auth', 'discourse.sso.validate'],

        // user attributes to provide discourse
        'user' => [
            // columns
            'id' => 'id', // external ID
            'email' => 'email',
            'name' => 'full_name',
            'username' => null,
            'avatar_url' => null,
            'bio' => null,

            // Boolean for making the user a Discourse admin. Leave null to ignore
            'admin' => null,

            // Boolean for making user a Discourse moderator. Leave null to ignore
            'moderator' => null,
        ],
    ],
];
