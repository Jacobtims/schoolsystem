<?php

Route::middleware(['auth:sanctum', 'verified', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/schedule', [\App\Http\Controllers\Teacher\ScheduleController::class, 'index'])->name('schedule');
    Route::post('/schedule/homework', [\App\Http\Controllers\Teacher\ScheduleController::class, 'addHomework'])->name('schedules.addHomework');
    Route::delete('/schedule/homework/{id}', [\App\Http\Controllers\Teacher\ScheduleController::class, 'deleteHomework'])->name('schedules.deleteHomework');

    Route::get('/attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'index'])->name('attendance');
    Route::get('/attendance/{lesson}', [\App\Http\Controllers\Teacher\AttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/attendance/{lesson}', [\App\Http\Controllers\Teacher\AttendanceController::class, 'store'])->name('attendance.store');

    Route::get('/grades', [\App\Http\Controllers\Teacher\GradeController::class, 'index'])->name('grades');
    Route::get('/grades/{class}', [\App\Http\Controllers\Teacher\GradeController::class, 'show'])->name('grades.show');
    Route::post('/grades/assignment', [\App\Http\Controllers\Teacher\GradeController::class, 'storeAssignment'])->name('grades.assignment.store');
    Route::post('/grades', [\App\Http\Controllers\Teacher\GradeController::class, 'store'])->name('grades.store');
});
