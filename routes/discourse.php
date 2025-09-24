<?php

use Illuminate\Support\Facades\Route;
use Sevaske\LaravelDiscourse\Http\Controllers\SsoController;

// discourse connect (SSO)
Route::get(config('discourse.sso.uri'), [SsoController::class, '__invoke'])
    ->middleware([
        'discourse.sso.enabled',
        'web',
        'auth',
        'discourse.sso.signature',
    ])
    ->name('discourse.sso');