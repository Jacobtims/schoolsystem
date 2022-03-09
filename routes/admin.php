<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('students', \App\Http\Controllers\Admin\StudentController::class);
    Route::resource('teachers', \App\Http\Controllers\Admin\TeacherController::class);
    Route::resource('classes', \App\Http\Controllers\Admin\SchoolClassController::class);
    Route::post('/classes/add-students/{class}', [\App\Http\Controllers\Admin\SchoolClassController::class, 'addStudents'])->name('classes.addStudents');
    Route::post('/classes/remove-student/{class}/{id}', [\App\Http\Controllers\Admin\SchoolClassController::class, 'removeStudent'])->name('classes.removeStudent');
    Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
});
