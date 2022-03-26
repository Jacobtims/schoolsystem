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
use Illuminate\Http\Response;
use Inertia\Inertia;
use Redirect;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Inertia\Response
     */
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

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSchoolClassRequest $request
     * @return RedirectResponse
     */
    public function store(StoreSchoolClassRequest $request): RedirectResponse
    {
        $mentor = Teacher::whereAbbreviation($request->get('mentor_abbreviation'))->firstOrFail();
        $schoolClass = SchoolClass::create([
            'mentor_id' => $mentor->id,
            'name' => $request->get('class_name')
        ]);

        $studentIds = $request->get('studentIds');
        foreach ($studentIds as $studentId) {
            $student = Student::findOrFail($studentId);
//            $schoolClass->students()->syncWithoutDetaching($student->id);
            $student->school_class_id = $schoolClass->id;
            $student->save();
        }

        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $schoolClass = SchoolClass::whereId($id)->with('students.user')->firstOrFail();

        return response()->json([
            'class' => $schoolClass
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Add students to a specific school class
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function addStudents(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'studentIds' => 'array'
        ]);

        $schoolClass = SchoolClass::findOrFail($id);

        $studentIds = $request->get('studentIds');
        foreach ($studentIds as $studentId)
        {
            $student = Student::findOrFail($studentId);
//            $schoolClass->students()->syncWithoutDetaching($student->id);
            $student->school_class_id = $schoolClass->id;
            $student->save();
        }

        return Redirect::back();
    }

    /**
     * Remove a student from a specific school class
     *
     * @param int $classId
     * @param int $studentId
     * @return RedirectResponse
     */
    public function removeStudent(int $classId, int $studentId): RedirectResponse
    {
//        $schoolClass = SchoolClass::findOrFail($classId);
        $student = Student::findOrFail($studentId);

//        $schoolClass->students()->detach($student->id);
        $student->school_class_id = null;
        $student->save();

        return Redirect::back();
    }
}
