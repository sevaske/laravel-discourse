<?php

use Illuminate\Support\Facades\Route;

// discourse webhook
if (config('discourse.webhook.enabled')) {
    Route::get(config('discourse.webhook.uri'), config('discourse.webhook.controller'))
        ->middleware(config('discourse.webhook.middleware'))
        ->name('discourse.webhook');
}

// discourse connect (SSO)
if (config('discourse.sso.enabled')) {
    Route::get(config('discourse.sso.uri'), config('discourse.sso.controller'))
        ->middleware(config('discourse.sso.middleware'))
        ->name('discourse.sso');
}
