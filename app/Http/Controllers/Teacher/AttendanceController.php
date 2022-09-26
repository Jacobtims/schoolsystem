<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Lesson;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    public function index(): \Inertia\Response
    {
        $lessons = Auth::user()->teacher->lessons()->with(['schoolClass:id,name', 'subject:id,name'])
            ->where('deleted', false)->orWhereNull('deleted')->where('date', today())
            ->orderBy('time')->get();

        return Inertia::render('Teacher/Attendance/Overview', [
            'lessons' => $lessons
        ]);
    }

    public function show(int $lesson): \Inertia\Response
    {
        $lesson = Lesson::whereId($lesson)->with(['schoolClass:id,name', 'subject:id,name'])
            ->get(['id', 'time', 'school_class_id', 'subject_id'])->firstOrFail();

        $students = $lesson->schoolClass->students()->limit(100)->with('user:id,firstname,lastname,sex,profile_photo')->get();

        $absences = $lesson->absences()->get(['id', 'student_id'])->keyBy('student_id');

        return Inertia::render('Teacher/Attendance/Register', compact('lesson', 'students', 'absences'));
    }

    public function store(Lesson $lesson, Request $request): RedirectResponse
    {
        $request->validate([
            'present' => 'required|array',
            'present.*' => 'boolean'
        ]);

        $teacher = Auth::user()->teacher;

        foreach ($request->get('present') as $key => $value) {
            if ($value === false) {
                Absence::updateOrCreate(
                    [
                        'student_id' => $key,
                        'lesson_id' => $lesson->id
                    ],
                    [
                        'teacher_id' => $teacher->id
                    ]
                );
            } elseif ($value === true) {
                Absence::where('student_id', $key)->where('lesson_id', $lesson->id)->delete();
            }
        }

        $lesson->update(['attendance_registered' => true]);
        return back();
    }
}
