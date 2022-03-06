<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
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
