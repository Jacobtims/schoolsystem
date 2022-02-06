<?php

Route::middleware(['auth:sanctum', 'verified'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', function () {
        return response()->json("Teacher dashboard");
    })->name('dashboard');
});
