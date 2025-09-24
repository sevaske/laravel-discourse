# Laravel Discourse

[![Packagist](https://img.shields.io/packagist/v/sevaske/laravel-discourse.svg)](https://packagist.org/packages/sevaske/laravel-discourse)
[![License](https://img.shields.io/github/license/sevaske/laravel-discourse.svg)](LICENSE)

**Laravel wrapper for [sevaske/discourse](https://github.com/sevaske/discourse).**  
This package provides simple integration of Discourse API and SSO (Single Sign-On) into your Laravel application.  
For a full list of available API endpoints and features, see the core [sevaske/discourse](https://github.com/sevaske/discourse) package.


## ✨ Features

- 🔑 **Discourse Connect (SSO)**
    - Full support for [Discourse SSO](https://meta.discourse.org/t/official-single-sign-on-for-discourse-sso/13045): signing and validating payloads.
    - Built-in `SsoController`, fully configurable via `.env`:
        - `DISCOURSE_SSO_ENABLED` — enable/disable the SSO route.
        - `DISCOURSE_SSO_URI` — the route path (default: `/discourse/sso`).
        - `DISCOURSE_SSO_CONTROLLER` — controller class handling the request.
        - `DISCOURSE_SSO_MIDDLEWARE` — comma-separated list of middleware (default: `web, auth, discourse.sso.signature`).
    - Middleware:
        - `discourse.sso.signature` — validates the incoming SSO signature.

- 📡 **API client integration**
    - Access the full Discourse API through `$discourse->api()`.
    - Covers categories, users, posts, groups, private messages, webhooks, and more.
    - Automatically signs requests with your API key and username.

- ⚡ **Laravel-ready**
    - Service Provider auto-registration.
    - Facade `Discourse` for clean syntax.
    - Middleware aliases registered automatically.

- 🧩 **Built on top of [`sevaske/discourse`](https://github.com/sevaske/discourse)**
    - All low-level functionality extracted into a framework-agnostic core package.
    - This wrapper adds Laravel integration, convenience, and conventions.

## 📦 Installation

Install via Composer:

```bash
composer require sevaske/laravel-discourse
```


## ⚙️ Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag="discourse-config"
```

It creates the config file `config/discourse.php`:

```php
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
        'controller' => env('DISCOURSE_SSO_CONTROLLER', \Sevaske\LaravelDiscourse\Http\Controllers\SsoController::class),
        'middleware' => array_map('trim', explode(',', env(
            'DISCOURSE_SSO_MIDDLEWARE',
            'discourse.sso.enabled, web, auth, discourse.sso.signature'
        ))),

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
```

Update your .env:
```bash
DISCOURSE_BASE_URI=https://your-discourse-url.com
DISCOURSE_API_KEY=your-api-key
DISCOURSE_API_USERNAME=system
DISCOURSE_SECRET=super-secret
DISCOURSE_SSO_ENABLED=false
```


## 🚀 Usage

You can use the `Discourse` facade or resolve it from the container.

### API

This Laravel wrapper exposes the full power of [**sevaske/discourse**](https://github.com/sevaske/discourse).  
Refer to its documentation for a complete list of API endpoints and usage examples.

Example: 

```php
use Sevaske\LaravelDiscourse\Facades\Discourse;

// Get categories
$response = Discourse::api()->categories()->list();

// Create a user
$response = Discourse::api()->users()->create(
    name: 'John Doe',
    email: 'john@example.com',
    password: 'secret123',
    username: 'john'
);
```

### Discourse SSO

This package ships a ready-to-use route for **Discourse Connect (SSO)**.  
The route is registered from `routes/discourse.php` **only when** `config('discourse.sso.enabled')` is `true`.

#### ⚙️ How it works

1. Discourse calls your app at `{DISCOURSE_SSO_URI}` with `sso` and `sig`.
2. Middleware stack (default: `web, auth, discourse.sso.signature`) is applied.
3. The controller (`DISCOURSE_SSO_CONTROLLER`) builds the signed response.
4. User is redirected back to Discourse.

#### 🎯 Customization

- **Route URI** → `DISCOURSE_SSO_URI`
- **Controller** → `DISCOURSE_SSO_CONTROLLER`
- **Middleware** → `DISCOURSE_SSO_MIDDLEWARE`

For example, you can create your own controller and return JSON instead of redirect.

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Sevaske\LaravelDiscourse\Facades\Discourse;

class CustomSsoController
{
    public function __invoke(Request $request)
    {
        $redirectTo = Discourse::connect($request->query('sso'), $request->user());

        return response()->json(['to_connect' => $redirectTo]);
    }
}
```


## 🛠 Testing

```bash
composer test
```