<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if (Auth::user()->is_admin) {
                    $home = '/admin/dashboard';
                } elseif (Auth::user()->is_teacher) {
                    $home = '/teacher/dashboard';
                } elseif (Auth::user()->is_student) {
                    $home = '/student/dashboard';
                } else {
                    Auth::logout();
                    $home = '/login';
                }

                return redirect($home);
            }
        }

        return $next($request);
    }
}
