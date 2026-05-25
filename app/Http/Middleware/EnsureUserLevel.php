<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserLevel
{
    public function handle(Request $request, Closure $next, string $level): Response
    {
        if ((int) $request->session()->get('auth_level') !== (int) $level) {
            abort(403);
        }

        return $next($request);
    }
}
