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



// Route::get('/learning/{course:slug}/{modules}/{iteme}', [App\Http\Controllers\LearningController::class, 'show'])
//     ->name('learning.show');


Route::middleware(['auth', 'verified'])->group(function () {

    Route::post('/courses/{course:slug}/enroll', [App\Http\Controllers\EnrollmentController::class, 'store'])
        ->name('courses.enroll');

    Route::get('/learn/{course:slug}/{moduleItem?}', [App\Http\Controllers\LearningController::class, 'show'])
        ->name('learning.show');
});

require __DIR__.'/settings.php';
