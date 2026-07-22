<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\CourseCatalogController;

/**
 * Landing page 
 * 
 * @return resources/js/pages/home.jsx
 */
Route::inertia('/', 'home')->name('home');

/**
 * Courses catalog 
 * 
 * @return CourseCatalogController->index()
 */
Route::get('/courses', [App\Http\Controllers\CourseCatalogController::class, 'index'])
    ->name('courses.index');

/**
 * Single Course
 * 
 * @return CourseCatalogController->show()
 */
Route::get('/courses/{course:slug}', [App\Http\Controllers\CourseCatalogController::class, 'show'])
    ->name('courses.show');

/**
 * accesable by authenticated and email verified users
 */
Route::middleware(['auth', 'verified'])->group(function () {

    /**
     * Handle enrolling to a course via post request to EnrollmentController->store
     */
    Route::post('/courses/{course:slug}/enroll', [App\Http\Controllers\EnrollmentController::class, 'store'])
        ->name('courses.enroll');

    /**
     * get course content
     */
    Route::get('/learn/{course:slug}/{moduleItem?}', [App\Http\Controllers\LearningController::class, 'show'])
        ->name('learning.show');
});

require __DIR__.'/settings.php';
