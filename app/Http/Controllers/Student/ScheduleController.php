<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\StandardLesson;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    /**
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        $lessons = Lesson::whereStudentId(Auth::user()->student->id)->with(['time', 'subject'])->get()->groupBy([function ($data) {
            return Carbon::parse($data->date)->format('Y-m-d');
        }, 'time']);
        $standardLessons = StandardLesson::get();
        $dates = [
            '2022-02-14',
            '2022-02-15',
            '2022-02-16',
            '2022-02-17',
            '2022-02-18',
        ];

        return Inertia::render('Student/Schedule', [
            'lessons' => $lessons,
            'standardLessons' => $standardLessons,
            'dates' => $dates
        ]);
    }
}
