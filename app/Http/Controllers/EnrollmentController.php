<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store(Course $course): RedirectResponse
    {
        $user = Auth::user();

        // Prevent duplicate enrollments.
        if ($course->enrollments()->where('user_id', $user->id)->exists()) {
            return back()->with('info', 'You are already enrolled in this course.');
        }

        $course->enrollments()->create([
            'user_id' => $user->id,
            'status' => 'active',
            'progress_percentage' => 0,
        ]);

        return back()->with('success', 'Successfully enrolled in the course.');
    }
}