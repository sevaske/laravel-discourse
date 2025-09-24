<?php

namespace Sevaske\LaravelDiscourse\Events;

use Illuminate\Foundation\Events\Dispatchable;

class DiscourseSsoValidated
{
    use Dispatchable;

    public function __construct(
        public string $requestSso,
        public array $user,
        public string $redirectTo
    ) {}
}
