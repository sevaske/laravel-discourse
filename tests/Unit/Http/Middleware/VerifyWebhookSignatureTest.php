<?php

use Illuminate\Http\Request;
use Sevaske\Discourse\Exceptions\InvalidRequestSignature;
use Sevaske\Discourse\Services\WebhookSigner;
use Sevaske\LaravelDiscourse\Http\Middleware\VerifyWebhookSignature;

it('throws if webhook signature header is missing', function () {
    $signer = Mockery::mock(WebhookSigner::class);
    $middleware = new VerifyWebhookSignature($signer);

    $request = Request::create('/discourse/webhook', 'POST', [], [], [], [], 'payload');

    $middleware->handle($request, fn () => 'next');
})->throws(InvalidRequestSignature::class, 'The webhook signature is not passed or empty.');

it('throws if webhook signature header is empty', function () {
    $signer = Mockery::mock(WebhookSigner::class);
    $middleware = new VerifyWebhookSignature($signer);

    $request = Request::create('/discourse/webhook', 'POST', [], [], [], [
        'HTTP_X-Discourse-Event-Signature' => '',
    ], 'payload');

    $middleware->handle($request, fn () => 'next');
})->throws(InvalidRequestSignature::class, 'The webhook signature is not passed or empty.');

it('throws if webhook signature is invalid', function () {
    $signer = Mockery::mock(WebhookSigner::class);
    $signer->shouldReceive('validate')
        ->once()
        ->with('bad-sig', 'payload')
        ->andReturn(false);

    $middleware = new VerifyWebhookSignature($signer);

    $request = Request::create('/discourse/webhook', 'POST', [], [], [], [
        'HTTP_X-Discourse-Event-Signature' => 'bad-sig',
    ], 'payload');

    $middleware->handle($request, fn () => 'next');
})->throws(InvalidRequestSignature::class, 'The webhook signature is invalid.');

it('passes if webhook signature is valid', function () {
    $signer = Mockery::mock(WebhookSigner::class);
    $signer->shouldReceive('validate')
        ->once()
        ->with('valid-sig', 'payload')
        ->andReturn(true);

    $middleware = new VerifyWebhookSignature($signer);

    $request = Request::create('/discourse/webhook', 'POST', [], [], [], [
        'HTTP_X-Discourse-Event-Signature' => 'valid-sig',
    ], 'payload');

    $result = $middleware->handle($request, fn () => 'next-called');

    expect($result)->toBe('next-called');
});
