<?php

namespace App\Http\Middleware\Api;

use Closure;

class Buyer
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth('api')->check()) {
            return response(['message' => 'Unauthenticated'], 401);
        }

        if (auth('api')->user()->role != 'ROLPB') {
            return response(['message' => 'Access Denied'], 403);
        }

        return $next($request);
    }
}
