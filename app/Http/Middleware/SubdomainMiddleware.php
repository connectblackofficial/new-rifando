<?php

namespace App\Http\Middleware;

use App\Models\Site;
use Closure;

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
        $host = str_replace(".".env("BASE_DOMAIN"), '',$_SERVER['HTTP_HOST']) ;
        $siteEnv = Site::where('subdomain', $host)->where("active", 1)->first();
        if (!isset($siteEnv['id'])) {
            abort(404);
        }
        setSiteEnv($siteEnv);
        return $next($request);
    }
}
