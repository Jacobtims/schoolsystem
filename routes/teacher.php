<?php

Route::middleware(['auth:sanctum', 'verified', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', function () {
        return response()->json("Teacher dashboard");
    })->name('dashboard');
});
