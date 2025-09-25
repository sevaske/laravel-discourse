<?php

namespace Sevaske\LaravelDiscourse\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sevaske\Discourse\Exceptions\InvalidArgumentException;
use Sevaske\LaravelDiscourse\Events\DiscourseSsoValidated;
use Sevaske\LaravelDiscourse\Facades\Discourse;
use Sevaske\LaravelDiscourse\Services\SsoService;

class SsoController extends Controller
{
    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(Request $request, SsoService $ssoService)
    {
        $sso = $request->query('sso');
        $user = $ssoService->normalizeUser($request->user())->toArray();
        $redirectTo = Discourse::connect($sso, $user);

        event(new DiscourseSsoValidated($sso, $user, $redirectTo));

        return redirect($redirectTo);
    }
}
