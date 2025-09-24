<?php

use Illuminate\Support\Facades\Route;

// discourse connect (SSO)
if (config('discourse.sso.enabled')) {
    Route::get(config('discourse.sso.uri'), config('discourse.sso.controller'))
        ->middleware(config('discourse.sso.middleware'))
        ->name('discourse.sso');
}