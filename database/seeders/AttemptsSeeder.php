<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use App\Models\AttemptAnswerOption;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Seeds attempts for every assessment inside a course, for every student
 * enrolled in that course — with deliberately mixed outcomes:
 *   - completed enrollments  -> a passing attempt on every assessment
 *   - the active enrollment with highest progress -> a mix of passed/failed
 *     attempts (partial progress through the course)
 *   - one attempt is left genuinely in-progress (submitted_at = null) to
 *     exercise that state in the admin panel
 *
 * This is the seeder that actually exercises the multiple_choice grading
 * fix (attempt_answer_options) and the questions_snapshot integrity field.
 */
class AttemptsSeeder extends Seeder
{
    public function run(): void
    {
        $leftInProgress = false;

        Enrollment::with(['user', 'course'])->get()->each(function (Enrollment $enrollment) use (&$leftInProgress) {
            if ($enrollment->status === 'cancelled') {
                return; // no attempts for cancelled enrollments
            }

            $assessments = $this->assessmentsForCourse($enrollment->course);

            if ($assessments->isEmpty()) {
                return;
            }

            foreach ($assessments as $index => $assessment) {
                // Skip later assessments for in-progress students to reflect
                // real partial completion instead of finishing everything.
                $shouldAttempt = $enrollment->status === 'completed'
                    || ($enrollment->progress_percentage / 100) * $assessments->count() > $index;

                if (!$shouldAttempt) {
                    continue;
                }

                if (Attempt::where('user_id', $enrollment->user_id)
                    ->where('assessment_id', $assessment->id)
                    ->exists()) {
                    continue; // idempotent re-run
                }

                // Leave exactly one attempt in-progress across the whole run,
                // to exercise that UI/state without cluttering every course.
                $inProgress = !$leftInProgress && $enrollment->status === 'active' && $index === $assessments->count() - 1;
                if ($inProgress) {
                    $leftInProgress = true;
                }

                // Bias toward passing for completed enrollments, mixed for active ones.
                $targetPassRate = $enrollment->status === 'completed' ? 1.0 : 0.65;

                $this->createAttempt($enrollment->user, $assessment, $targetPassRate, $inProgress);
            }
        });

        $this->command->info('✅  Attempts, answers & selected options seeded.');
    }

    private function assessmentsForCourse(Course $course): Collection
    {
        return Assessment::whereHas('moduleItem.module', function ($query) use ($course) {
            $query->where('course_id', $course->id);
        })
            ->with(['moduleItem.module', 'questions.options'])
            ->get()
            ->sortBy(fn (Assessment $a) => $a->moduleItem->module->position)
            ->values();
    }

    private function createAttempt(User $user, Assessment $assessment, float $targetPassRate, bool $inProgress): void
    {
        $startedAt = now()->subDays(random_int(1, 20))->subHours(random_int(0, 12));

        $attempt = Attempt::create([
            'user_id'       => $user->id,
            'assessment_id' => $assessment->id,
            'score'         => 0,
            'passed'        => false,
            'started_at'    => $startedAt,
            'submitted_at'  => null,
        ]);

        if ($inProgress) {
            return; // student started but hasn't submitted — nothing more to seed
        }

        $questions = $assessment->questions;
        $totalPoints = $questions->sum('points');
        $earnedPoints = 0;

        $snapshot = [];

        foreach ($questions as $question) {
            $correctOptionIds = $question->options->where('is_correct', true)->pluck('id');
            $answerIsCorrect = (mt_rand() / mt_getrandmax()) <= $targetPassRate;

            $selectedOptionIds = $answerIsCorrect
                ? $correctOptionIds
                : $this->wrongSelection($question, $correctOptionIds);

            $pointsAwarded = $answerIsCorrect ? $question->points : 0;
            $earnedPoints += $pointsAwarded;

            $attemptAnswer = AttemptAnswer::create([
                'attempt_id'      => $attempt->id,
                'question_id'     => $question->id,
                'is_correct'      => $answerIsCorrect,
                'points_awarded'  => $pointsAwarded,
            ]);

            foreach ($selectedOptionIds as $optionId) {
                AttemptAnswerOption::create([
                    'attempt_answer_id'   => $attemptAnswer->id,
                    'question_option_id'  => $optionId,
                ]);
            }

            $snapshot[] = [
                'question_id'   => $question->id,
                'question_text' => $question->question_text,
                'type'          => $question->type,
                'points'        => $question->points,
                'options'       => $question->options->map(fn ($o) => [
                    'id'          => $o->id,
                    'option_text' => $o->option_text,
                    'is_correct'  => $o->is_correct,
                ])->all(),
            ];
        }

        $score = $totalPoints > 0 ? (int) round(($earnedPoints / $totalPoints) * 100) : 0;
        $submittedAt = $startedAt->copy()->addMinutes(random_int(4, 18));

        $attempt->update([
            'score'               => $score,
            'passed'              => $score >= $assessment->passing_score,
            'submitted_at'        => $submittedAt,
            'time_taken_seconds'  => random_int(240, 1080),
            'questions_snapshot'  => $snapshot,
        ]);
    }

    /**
     * Picks a plausible-but-wrong selection: for multiple_choice, drop one
     * correct option and add one incorrect one; for single_choice/true_false,
     * pick any incorrect option.
     */
    private function wrongSelection(Question $question, Collection $correctOptionIds): Collection
    {
        $incorrectOptionIds = $question->options->where('is_correct', false)->pluck('id');

        if ($question->type === 'multiple_choice' && $incorrectOptionIds->isNotEmpty() && $correctOptionIds->count() > 1) {
            return $correctOptionIds->take($correctOptionIds->count() - 1)
                ->push($incorrectOptionIds->first())
                ->values();
        }

        return $incorrectOptionIds->isNotEmpty()
            ? collect([$incorrectOptionIds->random()])
            : $correctOptionIds; // no wrong options exist, fall back
    }
}