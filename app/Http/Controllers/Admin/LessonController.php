<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Models\Classroom;
use App\Models\Lesson;
use App\Models\SchoolClass;
use App\Models\StandardLesson;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LessonController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create(): \Inertia\Response
    {
        $standardLessons = StandardLesson::get();

        return Inertia::render('Admin/Schedule/Lessons', [
            'lessons' => $standardLessons
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreScheduleRequest $request
     * @return RedirectResponse
     */
    public function store(StoreScheduleRequest $request): RedirectResponse
    {
        $schoolClasses = $request->get('classes');
        $lessons = $request->get('lessons');
        foreach ($schoolClasses as $class) {
            foreach ($lessons as $lesson) {
                Lesson::create([
                    'school_class_id' => $class['id'],
                    'teacher_id' => $request->get('teacher')['id'],
                    'classroom_id' => $request->get('classroom')['id'],
                    'subject_id' => $request->get('subject')['id'],
                    'date' => $request->get('date'),
                    'time' => $lesson['id']
                ]);
            }
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroyMultiple(Request $request): RedirectResponse
    {
        $request->validate([
            'permanent' => 'boolean',
            'lessons' => 'required|array'
        ]);

        if ($request->get('permanent') == true) {
            foreach ($request->get('lessons') as $lesson) {
                Lesson::whereId($lesson['id'])->delete();
            }
        } else {
            foreach ($request->get('lessons') as $lesson) {
                $lesson = Lesson::find($lesson['id']);
                $lesson->deleted = true;
                $lesson->save();
            }
        }

        return back();
    }

    /**
     * Get school classes for multiselect
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getSchoolClasses(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'string|nullable|max:255'
        ]);

        $schoolClasses = SchoolClass::when($request->has('query'), function ($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request->get('query') . '%');
        })->limit(300)->get();

        return response()->json($schoolClasses);
    }

    /**
     * Get teachers for multiselect
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTeachers(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'string|nullable|max:255'
        ]);

        $teachers = Teacher::with('user')
            ->when($request->has('query'), function ($query) use ($request) {
                $query
                    ->whereHas('user', function ($q) use ($request) {
                        $q->where('users.firstname', 'LIKE', '%' . $request->get('query') . '%')
                            ->orWhere('users.lastname', 'LIKE', '%' . $request->get('query') . '%');
                    })
                    ->orWhere('id', 'LIKE', '%' . $request->get('query') . '%');
            })->limit(300)->get();

        return response()->json($teachers);
    }

    /**
     * Get subjects for multiselect
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getSubjects(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'string|nullable|max:255'
        ]);

        $subjects = Subject::
        when($request->has('query'), function ($query) use ($request) {
            $query
                ->where('name', 'LIKE', '%' . $request->get('query') . '%')
                ->orWhere('abbreviation', 'LIKE', '%' . $request->get('query') . '%');
        })->limit(300)->get();

        return response()->json($subjects);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getLessonsByClass(Request $request): JsonResponse
    {
        $request->validate([
            'class' => 'required|integer'
        ]);

        $lessons = Lesson::whereSchoolClassId($request->get('class'))
            ->where('date', $request->get('date'))
            ->with('subject')->limit(300)->get();

        return response()->json($lessons);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getClassrooms(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'string|nullable|max:255'
        ]);

        $classrooms = Classroom::
        when($request->has('query'), function ($query) use ($request) {
            $query
                ->where('name', 'LIKE', '%' . $request->get('query') . '%');
        })->limit(300)->get();

        return response()->json($classrooms);
    }
}
