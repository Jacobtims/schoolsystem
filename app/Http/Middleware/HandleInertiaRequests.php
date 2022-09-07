<?php

namespace App\Http\Middleware;

use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Route;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => function () use ($request) {
                    if (auth()->check()) {
                        return array_merge($request->user()->only([
                            'firstname', 'lastname', 'profile_photo_url'
                        ]), [
                            'teacher_id' => $request->user()->is_teacher ? $request->user()->teacher->id : null
                        ]);
                    }
                }
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            // Old
            'settings' => [
                'school_name' => app(GeneralSettings::class)->school_name
            ],
            'current_route' => Route::currentRouteName()
        ]);
    }
}
