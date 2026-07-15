<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CoursesCatalogController;

Route::inertia('/', 'home')->name('home');

// Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
// Route::get('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');

Route::get('/courses/{course}',  [CoursesCatalogController::class, 'show' ])->name('courses.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
