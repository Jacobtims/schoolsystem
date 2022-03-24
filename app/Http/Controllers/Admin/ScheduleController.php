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
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Redirect;

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
        $now = Carbon::now();
        // Check for specific week
        if ($request->input('week') !== null) {
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

        // Get the lessons for the specific week
        $lessons = Lesson::whereStudentId(1)->with(['time', 'subject'])->whereBetween('date', [$monday, $friday])->get()->groupBy([function ($data) {
            return Carbon::parse($data->date)->format('Y-m-d');
        }, 'time']);

        // Return
        return Inertia::render('Admin/Schedule/Overview', [
            'dates' => $dates,
            'lessons' => $lessons,
            'week' => $week
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     * @throws NotFoundExceptionInterface|ContainerExceptionInterface
     */
    public function create(): \Inertia\Response
    {
        $standardLessons = StandardLesson::get();

        return Inertia::render('Admin/Schedule/Lessons', [
            'lessons' => $standardLessons,
            'createdRecords' => session()->has('createdRecords') ? session()->get('createdRecords') : null
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
        $createdRecords = 0;
        foreach ($schoolClasses as $class) {
            foreach ($lessons as $lesson) {
                Lesson::create([
                    'school_class_id' => $class['id'],
                    'teacher_id' => $request->get('teacher')['id'],
                    'subject_id' => $request->get('subject')['id'],
                    'date' => $request->get('date'),
                    'time' => $lesson['id']
                ]);
                $createdRecords++;
            }
        }

        return Redirect::back()->with([
            'createdRecords' => $createdRecords
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return void
     */
    public function destroy($id)
    {
        //
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

        return Redirect::back();
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
