<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StandardLesson;
use App\Settings\GeneralSettings;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index(GeneralSettings $settings): \Inertia\Response
    {
        $settings = $settings->toCollection();
        $standardLessons = StandardLesson::all();
        $maxStudentsAutoIncrement = $this->getMinStudentsAutoIncrement();

        return Inertia::render('Admin/Settings', compact('settings', 'standardLessons', 'maxStudentsAutoIncrement'));
    }

    public function update(GeneralSettings $settings, Request $request): RedirectResponse
    {
        $request->validate([
            'school_name' => 'required|min:2|max:255'
        ]);

        $settings->school_name = $request->get('school_name');
        $settings->save();

        return back();
    }

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

    public function updateStudentsAutoIncrement(Request $request): RedirectResponse
    {
        $maxStudentsAutoIncrement = $this->getMinStudentsAutoIncrement();
        $request->validate([
            'students_increment' => 'required|integer|min:' . $maxStudentsAutoIncrement
        ]);

        DB::statement("ALTER TABLE `students` AUTO_INCREMENT = ". $request->get('students_increment') .";");

        return back();
    }

    private function getMinStudentsAutoIncrement(): int
    {
        $table = DB::select("SELECT `AUTO_INCREMENT`
                                    FROM INFORMATION_SCHEMA.TABLES
                                    WHERE TABLE_SCHEMA = '". config('database.connections.mysql.database') ."'
                                    AND TABLE_NAME = 'students';");

        return !empty($table) ? $table[0]->AUTO_INCREMENT : 0;
    }
}
