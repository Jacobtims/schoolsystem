<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Redirect;
use Str;

class TeacherController extends Controller
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
            'field' => 'in:firstname,lastname,abbreviation,student_name,email,phone_number',
            'direction' => 'in:asc,desc'
        ]);

        $teacherRole = Role::whereName('Teacher')->firstOrFail();
        $teachers = User::whereRoleId($teacherRole->id)
            ->join('teachers', 'users.id', '=', 'teachers.user_id')
            ->selectRaw('users.*, teachers.abbreviation as abbreviation, teachers.student_name as student_name')
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('firstname', 'LIKE', '%'.$request->get('search').'%');
            })
            ->when($request->has(['field', 'direction']), function ($query) use ($request) {
                $query->orderBy($request->get('field'), $request->get('direction'));
            })
            ->paginate(10);

        return Inertia::render('Admin/Teachers/Overview', [
            'teachers' => $teachers,
            'filters' => $request->all(['search', 'field', 'direction'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTeacherRequest $request
     * @return RedirectResponse
     */
    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        $teacherRole = Role::whereName('Teacher')->firstOrFail();

        if ($request->get('generatePassword') == true) {
            $password = Str::random(8);
        } else {
            $password = $request->get('password');
        }
        $passwordHash = Hash::make($password);

        $request->merge(['password' => $passwordHash, 'role_id' => $teacherRole->id]);
        $user = User::create($request->only(['email', 'firstname', 'lastname', 'phone_number', 'date_of_birth', 'country', 'state', 'city', 'zipcode', 'street', 'password', 'role_id']));
        Teacher::create(['user_id' => $user->id, 'abbreviation' => $request->get('abbreviation'), 'student_name' => $request->get('student_name')]);

        if ($request->get('sendEmail') == true) {
            //TODO: Send e-mail to email-address...
        }

        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTeacherRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(UpdateTeacherRequest $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->fill($request->only(['email', 'firstname', 'lastname', 'phone_number', 'date_of_birth', 'country', 'state', 'city', 'zipcode', 'street']));
        $user->save();

        $teacher = Teacher::findOrFail($user->teacher->id);
        $teacher->fill($request->only(['abbreviation', 'student_name']));
        $teacher->save();

        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return Redirect::back();
    }
}
