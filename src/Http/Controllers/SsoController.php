<?php

namespace Sevaske\LaravelDiscourse\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sevaske\LaravelDiscourse\Contracts\DiscourseUser;
use Sevaske\LaravelDiscourse\Facades\Discourse;
use Sevaske\LaravelDiscourse\Events\DiscourseSsoValidated;
use Sevaske\LaravelDiscourse\Services\SsoService;

class SsoController extends Controller
{
    public function __invoke(Request $request, SsoService $connectService)
    {
        $sso = $request->query('sso');
        $user = $request->user();
        $discourseUser = $connectService->normalizeUser($user);

        $redirectTo = Discourse::connect($sso, $discourseUser);

        event(new DiscourseSsoValidated($sso, $user, $redirectTo));

        return redirect($redirectTo);
    }
}
