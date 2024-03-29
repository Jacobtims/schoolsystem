<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('students', \App\Http\Controllers\Admin\StudentController::class)->except(['create', 'show', 'edit']);
    Route::post('/students/import', [\App\Http\Controllers\Admin\StudentController::class, 'import'])->name('students.import');
    Route::get('/students/export', [\App\Http\Controllers\Admin\StudentController::class, 'export'])->name('students.export');
    Route::post('/students/reset-password', [\App\Http\Controllers\Admin\StudentController::class, 'sendPasswordResetLink'])->name('students.reset-password');

    Route::resource('teachers', \App\Http\Controllers\Admin\TeacherController::class)->except(['create', 'show', 'edit']);
    Route::post('/teachers/import', [\App\Http\Controllers\Admin\TeacherController::class, 'import'])->name('teachers.import');
    Route::get('/teachers/export', [\App\Http\Controllers\Admin\TeacherController::class, 'export'])->name('teachers.export');

    Route::resource('classes', \App\Http\Controllers\Admin\SchoolClassController::class)->only(['index', 'store']);
    Route::post('/classes/add-students/{class}', [\App\Http\Controllers\Admin\SchoolClassController::class, 'addStudentsToSchoolClass'])->name('classes.addStudents');
    Route::post('/classes/remove-student/{id}', [\App\Http\Controllers\Admin\SchoolClassController::class, 'removeStudentFromSchoolClass'])->name('classes.removeStudent');
    Route::get('/classes/get-students', [\App\Http\Controllers\Admin\SchoolClassController::class, 'getStudents'])->name('classes.get-students');

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
    Route::patch('/settings/students-auto-increment', [\App\Http\Controllers\Admin\SettingController::class, 'updateStudentsAutoIncrement'])->name('settings.students-auto-increment');

    Route::resource('classrooms', \App\Http\Controllers\Admin\ClassroomController::class)->only(['index', 'store', 'destroy']);
    Route::post('/classrooms/restore/{id}', [\App\Http\Controllers\Admin\ClassroomController::class, 'restore'])->name('classrooms.restore');
});
