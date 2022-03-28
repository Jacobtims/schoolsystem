<?php

Route::middleware(['auth:sanctum', 'verified', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/schedule', [\App\Http\Controllers\Teacher\ScheduleController::class, 'index'])->name('schedule');
    Route::get('/schedule/school-classes/get', [\App\Http\Controllers\Teacher\ScheduleController::class, 'getSchoolClasses'])->name('schedules.getSchoolClasses');
});
