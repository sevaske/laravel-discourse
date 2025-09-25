<?php

namespace Sevaske\LaravelDiscourse\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sevaske\Discourse\Contracts\DiscourseExceptionContract;
use Sevaske\LaravelDiscourse\Services\SsoService;

class SsoController extends Controller
{
    /**
     * @throws DiscourseExceptionContract
     */
    public function __invoke(Request $request, SsoService $ssoService)
    {
        $sso = $request->input('sso');
        $redirectTo = $ssoService->connect($sso, $request->user());

        return redirect($redirectTo);
    }
}
