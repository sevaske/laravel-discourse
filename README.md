# Laravel Discourse

[![Packagist](https://img.shields.io/packagist/v/sevaske/laravel-discourse.svg)](https://packagist.org/packages/sevaske/laravel-discourse)
[![License](https://img.shields.io/github/license/sevaske/laravel-discourse.svg)](LICENSE)

**Laravel wrapper for [sevaske/discourse](https://github.com/sevaske/discourse).**  
This package provides simple integration of Discourse API and SSO (Single Sign-On) into your Laravel application.  
For a full list of available API endpoints and features, see the core [sevaske/discourse](https://github.com/sevaske/discourse) package.


## ✨ Features

- 🔑 **SSO support** — easily sign and validate Discourse SSO payloads.
- 📡 **API client integration** — access Discourse API via `$discourse->api()`.
- ⚡ **Laravel-ready** — comes with Service Provider, Facade, and Middleware.
- 🧩 Built on top of [`sevaske/discourse`](https://github.com/sevaske/discourse).


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
    'base_uri' => env('DISCOURSE_BASE_URI', ''),
    'api_key' => env('DISCOURSE_API_KEY'),
    'api_username' => env('DISCOURSE_API_USERNAME'),
    'secret' => env('DISCOURSE_SECRET'),
];
```

Update your .env:
```bash
DISCOURSE_BASE_URI=https://your-discourse-url.com
DISCOURSE_API_KEY=your-api-key
DISCOURSE_API_USERNAME=system
DISCOURSE_SECRET=super-secret
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
    username: 'johndoe'
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