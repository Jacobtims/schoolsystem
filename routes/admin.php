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
    Route::resource('schedules', \App\Http\Controllers\Admin\ScheduleController::class);
    Route::get('/schedules/school-classes/get', [\App\Http\Controllers\Admin\ScheduleController::class, 'getSchoolClasses'])->name('schedules.getSchoolClasses');
    Route::get('/schedules/teachers/get', [\App\Http\Controllers\Admin\ScheduleController::class, 'getTeachers'])->name('schedules.getTeachers');
    Route::get('/schedules/subjects/get', [\App\Http\Controllers\Admin\ScheduleController::class, 'getSubjects'])->name('schedules.getSubjects');
    Route::get('/schedules/lessons-by-class/get', [\App\Http\Controllers\Admin\ScheduleController::class, 'getLessonsByClass'])->name('schedules.getLessonsByClass');
    Route::post('/schedules/destroy/multiple', [\App\Http\Controllers\Admin\ScheduleController::class, 'destroyMultiple'])->name('schedules.destroyMultiple');
});
