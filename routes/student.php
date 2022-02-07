<?php

Route::middleware(['auth:sanctum', 'verified', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', function () {
        return response()->json("Student dashboard");
    })->name('dashboard');
});

