<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Inertia\Inertia;
use App\Http\Resources\CourseResource;

class FrontEndController extends Controller
{
    public function home()
    {
        return Inertia::render('home', [
          'data' => [
            'popularCourses' => CourseResource::collection($this->getFeaturedCourses())->resolve(),
            // 'featuredCourses' => $this->getFeaturedCourses(),
            // 'experts' => $this->getExperts(),
          ]
        ]);
    }

    private function getFeaturedCourses()
    {
        return Course::query()
            ->with(['creator', 'category'])
            ->withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->where('status', 'published')
            ->take(10)
            ->get();
    }

    private function getExperts()
    {
        return User::query()
            ->role('professor') // Filament Shield / Spatie
            ->withCount(['courses' => fn ($q) => $q->where('is_published', true)])
            ->with('professorProfile') // si tu as une table à part pour bio/specialite
            ->having('courses_count', '>', 0)
            ->orderByDesc('courses_count')
            ->take(6)
            ->get()
            ->map(fn (User $expert) => [
                'id' => $expert->id,
                'name' => $expert->name,
                'avatar' => $expert->avatar_url,
                'title' => $expert->professorProfile?->title, // ex: "Professeur de Fiqh"
                'bio' => \Str::limit($expert->professorProfile?->bio, 100),
                'courses_count' => $expert->courses_count,
                'students_count' => $expert->courses()->withCount('enrollments')->get()->sum('enrollments_count'),
                'rating' => round($expert->professorProfile?->average_rating ?? 0, 1),
            ]);
    }
}