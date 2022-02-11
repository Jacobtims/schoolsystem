<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Auth;
use Inertia\Inertia;

class GradeController extends Controller
{
    /**
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        $grades = Auth::user()->student->grades()->with('subject')->orderByDesc('created_at')
            ->get()->groupBy('subject_id');

        return Inertia::render('Student/Grades', [
            'grades' => $grades
        ]);
    }
}
