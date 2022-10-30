<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Lesson;
use DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        // Lessons
        $query = Lesson::query();
        $query->join('standard_lessons', 'lessons.time', '=', 'standard_lessons.id')
            ->whereDate('date', today())
            ->select([
                'lessons.id', 'lessons.subject_id', 'teacher_id', 'classroom_id',
                DB::raw('TIMESTAMP(lessons.date, standard_lessons.from) AS `start`'),
                DB::raw('TIMESTAMP(lessons.date, standard_lessons.to) AS `end`')
            ])
            ->with(['subject:id,name', 'teacher:id,abbreviation', 'classroom:id,name']);

        $query->where('school_class_id', auth()->user()->student->school_class_id);
        $events = $query->get();

        // Grades
        $grades = Grade::whereStudentId(auth()->user()->student->id)
            ->with(['assignment:id,name,subject_id', 'assignment.subject:id,name'])
            ->latest('created_at')->limit(5)->get();

        return Inertia::render('Student/Dashboard/Dashboard', compact('events', 'grades'));
    }
}
