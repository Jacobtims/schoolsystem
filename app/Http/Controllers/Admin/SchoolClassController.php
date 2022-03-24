<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSchoolClassRequest;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Redirect;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'class' => 'integer'
        ]);

        $schoolClasses = SchoolClass::select(['id', 'name'])->get();

        if ($request->has('class')) {
            $schoolClass = SchoolClass::whereId($request->get('class'))->with('students.user')->firstOrFail();
        } else {
            $schoolClass = null;
        }

        return Inertia::render('Admin/SchoolClasses/Overview', [
            'schoolClasses' => $schoolClasses,
            'schoolClass' => $schoolClass,
            'oldParams' => $request->all(['class'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSchoolClassRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreSchoolClassRequest $request)
    {
        $mentor = Teacher::whereAbbreviation($request->get('mentor_abbreviation'))->firstOrFail();
        $schoolClass = SchoolClass::create([
            'mentor_id' => $mentor->id,
            'name' => $request->get('class_name')
        ]);

        $studentIds = $request->get('studentIds');
        foreach ($studentIds as $studentId) {
            $student = Student::findOrFail($studentId);
            $schoolClass->students()->syncWithoutDetaching($student->id);
        }

        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $schoolClass = SchoolClass::whereId($id)->with('students.user')->firstOrFail();

        return response()->json([
            'class' => $schoolClass
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addStudents(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'studentIds' => 'array'
        ]);

        $schoolClass = SchoolClass::findOrFail($id);

        $studentIds = $request->get('studentIds');
        foreach ($studentIds as $studentId)
        {
            $student = Student::findOrFail($studentId);
            $schoolClass->students()->syncWithoutDetaching($student->id);
        }

        return Redirect::back();
    }

    /**
     * Remove a student from a specific school class
     *
     * @param int $classId
     * @param int $studentId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeStudent(int $classId, int $studentId)
    {
        $schoolClass = SchoolClass::findOrFail($classId);
        $student = Student::findOrFail($studentId);

        $schoolClass->students()->detach($student->id);

        return Redirect::back();
    }
}
