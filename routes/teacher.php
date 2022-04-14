<?php

Route::middleware(['auth:sanctum', 'verified', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/schedule', [\App\Http\Controllers\Teacher\ScheduleController::class, 'index'])->name('schedule');
    Route::get('/schedule/school-classes/get', [\App\Http\Controllers\Teacher\ScheduleController::class, 'getSchoolClasses'])->name('schedules.getSchoolClasses');
    Route::get('/schedule/teachers/get', [\App\Http\Controllers\Teacher\ScheduleController::class, 'getTeachers'])->name('schedules.getTeachers');

    Route::get('/attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'index'])->name('attendance');
    Route::get('/attendance/{lesson}', [\App\Http\Controllers\Teacher\AttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'store'])->name('attendance.store');

    Route::get('/grades', [\App\Http\Controllers\Teacher\GradeController::class, 'index'])->name('grades');
    Route::get('/grades/{class}', [\App\Http\Controllers\Teacher\GradeController::class, 'show'])->name('grades.show');
});
