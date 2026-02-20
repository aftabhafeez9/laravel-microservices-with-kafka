<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Student signup
Route::post('/students/signup', [StudentController::class, 'signup'])->name('students.signup');

// Student routes
Route::prefix('students')->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('students.index');
    Route::get('/{id}', [StudentController::class, 'show'])->name('students.show');
    Route::put('/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
});
