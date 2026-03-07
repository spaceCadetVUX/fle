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
        if (
            $request->is('login') ||
            $request->is('logout') ||
            $request->is('register') ||
            $request->is('password/*') ||
            $request->is('admin') ||
            $request->is('admin/*') ||
            $request->is('dashboard') ||
            $request->is('card') ||
            $request->is('profile') ||
            $request->is('profile/*')
        ) {
            return $next($request);
        }

        $supportedLocales = ['vi', 'en'];
        $locale = $request->segment(1);

        if (!in_array($locale, $supportedLocales)) {
            return redirect('/vi/' . ltrim($request->path(), '/'), 301);
        }

        App::setLocale($locale);

        return $next($request);
    }
}

