<?php

namespace Codexshaper\Tenancy\Middleware;

use Codexshaper\Tenancy\Models\Hostname;
use Closure;

class IdentifyHostname
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
        if(Hostname::identifyHostname($request)) {
            return $next($request);
        }

        return abort(404);
    }
}
