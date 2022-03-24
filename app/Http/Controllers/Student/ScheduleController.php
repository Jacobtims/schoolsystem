<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\SchoolClass;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
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
        $now = Carbon::now();
        // Check for specific week
        if ($request->filled('week')) {
            $splitWeek = explode("-", $request->get('week'));
            $now->setISODate($splitWeek[0], $splitWeek[1]);
        }

        // Check for specific school class
        $schoolClass = null;
        if ($request->filled('class')) {
            $schoolClass = SchoolClass::where('name', $request->get('class'))->first();
        }

        // Week dates
        $monday = $now->startOfWeek(CarbonInterface::MONDAY)->format('Y-m-d');
        $friday = $now->endOfWeek(CarbonInterface::FRIDAY)->format('Y-m-d');

        $period = CarbonPeriod::create($monday, $friday);
        $dates = $period->toArray();

        // Get week
        $week = $now->format('Y-W');

        // Get the lessons for the specific week
        $lessons = Auth::user()->student->lessons()->with(['time', 'subject'])->whereBetween('date', [$monday, $friday])
            ->when($schoolClass, function ($request) use ($schoolClass) {
                $request->where('school_class_id', $schoolClass->id);
            })->get()
            ->groupBy([function ($data) {
                return Carbon::parse($data->date)->format('Y-m-d');
            }, 'time']);

        // Return
        return Inertia::render('Student/Schedule', [
            'dates' => $dates,
            'lessons' => $lessons,
            'week' => $week
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
        $schoolClasses = SchoolClass::when($request->has('query'), function ($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request->get('query') . '%');
        })->limit(300)->get();

        return response()->json($schoolClasses);
    }
}
