<?php

namespace Sevaske\LaravelDiscourse\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Sevaske\LaravelDiscourse\Contracts\DiscourseUser;

class DiscourseSsoValidated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $requestSso,
        public DiscourseUser|array $user,
        public string $redirectTo
    ) {}
}
