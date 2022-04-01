<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Models\Lesson;
use App\Models\SchoolClass;
use App\Models\StandardLesson;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request): \Inertia\Response
    {
        // Validate
        $request->validate([
            'week' => ['regex:/^[0-9]+-[0-9]+$/i'],
            'class' => 'string|max:255|exists:school_classes,name',
            'teacher' => 'string|max:255|exists:teachers,abbreviation'
        ]);

        $now = Carbon::now();

        // Check for specific week
        if ($request->filled('week')) {
            $splitWeek = explode("-", $request->get('week'));
            $now->setISODate($splitWeek[0], $splitWeek[1]);
        }

        // Week dates
        $monday = $now->startOfWeek(CarbonInterface::MONDAY)->format('Y-m-d');
        $friday = $now->endOfWeek(CarbonInterface::FRIDAY)->format('Y-m-d');

        $period = CarbonPeriod::create($monday, $friday);
        $dates = $period->toArray();

        // Get week
        $week = $now->format('Y-W');

        // Create query
        $query = Lesson::query();
        $query->with(['time', 'subject'])->whereBetween('date', [$monday, $friday]);

        // Check for class, teacher or student
        if ($request->filled('class')) {
            $schoolClass = SchoolClass::whereName($request->get('class'))->first();
            $query->where('school_class_id', $schoolClass->id);
        }
        elseif ($request->filled('teacher')) {
            $teacher = Teacher::whereAbbreviation($request->get('teacher'))->first();
            $query->where('teacher_id', $teacher->id);
        }
        else {
            $query->limit(100);
            // TODO: Remove?
        }

        // Get lessons & group by date
        $lessons = $query->get()->groupBy([function ($data) {
            return Carbon::parse($data->date)->format('Y-m-d');
        }, 'time']);

        // Set params
        $data = [
            'class' => $schoolClass ?? null,
            'teacher' => $teacher ?? null
        ];

        // Return
        return Inertia::render('Admin/Schedule/Overview', [
            'dates' => $dates,
            'lessons' => $lessons,
            'week' => $week,
            'data' => $data
        ]);
    }

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
        $lessons = Lesson::whereSchoolClassId($request->get('class'))
            ->where('date', $request->get('date'))
            ->with('subject')->limit(300)->get();

        return response()->json($lessons);
    }
}
