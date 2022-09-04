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

                // Check to which dashboard the user should be redirected
                if (Auth::user()->is_admin) {
                    $home = route('admin.dashboard');
                } elseif (Auth::user()->is_teacher) {
                    $home = route('teacher.dashboard');
                } elseif (Auth::user()->is_student) {
                    $home = route('student.dashboard');
                } else {
                    Auth::logout();
                    $home = route('login');
                }

                return redirect($home);
            }
        }

        return $next($request);
    }
}
