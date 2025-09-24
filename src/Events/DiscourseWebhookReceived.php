<?php

namespace Sevaske\LaravelDiscourse\Events;

use Illuminate\Foundation\Events\Dispatchable;

class DiscourseWebhookReceived
{
    use Dispatchable;

    public function __construct(
        public array $payload,
        public string $eventName,
        public string $eventType,
        public string $eventId,
    ) {}
}
