<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Inertia\Inertia;

class GradeController extends Controller
{
    public function index(): \Inertia\Response
    {
        $subjects = Subject::whereHas('grades', function ($q) {
            $q->where('student_id', auth()->user()->student->id);
        })->with('grades', function ($q) {
            $q->where('student_id', auth()->user()->student->id)->with(['assignment', 'assignment.subject', 'teacher']);
        })->get();

        return Inertia::render('Student/Grades/Index', compact('subjects'));
    }
}
