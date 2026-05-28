<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserLevel
{
    public function handle(Request $request, Closure $next, string $level): Response
    {
        $allowedLevels = array_map('intval', explode(',', $level));

        if (! in_array((int) $request->session()->get('auth_level'), $allowedLevels, true)) {
            abort(403);
        }

        return $next($request);
    }
}
