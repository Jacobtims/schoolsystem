<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param string $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        abort_if(($role == 'admin' && !Auth::user()->is_admin), 403);
        abort_if(($role == 'teacher' && !Auth::user()->is_teacher), 403);
        abort_if(($role == 'student' && !Auth::user()->is_student), 403);

        return $next($request);
    }
}
