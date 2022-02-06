<?php

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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

// Require fortify routes
require_once __DIR__ . '/fortify.php';

// Require admin routes
require_once __DIR__ . '/admin.php';

// Require teacher routes
require_once __DIR__ . '/teacher.php';

// Require student routes
require_once __DIR__ . '/student.php';
