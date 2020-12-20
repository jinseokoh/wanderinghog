<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckJwtHashClaim
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
        $authHeader = $request->header('Authorization');
        /** @var User $user */
        $user = $request->user();

        if ($authHeader && $user) {
            $jwt = JWTAuth::parseToken();
            $uuid = $jwt->getPayload()->get('uuid');
            if ($user->uuid && $uuid !== $user->uuid) {
                abort(403, 'Only 1 device is allowed to run.');
            }
        }

        return $next($request);
    }
}
