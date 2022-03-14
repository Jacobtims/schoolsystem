<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Models\Lesson;
use App\Models\Role;
use App\Models\StandardLesson;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Redirect;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
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
     */
    public function create()
    {
        $standardLessons = StandardLesson::get();

        return Inertia::render('Admin/Schedule/Create', [
            'lessons' => $standardLessons,
            'newCreated' => session()->has('newCreated') ? session()->get('newCreated') : null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreScheduleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreScheduleRequest $request)
    {
        $students = $request->get('students');
        $lessons = $request->get('lessons');
        $newCreated = [];
        foreach ($students as $student){
            foreach ($lessons as $lesson) {
                Lesson::create([
                    'student_id' => $student['id'],
                    'teacher_id' => $request->get('teacher')['id'],
                    'subject_id' => $request->get('subject')['id'],
                    'date'       => $request->get('date'),
                    'time'       => $lesson['id']
                ]);
                $newCreated[] = [
                    'student' => $student['id'].' - '.$student['user']['firstname'].' '.$student['user']['lastname'],
                    'teacher' => $request->get('teacher')['abbreviation'].' - '.$request->get('teacher')['user']['firstname'].' '.$request->get('teacher')['user']['lastname'],
                    'subject' => $request->get('subject')['name'],
                    'date'       => $request->get('date'),
                    'time'       => $lesson['id']
                ];
            }
        }

        return Redirect::back()->with([
            'newCreated' => $newCreated
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get students for multiselect
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudents(Request $request)
    {
        $students = Student::with('user')
            ->when($request->has('query'), function ($query) use ($request) {
            $query
                ->whereHas('user', function ($q) use ($request) {
                    $q->where('users.firstname', 'LIKE', '%'.$request->get('query').'%')
                    ->orWhere('users.lastname', 'LIKE', '%'.$request->get('query').'%');
                })
                ->orWhere('id', 'LIKE', '%'.$request->get('query').'%');
        })->limit(300)->get();

        return response()->json($students);
    }

    /**
     * Get teachers for multiselect
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeachers(Request $request)
    {
        $teachers = Teacher::with('user')
            ->when($request->has('query'), function ($query) use ($request) {
                $query
                    ->whereHas('user', function ($q) use ($request) {
                        $q->where('users.firstname', 'LIKE', '%'.$request->get('query').'%')
                        ->orWhere('users.lastname', 'LIKE', '%'.$request->get('query').'%');
                    })
                    ->orWhere('id', 'LIKE', '%'.$request->get('query').'%');
            })->limit(300)->get();

        return response()->json($teachers);
    }

    /**
     * Get subjects for multiselect
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubjects(Request $request)
    {
        $subjects = Subject::
            when($request->has('query'), function ($query) use ($request) {
                $query
                    ->where('name', 'LIKE', '%'.$request->get('query').'%')
                    ->orWhere('abbreviation', 'LIKE', '%'.$request->get('query').'%');
            })->limit(300)->get();

        return response()->json($subjects);
    }
}
