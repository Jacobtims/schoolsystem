<?php

namespace App\Http\Controllers\Admin;

use App\Exports\StudentsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportStudentsRequest;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Imports\StudentsImport;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use DB;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Log;
use Redirect;
use Str;

class StudentController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $request->validate([
            'field' => 'in:firstname,lastname,student_id,email,phone_number,sex',
            'direction' => 'in:asc,desc'
        ]);

        $studentRole = Role::whereName('Student')->firstOrFail();
        $students = User::whereRoleId($studentRole->id)
            ->join('students', 'users.id', '=', 'students.user_id')
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('firstname', 'LIKE', '%' . $request->get('search') . '%');
            })
            ->when($request->has(['field', 'direction']), function ($query) use ($request) {
                $query->orderBy($request->get('field'), $request->get('direction'));
            })
            ->select(['users.id', 'students.id as student_id', 'firstname', 'lastname', 'sex', 'email', 'phone_number', 'street', 'zipcode', 'city', 'state', 'country', 'date_of_birth', DB::raw('users.sex as sex_raw')])
            ->paginate(10);

        return Inertia::render('Admin/Students/Overview', [
            'students' => $students,
            'filters' => $request->all(['search', 'field', 'direction'])
        ]);
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $studentRole = Role::whereName('Student')->firstOrFail();

        if ($request->get('generatePassword')) {
            $password = Str::random(8);
        } else {
            $password = $request->get('password');
        }
        $passwordHash = Hash::make($password);

        $request->merge(['password' => $passwordHash, 'role_id' => $studentRole->id]);
        $user = User::create($request->only(['email', 'firstname', 'lastname', 'sex', 'phone_number', 'date_of_birth', 'country', 'state', 'city', 'zipcode', 'street', 'password', 'role_id']));
        Student::create(['user_id' => $user->id]);

        if ($request->get('sendEmail')) {
            //TODO: Send e-mail to email-address...
        }

        return Redirect::back();
    }

    public function update(UpdateStudentRequest $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->fill($request->only(['email', 'firstname', 'lastname', 'sex', 'phone_number', 'date_of_birth', 'country', 'state', 'city', 'zipcode', 'street']));
        $user->save();

        return Redirect::back();
    }

    public function destroy(int $id): RedirectResponse
    {
        // Soft-delete user
        $user = User::findOrFail($id);
        $user->delete();

        // Soft-delete student
        $student = $user->student;
        $student->delete();

        return Redirect::back();
    }

    public function import(ImportStudentsRequest $request): RedirectResponse
    {
        // Start importing file
        $import = new StudentsImport($request->get('password'), $request->boolean('generatePassword'), $request->boolean('sendEmail'));
        $import->import($request->file('file'));

        // Log validation failures
        if ($import->failures()->count() > 0) {
            Log::error('A validation error occurred during students import. Failures: ' . $import->failures());
        }

        // Log database errors
        if ($import->errors()->count() > 0) {
            Log::error('A database error occurred during students import. Errors: ' . $import->errors());
        }

        return back()->with('data', [
            'students_count' => $import->amount,
            'errors' => ($import->failures()->count() + $import->errors()->count())
        ]);
    }

    public function export(Request $request)
    {
        return (new StudentsExport)->download('students.xlsx');
    }
}
