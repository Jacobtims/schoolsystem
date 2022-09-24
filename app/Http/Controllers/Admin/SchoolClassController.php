<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSchoolClassRequest;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Redirect;

class SchoolClassController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $request->validate([
            'class' => 'string'
        ]);

        $schoolClasses = SchoolClass::select(['id', 'name'])->get();

        $schoolClass = null;
        if ($request->has('class')) {
            $schoolClass = SchoolClass::whereName($request->get('class'))->with('students.user')->firstOrFail();
        }

        return Inertia::render('Admin/SchoolClasses/Overview', [
            'schoolClasses' => $schoolClasses,
            'schoolClass' => $schoolClass
        ]);
    }

    public function store(StoreSchoolClassRequest $request): RedirectResponse
    {
        $mentor = Teacher::whereAbbreviation($request->get('mentor_abbreviation'))->firstOrFail();
        $schoolClass = SchoolClass::create([
            'mentor_id' => $mentor->id,
            'name' => $request->get('class_name')
        ]);

        $students = $request->collect('students')->pluck('id');

        Student::whereIn('id', $students)->update(['school_class_id' => $schoolClass->id]);

        return Redirect::back();
    }

    public function addStudentsToSchoolClass(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'students' => 'array',
            'students.*.id' => 'integer|exists:students,id'
        ]);

        $schoolClass = SchoolClass::findOrFail($id);

        $students = $request->collect('students')->pluck('id');

        Student::whereIn('id', $students)->update(['school_class_id' => $schoolClass->id]);

        return Redirect::back();
    }

    public function removeStudentFromSchoolClass(int $studentId): RedirectResponse
    {
        $student = Student::findOrFail($studentId);

        $student->school_class_id = null;
        $student->save();

        return Redirect::back();
    }

    public function getStudents(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'nullable|string',
            'school_class' => 'nullable|exists:school_classes,id'
        ]);

        $students = Student::join('users', 'students.user_id', '=', 'users.id')
            ->when($request->get('query'), function ($q) use ($request) {
                $q->where('students.id', 'LIKE', '%' . $request->get('query') . '%')
                ->orWhere('firstname', 'LIKE', '%' . $request->get('query') . '%')
                ->orWhere('lastname', 'LIKE', '%' . $request->get('query') . '%');
            })
            ->when($request->get('school_class'), function ($q) use ($request) {
                $q->where('school_class_id', '!=', $request->get('school_class'))
                    ->orWhereNull('school_class_id');
            })
            ->limit(50)
            ->select(['students.id', 'users.firstname', 'users.lastname'])
            ->get();

        return response()->json($students);
    }
}
