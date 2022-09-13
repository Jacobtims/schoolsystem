<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\SchoolClass;
use App\Models\Teacher;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
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
