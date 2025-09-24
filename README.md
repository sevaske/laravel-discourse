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

### API Example

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

#### 📖 Full API Documentation

This Laravel wrapper exposes the full power of [**sevaske/discourse**](https://github.com/sevaske/discourse).  
Refer to its documentation for a complete list of API endpoints and usage examples.

### Discourse SSO

To validate Discourse Connect (SSO) requests, use middleware **"discourse.sso"**.

```php
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Sevaske\LaravelDiscourse\Facades\Discourse;

Route::middleware(['web', 'auth', 'discourse.sso.validate'])->get('/discourse/sso', function(Request $request){
    $redirectTo = Discourse::connect($request->query('sso'), [
        'id' => $request->user()->id,
        'email' => $request->user()->email,
    ]);
    
    return redirect($redirectTo);
});

```


## 🛠 Testing

```bash
composer test
```