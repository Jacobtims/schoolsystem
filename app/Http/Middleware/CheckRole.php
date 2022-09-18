<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$roles
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        abort_if(!auth()->check(), 404);

        foreach ($roles as $role) {
            if (strtolower($request->user()->role->name) === strtolower($role)) {
                return $next($request);
            }
        }

        abort(404);
    }
}
