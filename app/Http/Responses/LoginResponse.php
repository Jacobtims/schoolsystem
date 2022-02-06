<?php

namespace App\Http\Responses;

use Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * @param  $request
     * @return mixed
     */
    public function toResponse($request)
    {
        if (Auth::user()->hasRole('admin')) {
            $home = '/admin/dashboard';
        } elseif (Auth::user()->hasRole('teacher')) {
            $home = '/teacher/dashboard';
        } elseif (Auth::user()->hasRole('student')) {
            $home = '/student/dashboard';
        } else {
            $home = '/dashboard';
        }

        return redirect()->intended($home);
    }
}
