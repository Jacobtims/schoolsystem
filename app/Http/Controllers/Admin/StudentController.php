<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Redirect;
use Str;

class StudentController extends Controller
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
            'field' => 'in:firstname,lastname,student_id,email,phone_number,sex',
            'direction' => 'in:asc,desc'
        ]);

        $studentRole = Role::whereName('Student')->firstOrFail();
        $students = User::whereRoleId($studentRole->id)
            ->join('students', 'users.id', '=', 'students.user_id')
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('firstname', 'LIKE', '%'.$request->get('search').'%');
            })
            ->when($request->has(['field', 'direction']), function ($query) use ($request) {
                $query->orderBy($request->get('field'), $request->get('direction'));
            })
            ->select(['users.id', 'students.id as student_id', 'firstname', 'lastname', 'sex', 'email', 'phone_number', 'street', 'zipcode', 'city', 'state', 'country', 'date_of_birth'])
            ->paginate(10);

        return Inertia::render('Admin/Students/Overview', [
            'students' => $students,
            'filters' => $request->all(['search', 'field', 'direction'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreStudentRequest $request
     * @return RedirectResponse
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $studentRole = Role::whereName('Student')->firstOrFail();

        if ($request->get('generatePassword') == true) {
            $password = Str::random(8);
        } else {
            $password = $request->get('password');
        }
        $passwordHash = Hash::make($password);

        $request->merge(['password' => $passwordHash, 'role_id' => $studentRole->id]);
        $user = User::create($request->only(['email', 'firstname', 'lastname', 'phone_number', 'date_of_birth', 'country', 'state', 'city', 'zipcode', 'street', 'password', 'role_id']));
        Student::create(['user_id' => $user->id]);

        if ($request->get('sendEmail') == true) {
            //TODO: Send e-mail to email-address...
        }

        return Redirect::back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateStudentRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(UpdateStudentRequest $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->fill($request->only(['email', 'firstname', 'lastname', 'phone_number', 'date_of_birth', 'country', 'state', 'city', 'zipcode', 'street']));
        $user->save();

        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
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
}
