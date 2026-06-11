<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserLevel
{
    public function handle(Request $request, Closure $next, string ...$levels): Response
    {
        $allowedLevels = collect($levels)
            ->flatMap(fn (string $level) => explode(',', $level))
            ->map(fn (string $level) => (int) trim($level))
            ->filter(fn (int $level) => $level >= 0)
            ->values()
            ->all();

        if (! in_array((int) $request->session()->get('auth_level'), $allowedLevels, true)) {
            abort(403);
        }

        return $next($request);
    }
}
