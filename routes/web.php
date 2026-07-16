<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\CourseCatalogController;

Route::inertia('/', 'home')->name('home');

// Route::get('/catalog', [App\Http\Controllers\CourseCatalogController::class, 'index'])->name('courses.index');
// // Route::get('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');


Route::get('/courses', [App\Http\Controllers\CourseCatalogController::class, 'index'])
    ->name('courses.index');
 
Route::get('/courses/{course:slug}', [App\Http\Controllers\CourseCatalogController::class, 'show'])
    ->name('courses.show');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
