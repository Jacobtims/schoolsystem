<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Schedule routes
Route::middleware(['auth:sanctum', 'verified', 'role:student,teacher,admin'])->prefix('schedule')->name('schedule.')->group(function () {
    Route::get('/school-classes', [\App\Http\Controllers\ScheduleController::class, 'getSchoolClasses'])->name('school-classes');
    Route::get('/teachers', [\App\Http\Controllers\ScheduleController::class, 'getTeachers'])->name('teachers');
    Route::get('/lessons', [\App\Http\Controllers\ScheduleController::class, 'getLessons'])->name('lessons');
    Route::get('/lesson/{lesson}', [\App\Http\Controllers\ScheduleController::class, 'getLesson'])->name('lesson');
});

// Require auth routes
require __DIR__.'/auth.php';

// Require admin routes
require_once __DIR__ . '/admin.php';

// Require teacher routes
require_once __DIR__ . '/teacher.php';

// Require student routes
require_once __DIR__ . '/student.php';

// User routes
Route::middleware(['auth:sanctum', 'verified', 'role:student,teacher'])->prefix('user')->name('user.')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\User\ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile/update', [\App\Http\Controllers\User\ProfileController::class, 'updateUser'])->name('profile.update');
    Route::patch('/profile/change-password', [\App\Http\Controllers\User\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::delete('/profile/logout-other-browser-sessions', [\App\Http\Controllers\User\ProfileController::class, 'logoutOtherBrowserSessions'])->name('profile.logout-other-browser-sessions');
});
