<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Redirect;

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
            'field' => 'in:firstname,lastname,student.id,email,phone_number,street',
            'direction' => 'in:asc,desc'
        ]);

        $students = User::whereRoleId(3)
                        ->when($request->has('search'), function ($query) use ($request) {
                            $query->where('firstname', 'LIKE', '%'.$request->get('search').'%');
                        })->when($request->has(['field', 'direction']), function ($query) use ($request) {
                            $query->orderBy($request->get('field'), $request->get('direction'));
                        })->with('student')->paginate(15);

        return Inertia::render('Admin/Students/Overview', [
            'students' => $students,
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
     * @param StoreStudentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreStudentRequest $request)
    {
        $studentRole = Role::whereName('Student')->firstOrFail();

        $request->merge(['password' => 'Please change', 'role_id' => $studentRole->id]);
        $user = User::create($request->only(['email', 'firstname', 'lastname', 'phone_number', 'date_of_birth', 'country', 'state', 'city', 'zipcode', 'street', 'password', 'role_id']));

        Student::create(['user_id' => $user->id]);

        return Redirect::route('admin.students.index');
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
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
