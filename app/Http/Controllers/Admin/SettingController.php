<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\StandardLesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    /**
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        $settings = Setting::all(['id', 'name', 'value'])->keyBy('name');

        $standardLessons = StandardLesson::all();

        return Inertia::render('Admin/Settings', [
            'settings' => $settings,
            'standardLessons' => $standardLessons
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'school_name' => 'required|min:2|max:255'
        ]);

        Setting::whereName('school_name')->firstOrFail()
            ->update(['value' => $request->get('school_name')]);

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
