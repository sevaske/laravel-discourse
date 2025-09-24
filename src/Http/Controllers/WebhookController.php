<?php

namespace Sevaske\LaravelDiscourse\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sevaske\LaravelDiscourse\Events\DiscourseWebhookReceived;

class WebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        event(new DiscourseWebhookReceived(
            payload: (array) $request->json()?->all(),
            eventName: $request->header('X-Discourse-Event'),
            eventType: $request->header('X-Discourse-Event-Type'),
            eventId: $request->header('X-Discourse-Event-Id')
        ));

        return response()->noContent();
    }
}
