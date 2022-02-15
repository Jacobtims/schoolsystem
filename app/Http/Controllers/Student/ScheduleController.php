<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\StandardLesson;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
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

        // Week dates
        $now = Carbon::now();
        $monday = $now->startOfWeek(CarbonInterface::MONDAY)->format('Y-m-d');
        $friday = $now->endOfWeek(CarbonInterface::FRIDAY)->format('Y-m-d');

        $period = CarbonPeriod::create($monday, $friday);
        $dates = $period->toArray();

        return Inertia::render('Student/Schedule', [
            'lessons' => $lessons,
            'standardLessons' => $standardLessons,
            'dates' => $dates
        ]);
    }
}
