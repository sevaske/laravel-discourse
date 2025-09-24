<?php

use Illuminate\Http\Request;
use Sevaske\Discourse\Exceptions\InvalidRequestSignature;
use Sevaske\Discourse\Services\Signer;
use Sevaske\LaravelDiscourse\Http\Middleware\VerifySsoSignature;

it('throws if sso payload is missing', function () {
    $signer = Mockery::mock(Signer::class);
    $middleware = new VerifySsoSignature($signer);

    $request = Request::create('/discourse/sso', 'GET', [
        // no 'sso'
        'sig' => 'somesig',
    ]);

    $middleware->handle($request, fn () => 'next');
})->throws(InvalidRequestSignature::class, 'The payload is not passed');

it('throws if signature is missing', function () {
    $signer = Mockery::mock(Signer::class);
    $middleware = new VerifySsoSignature($signer);

    $request = Request::create('/discourse/sso', 'GET', [
        'sso' => 'payload',
        // no 'sig'
    ]);

    $middleware->handle($request, fn () => 'next');
})->throws(InvalidRequestSignature::class, 'The signature is not passed');

it('throws if signature is invalid', function () {
    $signer = Mockery::mock(Signer::class);
    $signer->shouldReceive('validate')
        ->once()
        ->with('bad-sig', 'payload')
        ->andReturn(false);

    $middleware = new VerifySsoSignature($signer);

    $request = Request::create('/discourse/sso', 'GET', [
        'sso' => 'payload',
        'sig' => 'bad-sig',
    ]);

    $middleware->handle($request, fn () => 'next');
})->throws(InvalidRequestSignature::class, 'The signature is invalid.');

it('passes if signature is valid', function () {
    $signer = Mockery::mock(Signer::class);
    $signer->shouldReceive('validate')
        ->once()
        ->with('valid-sig', 'payload')
        ->andReturn(true);

    $middleware = new VerifySsoSignature($signer);

    $request = Request::create('/discourse/sso', 'GET', [
        'sso' => 'payload',
        'sig' => 'valid-sig',
    ]);

    $result = $middleware->handle($request, fn () => 'next-called');

    expect($result)->toBe('next-called');
});
