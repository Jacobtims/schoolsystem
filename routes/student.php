<?php

Route::middleware(['auth:sanctum', 'verified', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/grades', [\App\Http\Controllers\Student\GradeController::class, 'index'])->name('grades');
    Route::get('/schedule', [\App\Http\Controllers\Student\ScheduleController::class, 'index'])->name('schedule');
    Route::get('/attendance', [\App\Http\Controllers\Student\AttendanceController::class, 'index'])->name('attendance');
    Route::get('/schedule/school-classes/get', [\App\Http\Controllers\Student\ScheduleController::class, 'getSchoolClasses'])->name('schedules.getSchoolClasses');
    Route::get('/schedule/teachers/get', [\App\Http\Controllers\Student\ScheduleController::class, 'getTeachers'])->name('schedules.getTeachers');
    Route::get('/get-lessons', [\App\Http\Controllers\Student\ScheduleController::class, 'getLessons'])->name('schedules.get-lessons');
    Route::get('/get-lesson/{lesson}', [\App\Http\Controllers\Student\ScheduleController::class, 'getLesson'])->name('schedules.get-lesson');
});

