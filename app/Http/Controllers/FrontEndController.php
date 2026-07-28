<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class FrontEndController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('home', [
            'data' => [
                'popularCourses' => CourseResource::collection(
                    $this->getFeaturedCourses()
                )->resolve(),

                'experts' => $this->getExperts(),

                'stats' => [
                    'courses' => Course::query()
                        ->where('status', 'published')
                        ->count(),

                    'students' => User::query()
                        ->role('student')
                        ->count(),

                    'experts' => User::query()
                        ->role('professor')
                        ->whereHas('createdCourses', function ($query) {
                            $query->where('status', 'published');
                        })
                        ->count(),
                ],
            ],
        ]);
    }

    private function getFeaturedCourses()
    {
        return Course::query()
            ->with([
                'creator',
                'category',
            ])
            ->withCount('enrollments')
            ->where('status', 'published')
            ->orderByDesc('enrollments_count')
            ->take(10)
            ->get();
    }

    private function getExperts(): array
    {
        return User::query()
            ->role('professor')
            ->whereHas('createdCourses', function ($query) {
                $query->where('status', 'published');
            })
            ->withCount([
                'createdCourses as courses_count' => function ($query) {
                    $query->where('status', 'published');
                },
            ])
            ->with([
                'createdCourses' => function ($query) {
                    $query
                        ->where('status', 'published')
                        ->withCount('enrollments');
                },
            ])
            ->orderByDesc('courses_count')
            ->take(10)
            ->get()
            ->map(function (User $expert) {
                return [
                    'id' => $expert->id,
                    'name' => $expert->name,
                    'courses_count' => (int) $expert->courses_count,
                    'students_count' => (int) $expert
                        ->createdCourses
                        ->sum('enrollments_count'),
                ];
            })
            ->values()
            ->all();
    }
}