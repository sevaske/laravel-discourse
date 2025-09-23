<?php

namespace Sevaske\LaravelDiscourse\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EnsureDiscourseSsoEnabled
{
    public function handle(Request $request, Closure $next)
    {
        if (! config('discourse.sso.enabled', true)) {
            throw new NotFoundHttpException(); // 404
        }

        return $next($request);
    }
}
