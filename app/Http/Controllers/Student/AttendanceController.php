<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Auth;
use DB;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    /**
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        $attendances = Attendance::whereStudentId(Auth::user()->student->id)->get();

        $counts = [
            'present' => $attendances->where('status', '=', true)->count(),
            'authorizedAbsent' => $attendances->where('status', '=', false)->where('verified', '=', true)->count(),
            'unauthorizedAbsence' => $attendances->where('status', '=', false)->filter(function ($attendance) {
                return $attendance->verified === false || $attendance->verified === null;
            })->count()
        ];

        $absent = $attendances->load('lesson.time')->where('status', '=', false)->sortByDesc('lesson.date')->values();

        return Inertia::render('Student/Attendance', [
            'counts' => $counts,
            'absent' => $absent
        ]);
    }
}
