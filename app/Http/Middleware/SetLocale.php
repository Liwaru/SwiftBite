<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale', $request->cookie('swiftbite_locale', 'id'));

        if (! in_array($locale, ['id', 'en'])) {
            $locale = 'id';
        }

        session(['locale' => $locale]);
        App::setLocale($locale);

        return $next($request);
    }
}
