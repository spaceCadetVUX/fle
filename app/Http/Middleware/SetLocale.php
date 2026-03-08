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
        $supportedLocales = ['vi', 'en'];
        $locale = $request->segment(1);

        if (!in_array($locale, $supportedLocales)) {
            return redirect('/vi/' . ltrim($request->path(), '/'), 301);
        }

        \Illuminate\Support\Facades\URL::defaults(['locale' => $locale]);
        App::setLocale($locale);

        return $next($request);
    }
}

