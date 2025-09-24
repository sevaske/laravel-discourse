<?php

namespace Sevaske\LaravelDiscourse\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Sevaske\Discourse\Exceptions\InvalidRequestSignature;
use Sevaske\Discourse\Services\Signer;

class VerifySsoSignature
{
    public function __construct(protected Signer $signer) {}

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     *
     * @throws InvalidRequestSignature
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (! $payload = $request->query('sso')) {
            throw new InvalidRequestSignature('The payload is not passed');
        }

        if (! $signature = $request->query('sig')) {
            throw new InvalidRequestSignature('The signature is not passed');
        }

        if (! $this->signer->validate($signature, $payload)) {
            throw new InvalidRequestSignature('The signature is invalid.');
        }

        return $next($request);
    }
}
