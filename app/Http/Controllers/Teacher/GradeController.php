<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\StoreGradesRequest;
use App\Models\Assignment;
use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Validator;

class GradeController extends Controller
{
    /**
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        $schoolClasses = SchoolClass::withCount('students')->get();

        return Inertia::render('Teacher/Grades/Overview', [
            'classes' => $schoolClasses
        ]);
    }

    /**
     * @param string $class
     * @param Request $request
     * @return \Inertia\Response
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
        $assignments = Assignment::with('grades')->where('subject_id', $subject->id)->where('school_class_id', $schoolClass->id)->get();

        return Inertia::render('Teacher/Grades/Show', [
            'subjects' => $subjects,
            'subject' => $subject->name,
            'schoolClass' => $schoolClass,
            'assignments' => $assignments,
        ]);
    }

    /**
     * @param StoreAssignmentRequest $request
     * @return RedirectResponse
     */
    public function storeAssignment(StoreAssignmentRequest $request): RedirectResponse
    {
        $subject = Subject::whereName($request->get('subject'))->firstOrFail();
        $schoolClass = SchoolClass::whereName($request->get('school_class'))->firstOrFail();

        Assignment::create([
            'subject_id' => $subject->id,
            'school_class_id' => $schoolClass->id,
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'weighting' => $request->get('weighting'),
        ]);

        return back();
    }

    /**
     * @param StoreGradesRequest $request
     * @return RedirectResponse
     */
    public function store(StoreGradesRequest $request): RedirectResponse
    {
        $grades = $request->get('grades');

        // Loop through all the grades.
        // Create a new one if not exist or update if exist
        foreach ($grades as $assignment_id => $assignment) {
            foreach ($assignment as $student_id => $grade) {
                if (!empty($grade['number'])) {
                    Grade::updateOrCreate(
                        [
                            'id' => $grade['id'] ?? null,
                            'assignment_id' => $assignment_id
                        ],
                        [
                            'assignment_id' => $assignment_id,
                            'student_id' => $student_id,
                            'teacher_id' => 1,
                            'number' => $grade['number']
                        ]
                    );
                }
            }
        }
        return back();
    }
}
