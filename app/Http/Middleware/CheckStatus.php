<?php

namespace App\Http\Middleware;

use Closure;

class CheckStatus
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


        if (!$request->user()->status) {
            // abort(401, 'Status not active.');
            abort(response('Status not active.', 401));
        }
        return $next($request);
    }
}
