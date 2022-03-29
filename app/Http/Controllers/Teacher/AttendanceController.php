<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Lesson;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    /**
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        $lessons = Auth::user()->teacher->lessons()->with(['schoolClass:id,name', 'subject:id,name'])->where('date', today())->get();

        return Inertia::render('Teacher/Attendance/Overview', [
            'lessons' => $lessons
        ]);
    }

    /**
     * @param int $lesson
     * @return \Inertia\Response
     */
    public function show(int $lesson): \Inertia\Response
    {
        $lesson = Lesson::whereId($lesson)->with(['schoolClass:id,name', 'schoolClass.students.user', 'subject:id,name', 'attendances'])->firstOrFail();


        return Inertia::render('Teacher/Attendance/Register', [
            'lesson' => $lesson
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'lessonId' => 'required|exists:lessons,id',
            'present' => 'required|array',
            'present.*' => 'boolean',
            'absent' => 'required|array',
            'absent.*' => 'boolean'
        ]);

        $lesson = Lesson::findOrFail($request->get('lessonId'));

        if ($lesson->attendance_registered) {
            return back()->withErrors([
                'error' => 'Dit lesuur is al geregistreerd!'
            ]);
        }

        $teacher = Auth::user()->teacher;

        foreach ($request->get('present') as $key => $value) {
            if ($value === true) {
                Attendance::create([
                    'student_id' => $key,
                    'lesson_id' => $lesson->id,
                    'status' => true,
                    'teacher_id' => $teacher->id
                ]);
            }
        }

        foreach ($request->get('absent') as $key => $value) {
            if ($value === true) {
                Attendance::create([
                    'student_id' => $key,
                    'lesson_id' => $lesson->id,
                    'status' => false,
                    'teacher_id' => $teacher->id
                ]);
            }
        }

        $lesson->update(['attendance_registered' => true]);
        return back();
    }
}
