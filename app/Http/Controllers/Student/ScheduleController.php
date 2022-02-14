<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\StandardLesson;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    /**
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        $lessons = Lesson::whereStudentId(Auth::user()->student->id)->with('time')->get()->groupBy(['date', 'time']);
        $standardLessons = StandardLesson::get();

        return Inertia::render('Student/Schedule', [
            'lessons' => $lessons,
            'standardLessons' => $standardLessons
        ]);
    }
}
