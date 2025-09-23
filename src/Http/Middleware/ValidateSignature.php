<?php

namespace Sevaske\LaravelDiscourse\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Sevaske\Discourse\Contracts\DiscourseExceptionContract;
use Sevaske\Discourse\Exceptions\InvalidRequestSignature;
use Sevaske\Discourse\Services\Signer;

class ValidateSignature
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     *
     * @throws DiscourseExceptionContract
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $payload = $request->query('sso')) {
            throw new InvalidRequestSignature('The payload is not passed');
        }

        if (! $signature = $request->query('sig')) {
            throw new InvalidRequestSignature('The signature is not passed');
        }

        $signer = app(Signer::class);

        if (! $signer->validate($signature, $payload)) {
            throw new InvalidRequestSignature;
        }

        return $next($request);
    }
}
