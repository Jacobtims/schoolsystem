<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Validator;

class GradeController extends Controller
{
    public function index(): \Inertia\Response
    {
        $schoolClasses = SchoolClass::withCount('students')->get();

        return Inertia::render('Teacher/Grades/Overview', [
            'classes' => $schoolClasses
        ]);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show(string $class, Request $request): \Inertia\Response
    {
        Validator::validate(['class' =>$class], [
            'class' => 'required|exists:school_classes,name'
        ]);
        $request->validate([
            'subject' => 'nullable|string|exists:subjects,name'
        ]);

        $subjects = Subject::limit(100)->get();

        if ($request->filled('subject')) {
            $subject = $subjects->where('name', $request->get('subject'))->firstOrFail();
        } else {
            $subject = $subjects->firstOrFail();
        }

        $schoolClass = SchoolClass::whereName($class)->with('students.user:id,firstname,lastname')->firstOrFail();

        $students = $schoolClass->students->pluck('id');

        $grades = Grade::whereIn('student_id', $students)->where('subject_id', $subject->id)->get()->groupBy(['name', 'student_id']);


        return Inertia::render('Teacher/Grades/Show', [
            'subjects' => $subjects,
            'subject' => $subject->name,
            'schoolClass' => $schoolClass,
            'grades' => $grades,
        ]);
    }
}
