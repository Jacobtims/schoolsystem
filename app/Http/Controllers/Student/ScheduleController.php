<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use DB;
use Illuminate\Http\JsonResponse;
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
//        $request->validate([
//            'start' => 'date',
//            'end' => 'date',
//            'class' => 'string|max:255|exists:school_classes,name',
//            'teacher' => 'string|max:255|exists:teachers,abbreviation'
//        ]);

//        $now = Carbon::now();
//
//        // Check for specific week
//        if ($request->filled('week')) {
//            $splitWeek = explode("-", $request->get('week'));
//            $now->setISODate($splitWeek[0], $splitWeek[1]);
//        }
//
//        // Week dates
//        $monday = $now->startOfWeek(CarbonInterface::MONDAY)->format('Y-m-d');
//        $friday = $now->endOfWeek(CarbonInterface::FRIDAY)->format('Y-m-d');
//
//        $period = CarbonPeriod::create($monday, $friday);
//        $dates = $period->toArray();
//
//        // Get week
//        $week = $now->format('Y-W');
//
//        $start = $request->date('start');
//        $end = $request->date('end');
//
//        // Create query
//        $query = Lesson::query();
//        $query->join('standard_lessons', 'lessons.time', '=', 'standard_lessons.id')
//            ->whereBetween('date', [$start, $end])
////            ->selectRaw('concat(lessons.date, " ", standard_lessons.from) as `from`')
////            ->selectRaw('concat(lessons.date, " ", standard_lessons.to) as `end`')
//            ->with(['subject', 'teacher:id,abbreviation,student_name', 'schoolClass:id,name', 'classroom:id,name', 'homework']);

//        // Check for class, teacher or student
//        if ($request->filled('class')) {
//            $schoolClass = SchoolClass::whereName($request->get('class'))->first();
//            $query->where('school_class_id', $schoolClass->id);
//        }
//        elseif ($request->filled('teacher')) {
//            $teacher = Teacher::whereAbbreviation($request->get('teacher'))->first();
//            $query->where('teacher_id', $teacher->id);
//        }
//        else {
//            $query->where('school_class_id', Auth::user()->student->school_class_id);
//        }
//
//        // Get lessons & group by date
//        $lessons = $query->get()->groupBy([function ($data) {
//            return Carbon::parse($data->date)->format('Y-m-d');
//        }, 'time']);
//
//        // Set params
//        $data = [
//            'class' => $schoolClass ?? null,
//            'teacher' => $teacher ?? null
//        ];
//        $lessons = $query->get();
//
//        foreach ($lessons as $lesson) {
//            $lesson->from = $lesson->date . " " . $lesson->from;
//            $lesson->end = $lesson->date . " " . $lesson->to;
//        }

        // Return
        return Inertia::render('Student/Schedule2');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getLessons(Request $request)
    {
        // Validate
        $request->validate([
            'start' => 'date',
            'end' => 'date',
            'class' => 'integer|exists:school_classes,id',
            'teacher' => 'integer|exists:teachers,id'
        ]);

        // Start & end dates
        $start = $request->date('start');
        $end = $request->date('end');

        // Create query
        $query = Lesson::query();
        $query->join('standard_lessons', 'lessons.time', '=', 'standard_lessons.id')
            ->whereBetween('date', [$start, $end])
            ->select([
                'lessons.id', 'lessons.subject_id', 'teacher_id', 'classroom_id',
                DB::raw('TIMESTAMP(lessons.date, standard_lessons.from) AS `start`'),
                DB::raw('TIMESTAMP(lessons.date, standard_lessons.to) AS `end`')
            ])
            ->with(['subject:id,name', 'teacher:id,abbreviation', 'classroom:id,name']);

        // Check for class, teacher or student
        if ($request->filled('class')) {
            $schoolClass = SchoolClass::findOrFail($request->get('class'));
            $query->where('school_class_id', $schoolClass->id);
        }
        elseif ($request->filled('teacher')) {
            $teacher = Teacher::findOrFail($request->get('teacher'));
            $query->where('teacher_id', $teacher->id);
        }
        else {
            if (auth()->user()->is_student) {
                $query->where('school_class_id', auth()->user()->student->school_class_id);
            } elseif (auth()->user()->is_teacher) {
                $query->where('teacher_id', auth()->user()->teacher->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // Get all lessons
        $lessons = $query->get();

        return response()->json($lessons);
    }

    /**
     * @param Lesson $lesson
     * @return JsonResponse
     */
    public function getLesson(Lesson $lesson)
    {
        $lesson->load(['subject', 'teacher:id,abbreviation,student_name', 'schoolClass:id,name', 'classroom:id,name', 'homework', 'time']);

        return response()->json($lesson);
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
}
