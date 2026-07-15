 <?php

// namespace App\Http\Controllers;


// use App\Models\Course;
// use Illuminate\Http\Request;
// use Inertia\Inertia; 

class LLOOloController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
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
        ]);

        $enrollment = null;

        // if ($user = Auth::user()) {
        //     $enrollment = $course->enrollments()
        //         ->where('user_id', $user->id)
        //         ->whereIn('status', ['active', 'completed'])
        //         ->first();
        // }

        return Inertia::render('courses/show', [
            'course' => $course,
            'enrollment' => $enrollment ? [
                'status' => $enrollment->status,
                'progress_percentage' => (float) $enrollment->progress_percentage,
            ] : null,
        ]);
    }

}
