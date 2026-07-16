<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CourseCatalogController extends Controller
{
    /**
     * Paginated, filterable, sortable course catalog.
     *
     * Route: GET /courses
     */
    public function index(Request $request): Response
    {
        $filters = $request->validate([
            'search'     => ['nullable', 'string', 'max:100'],
            'category'   => ['nullable', 'string', 'exists:categories,slug'],
            'difficulty' => ['nullable', 'in:easy,medium,hard'],
            'sort'       => ['nullable', 'in:newest,title,popular'],
        ]);

        $sort = $filters['sort'] ?? 'newest';

        $courses = Course::query()
            ->where('status', 'published')
            ->with('category:id,title,slug')
            ->withCount('enrollments')
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where('title', 'like', "%{$search}%")
            )
            ->when($filters['category'] ?? null, fn ($query, $slug) => $query->whereHas(
                'category',
                fn ($q) => $q->where('slug', $slug)
            ))
            ->when($filters['difficulty'] ?? null, fn ($query, $difficulty) => $query->where('difficulty_level', $difficulty)
            )
            ->when($sort === 'title', fn ($query) => $query->orderBy('title'))
            ->when($sort === 'popular', fn ($query) => $query->orderByDesc('enrollments_count'))
            ->when($sort === 'newest', fn ($query) => $query->orderByDesc('created_at'))
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('courses/index', [
            'courses' => [
                'data' => CourseResource::collection($courses->items())->resolve(),
                'meta' => [
                    'current_page' => $courses->currentPage(),
                    'last_page'    => $courses->lastPage(),
                    'per_page'     => $courses->perPage(),
                    'total'        => $courses->total(),
                    'from'         => $courses->firstItem(),
                    'to'           => $courses->lastItem(),
                ],
                'links' => [
                    'prev' => $courses->previousPageUrl(),
                    'next' => $courses->nextPageUrl(),
                ],
            ],
            'categories' => Category::whereNotNull('parent_id')
                ->orderBy('title')
                ->get(['id', 'title', 'slug']),
            'filters' => [
                'search'     => $filters['search'] ?? '',
                'category'   => $filters['category'] ?? '',
                'difficulty' => $filters['difficulty'] ?? '',
                'sort'       => $sort,
            ],
        ]);
    }

    /**
     * Show a single published course's detail / syllabus preview page.
     *
     * Route: GET /courses/{course:slug}  (slug-based implicit binding)
     */
    public function show(Course $course): Response
    {
        // Published-only gate — draft/archived courses 404 for everyone
        // except the eventual "preview as owner" flow, which isn't in scope
        // for this MVP endpoint.
        abort_unless($course->status === 'published', 404);

        $course->load([
            'category:id,title,slug',
            'creator:id,name',
            'modules' => fn ($query) => $query->orderBy('position'),
            'modules.moduleItems' => fn ($query) => $query->orderBy('position'),
            'modules.moduleItems.itemable',
        ])->loadCount('enrollments');

        $enrollment = null;

        if ($user = Auth::user()) {
            $enrollment = $course->enrollments()
                ->where('user_id', $user->id)
                ->whereIn('status', ['active', 'completed'])
                ->first();
        }
        // dd($course->toArray());
        return Inertia::render('courses/show', [
            'course' => $course->toArray(),
            'enrollment' => $enrollment ? [
                'status' => $enrollment->status,
                'progress_percentage' => (float) $enrollment->progress_percentage,
            ] : null,
        ]);
    }
}