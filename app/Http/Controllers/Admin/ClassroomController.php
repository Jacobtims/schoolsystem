<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassroomRequest;
use App\Models\Classroom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClassroomController extends Controller
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
            'search' => 'string|max:255'
        ]);

        $classrooms = Classroom::withTrashed()->when($request->has('search'), function ($query) use ($request) {
            $query->where('name', 'LIKE', '%'.$request->get('search').'%');
        })->orderBy('deleted_at')->limit(100)->get();

        return Inertia::render('Admin/Classrooms/Overview', [
            'classrooms' => $classrooms
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreClassroomRequest $request
     * @return RedirectResponse
     */
    public function store(StoreClassroomRequest $request): RedirectResponse
    {
        Classroom::create($request->only(['name']));

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $subject = Classroom::findOrFail($id);
        $subject->delete();

        return back();
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function restore(int $id): RedirectResponse
    {
        $subject = Classroom::withTrashed()->findOrFail($id);
        $subject->restore();

        return back();
    }
}
