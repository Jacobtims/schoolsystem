<?php

Route::middleware(['auth:sanctum', 'verified', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/grades', [\App\Http\Controllers\Student\GradeController::class, 'index'])->name('grades');
});

