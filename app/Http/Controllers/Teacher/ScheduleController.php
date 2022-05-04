<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\Lesson;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Auth;
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
        $query->with(['time', 'subject', 'teacher:id,abbreviation,student_name', 'schoolClass:id,name', 'classroom:id,name', 'homework'])->whereBetween('date', [$monday, $friday]);

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
            $query->where('teacher_id', Auth::user()->teacher->id);
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
        return Inertia::render('Teacher/Schedule', [
            'dates' => $dates,
            'lessons' => $lessons,
            'week' => $week,
            'data' => $data
        ]);
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
            'query' => 'string|max:255'
        ]);

        $schoolClasses = SchoolClass::when($request->has('query'), function ($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request->get('query') . '%');
        })->limit(300)->get();

        return response()->json($schoolClasses);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getTeachers(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'string|max:255'
        ]);

        $teachers = Teacher::when($request->has('query'), function ($query) use ($request) {
            $query->where('abbreviation', 'LIKE', '%' . $request->get('query') . '%');
        })->limit(300)->get();

        return response()->json($teachers);
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
