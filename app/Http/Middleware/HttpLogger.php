<?php

namespace App\Http\Middleware;

use Closure;

class HttpLogger
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
        return $next($request);
    }

    public function terminate($request, $response)
    {
        if (config('app.env') === 'local' &&
            config('app.http_debug')
        ) {
            $end_time = microtime(true);
            $filename = 'http-logger-' . date('Y-m-d') . '.log';
            $data = 'Time: ' . gmdate("F j, Y, g:i a") . "\n";
            $data .= 'Duration: ' . number_format($end_time - LARAVEL_START, 3) . "\n";
            $data .= 'IP Address: ' . $request->ip() . "\n";
            $data .= 'URL: ' . $request->fullUrl() . "\n";
            $data .= 'Method: ' . $request->method() . "\n";
            $data .= 'Headers: ' . json_encode($request->headers->all()) . "\n";
            $data .= 'Input: ' . $request->getContent() . "\n";
            $data .= 'Output: ' . $response->getContent() . "\n";

            if ($request->method() !== 'GET') {
                \File::append(
                    storage_path('logs' . DIRECTORY_SEPARATOR . $filename),
                    $data. "\n" . str_repeat("=", 40) . "\n\n"
                );
            }
        }
    }
}
