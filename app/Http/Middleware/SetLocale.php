<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{

    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['en', 'id'];
        $locale = $request->cookie('locale', 'id');
        if (!in_array($locale, $supportedLocales)) {
            $locale = 'id';
        }
        App::setLocale($locale);
        return $next($request);
    }
}
