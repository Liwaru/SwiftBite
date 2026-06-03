<?php

namespace App\Http\Middleware;

use App\Support\AccessControl;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureAccess
{
    public function handle(Request $request, Closure $next, string $featureKey): Response
    {
        $level = (int) $request->session()->get('auth_level');

        if (! AccessControl::allowed($level, $featureKey)) {
            abort(403);
        }

        return $next($request);
    }
}
