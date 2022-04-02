<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubjectRequest;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubjectController extends Controller
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

        $subjects = Subject::withTrashed()->when($request->has('search'), function ($query) use ($request) {
            $query->where('name', 'LIKE', '%'.$request->get('search').'%')
                ->orWhere('abbreviation', 'LIKE', '%'.$request->get('search').'%');
        })->orderBy('deleted_at')->limit(100)->get();

        return Inertia::render('Admin/Subjects/Overview', [
            'subjects' => $subjects
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSubjectRequest $request
     * @return RedirectResponse
     */
    public function store(StoreSubjectRequest $request): RedirectResponse
    {
        Subject::create($request->only(['name', 'abbreviation']));

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
        $subject = Subject::findOrFail($id);
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
        $subject = Subject::withTrashed()->findOrFail($id);
        $subject->restore();

        return back();
    }
}
