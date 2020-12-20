<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class ProfileJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (config('app.env') !== 'production' &&
            $response instanceof JsonResponse &&
            $request->has('_debug')
        ) {
            $debugBar = app('debugbar');

            if ($debugBar->isEnabled()) {
                $originalData = $response->getData(true);
                $debugBarData = $debugBar->getData();
                $debugData = [
                    '_debug' => Arr::only($debugBarData, 'queries')
                ];
                $response->setData($originalData + $debugData);
            }
        }

        return $response;
    }
}
