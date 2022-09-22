<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\SessionTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    use SessionTrait;

    public function index(Request $request): Response
    {
        $sessions = $this->collectSessions($request);
        $role = auth()->user()->role->name;

        return Inertia::render('User/Profile', compact('sessions', 'role'));
    }

    public function logoutOtherBrowserSessions(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password'
        ]);

        $this->deleteOtherSessionRecords($request);

        return back();
    }
}
