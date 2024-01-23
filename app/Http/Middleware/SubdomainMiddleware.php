<?php

namespace App\Http\Middleware;

use App\Enums\UserRolesEnum;
use App\Environment;
use Closure;
use Illuminate\Support\Facades\Auth;

class SubdomainMiddleware
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
        if (!isset($_SERVER['HTTP_HOST'])) {
            abort(404);
        }
        $host = $_SERVER['HTTP_HOST'];
        $siteEnv = Environment::where('subdomain', $host)->where("active", 1)->first();
        if (!isset($siteEnv['id'])) {
            abort(404);
        }
        setSiteEnv($siteEnv);
        return $next($request);
    }
}
