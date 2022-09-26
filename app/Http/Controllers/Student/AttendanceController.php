<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    public function index(): \Inertia\Response
    {
        $student = Student::findOrFail(auth()->user()->student->id);

        // All queries for counts
        $notRegistered = $student->lessons()->where(function ($q) {
            $q->whereAttendanceRegistered(false)->orWhereNull('attendance_registered');
        })->whereDoesntHave('absences', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })->whereDate('date', '<=', today())
            ->count();

        $present = $student->lessons()->whereAttendanceRegistered(true)
            ->whereDoesntHave('absences', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })->whereDate('date', '<=', today())
            ->count();

        $allowedAbsent = $student->lessons()->whereHas('absences', function ($q) use ($student) {
            $q->where('student_id', $student->id)->where('reason_verified', true);
        })->whereDate('date', '<=', today())
            ->count();

        $unallowedAbsent = $student->lessons()->whereHas('absences', function ($q) use ($student) {
            $q->where('reason_verified', false)->orWhereNull('reason_verified');
            $q->where('student_id', $student->id);
        })->whereDate('date', '<=', today())
            ->count();

        // All student's absences
        $absences = $student->absences()->with(['lesson:id,time,date', 'lesson.time', 'type'])->paginate();

        return Inertia::render('Student/Attendance', compact('present', 'allowedAbsent', 'unallowedAbsent', 'notRegistered', 'absences'));
    }
}
