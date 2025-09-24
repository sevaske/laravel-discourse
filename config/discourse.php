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

        // user attributes to provide discourse
        'user' => [
            // required:
            'id' => env('DISCOURSE_SSO_USER_ID', 'id'), // external ID
            'email' => env('DISCOURSE_SSO_USER_EMAIL', 'email'), // verified email
            // optional:
            'name' =>  env('DISCOURSE_SSO_USER_NAME'),
            'username' =>  env('DISCOURSE_SSO_USER_USERNAME'),
            'avatar_url' =>  env('DISCOURSE_SSO_USER_AVATAR_URL'),
            'bio' =>  env('DISCOURSE_SSO_USER_BIO'),
            'admin' =>  env('DISCOURSE_SSO_USER_ADMIN'),
            'moderator' =>  env('DISCOURSE_SSO_USER_MODERATOR'),
        ],
    ],
];
