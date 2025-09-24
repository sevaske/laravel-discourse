<?php

namespace Sevaske\LaravelDiscourse\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Sevaske\Discourse\Exceptions\InvalidRequestSignature;
use Sevaske\Discourse\Services\WebhookSigner;

class VerifyWebhookSignature
{
    private const SIGNATURE_HEADER = 'X-Discourse-Event-Signature';

    public function __construct(protected WebhookSigner $signer) {}

    /**
     * Handle an incoming request.
     *
     * @throws InvalidRequestSignature
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $signature = $request->header(self::SIGNATURE_HEADER);

        if (! is_string($signature) || empty($signature)) {
            throw new InvalidRequestSignature('The webhook signature is not passed or empty.');
        }

        if (! $this->signer->validate($signature, (string) $request->getContent())) {
            throw new InvalidRequestSignature('The webhook signature is invalid.');
        }

        return $next($request);
    }
}
