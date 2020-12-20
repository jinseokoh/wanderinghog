<?php

namespace App\Http\Middleware;

use Closure;

class SetLocale
{
    const LOCALE_SIGNATURE = 'x-locale';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check header request and determine locale
        $locale = $request->hasHeader(static::LOCALE_SIGNATURE) ?
        $request->header(static::LOCALE_SIGNATURE) :
        config('app.locale');

        // set laravel locale
        app()->setLocale($locale);

        return $next($request);
    }
}
