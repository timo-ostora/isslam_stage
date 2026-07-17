<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\ModuleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class LearningController extends Controller
{
    public function show(Course $course, ?ModuleItem $moduleItem = null): Response
    {
        $user = Auth::user();

        // Ensure the user is enrolled.
        $enrollment = $this->resolveEnrollment($course, $user->id);

        // Load the complete learning tree.
        $course->load([
            'category:id,title,slug',
            'creator:id,name',
            'modules' => fn ($query) => $query->orderBy('position'),
            'modules.moduleItems' => fn ($query) => $query->orderBy('position'),
            'modules.moduleItems.itemable',
        ]);

        // Flatten all module items into a single ordered collection.
        $items = $course->modules
            ->flatMap(fn ($module) => $module->moduleItems)
            ->values();

        abort_if($items->isEmpty(), 404, 'This course contains no learning content.');

        // Determine which item should be displayed.
        $currentItem = $this->resolveCurrentItem($items, $moduleItem);

        // Security: ensure the item belongs to this course.
        abort_unless(
            $items->contains('id', $currentItem->id),
            404
        );

        // Navigation.
        $currentIndex = $items->search(
            fn (ModuleItem $item) => $item->id === $currentItem->id
        );

        $previousItem = $currentIndex > 0
            ? $items[$currentIndex - 1]
            : null;

        $nextItem = $currentIndex < ($items->count() - 1)
            ? $items[$currentIndex + 1]
            : null;

        return Inertia::render('learning/show', [
            'learning' => [
                'course' => $course,
                'enrollment' => [
                    'status' => $enrollment->status,
                    'progress_percentage' => (float) $enrollment->progress_percentage,
                ],

                'currentItem' => $currentItem,

                'previousItem' => $previousItem,

                'nextItem' => $nextItem,

                'statistics' => [
                    'totalModules' => $course->modules->count(),
                    'totalItems' => $items->count(),
                ],
            ],
        ]);
    }

    private function resolveEnrollment(Course $course, int $userId): Enrollment
    {
        return Enrollment::query()
            ->where('course_id', $course->id)
            ->where('user_id', $userId)
            ->whereIn('status', ['active', 'completed'])
            ->firstOrFail();
    }

    private function resolveCurrentItem($items, ?ModuleItem $moduleItem): ModuleItem
    {
        if ($moduleItem) {
            return $moduleItem->load('itemable');
        }

        return $items->first();
    }
}