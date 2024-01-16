<?php

namespace App\Http\Middleware;

use App\Enums\UserRolesEnum;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdminMiddleware
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
        $user = Auth::user();
        if ($user->role == UserRolesEnum::Admin || $user->role == UserRolesEnum::SuperAdmin) {
            return $next($request);
        }
        Auth::logout();
        return redirect()->route('login');
    }
}
