<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('students', \App\Http\Controllers\Admin\StudentController::class)->except(['create', 'show', 'edit']);
    Route::post('/students/import', [\App\Http\Controllers\Admin\StudentController::class, 'import'])->name('students.import');

    Route::resource('teachers', \App\Http\Controllers\Admin\TeacherController::class)->except(['create', 'show', 'edit']);

    Route::resource('classes', \App\Http\Controllers\Admin\SchoolClassController::class)->except(['create', 'edit']);
    Route::post('/classes/add-students/{class}', [\App\Http\Controllers\Admin\SchoolClassController::class, 'addStudents'])->name('classes.addStudents');
    Route::post('/classes/remove-student/{id}', [\App\Http\Controllers\Admin\SchoolClassController::class, 'removeStudent'])->name('classes.removeStudent');

    Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class)->except(['create', 'show', 'edit', 'update']);
    Route::post('/subjects/restore/{id}', [\App\Http\Controllers\Admin\SubjectController::class, 'restore'])->name('subjects.restore');

    Route::get('/schedules', [\App\Http\Controllers\Admin\ScheduleController::class, 'index'])->name('schedules.index');

    Route::resource('lessons', \App\Http\Controllers\Admin\LessonController::class)->only(['create', 'store']);
    Route::get('/lessons/classes/get', [\App\Http\Controllers\Admin\LessonController::class, 'getSchoolClasses'])->name('lessons.getSchoolClasses');
    Route::get('/lessons/teachers/get', [\App\Http\Controllers\Admin\LessonController::class, 'getTeachers'])->name('lessons.getTeachers');
    Route::get('/lessons/subjects/get', [\App\Http\Controllers\Admin\LessonController::class, 'getSubjects'])->name('lessons.getSubjects');
    Route::get('/lessons/lessons/get', [\App\Http\Controllers\Admin\LessonController::class, 'getLessonsByClass'])->name('lessons.getLessonsByClass');
    Route::post('/lessons/destroy-multiple', [\App\Http\Controllers\Admin\LessonController::class, 'destroyMultiple'])->name('lessons.destroyMultiple');
    Route::get('/lessons/classrooms/get', [\App\Http\Controllers\Admin\LessonController::class, 'getClassrooms'])->name('lessons.getClassrooms');

    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings');
    Route::patch('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::patch('/settings/lessons', [\App\Http\Controllers\Admin\SettingController::class, 'updateLessons'])->name('settings.lessons.update');

    Route::resource('classrooms', \App\Http\Controllers\Admin\ClassroomController::class)->only(['index', 'store', 'destroy']);
    Route::post('/classrooms/restore/{id}', [\App\Http\Controllers\Admin\ClassroomController::class, 'restore'])->name('classrooms.restore');
});
