<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StandardLesson;
use App\Settings\GeneralSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingController extends Controller
{
    /**
     * @param GeneralSettings $settings
     * @return Response
     */
    public function index(GeneralSettings $settings): \Inertia\Response
    {
        $settings = $settings->toCollection();

        $standardLessons = StandardLesson::all();

        return Inertia::render('Admin/Settings', [
            'settings' => $settings,
            'standardLessons' => $standardLessons
        ]);
    }

    /**
     * @param GeneralSettings $settings
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(GeneralSettings $settings, Request $request): RedirectResponse
    {
        $request->validate([
            'school_name' => 'required|min:2|max:255'
        ]);

        $settings->school_name = $request->get('school_name');
        $settings->save();

        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateLessons(Request $request): RedirectResponse
    {
        $request->validate([
            'standardLessons' => 'required|array',
            'standardLessons.*.id' => 'required|integer',
            'standardLessons.*.from' => 'required',
            'standardLessons.*.to' => 'required'
        ]);

        foreach ($request->get('standardLessons') as $standardLesson)
        {
            StandardLesson::updateOrCreate(
                ['id' => $standardLesson['id']],
                ['from' => $standardLesson['from'], 'to' => $standardLesson['to']]
            );
        }

        return back();
    }
}
