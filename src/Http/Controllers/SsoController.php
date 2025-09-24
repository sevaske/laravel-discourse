<?php

namespace Sevaske\LaravelDiscourse\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sevaske\Discourse\Exceptions\InvalidArgumentException;
use Sevaske\LaravelDiscourse\Facades\Discourse;
use Sevaske\LaravelDiscourse\Events\DiscourseSsoValidated;
use Sevaske\LaravelDiscourse\Services\SsoService;

class SsoController extends Controller
{
    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(Request $request, SsoService $connectService)
    {
        $sso = $request->query('sso');
        $user = $connectService->normalizeUser($request->user())->toArray();
        $redirectTo = Discourse::connect($sso, $user);

        // debugging or logging
        event(new DiscourseSsoValidated($sso, $user, $redirectTo));

        return redirect($redirectTo);
    }
}
