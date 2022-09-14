<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request): \Inertia\Response
    {
        // Return
        return Inertia::render('Teacher/Schedule');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function addHomework(Request $request): RedirectResponse
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'type' => 'required|in:homework,test,activity',
            'description' => 'required|min:2|max:1000'
        ]);

        Homework::create($request->only(['lesson_id', 'type', 'description']));

        return back();
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteHomework(int $id): RedirectResponse
    {
        $homework = Homework::findOrFail($id);
        $homework->delete();

        return back();
    }
}
