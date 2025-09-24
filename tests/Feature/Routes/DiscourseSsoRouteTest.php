<?php

use Illuminate\Support\Facades\Route;

it('registers discourse route when enabled', function () {
    $routes = collect(Route::getRoutes())->pluck('uri');

    expect($routes)->toContain(ltrim(config('discourse.sso.uri'), '/'));
});