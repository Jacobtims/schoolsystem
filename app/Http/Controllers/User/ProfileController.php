<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\SessionTrait;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;
use Intervention\Image\Facades\Image;
use Storage;

class ProfileController extends Controller
{
    use SessionTrait;

    public function index(Request $request): Response
    {
        $sessions = $this->collectSessions($request);
        $role = auth()->user()->role->name;

        $user = auth()->user()->only(['email', 'firstname', 'lastname', 'profile_photo_url']);

        return Inertia::render('User/Profile', compact('sessions', 'role', 'user'));
    }

    public function updateUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Update email
        auth()->user()->update([
            'email' => $request->email,
        ]);

        // Update profile photo
        $file = $request->file('profile_photo');
        if ($file !== null) {
            $image = Image::make($file)->resize(120, 120);
            $name = auth()->user()->id . '-' . now()->timestamp . '.' . $file->getClientOriginalExtension();

            Storage::disk('public')->put('/profiles/' . $name, (string)$image->encode());

            auth()->user()->update([
                'profile_photo' => $name
            ]);
        }

        return back();
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back();
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
