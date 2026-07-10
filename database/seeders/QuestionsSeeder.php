<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Seeder;

/**
 * Seeds 4 questions per assessment: 2 single_choice, 1 multiple_choice
 * (2 correct options out of 4 — exercises the multi-select grading logic),
 * 1 true_false. Question text is generic/template-based since it isn't
 * tied to real course content, but the structure is fully realistic.
 */
class QuestionsSeeder extends Seeder
{
    public function run(): void
    {
        Assessment::all()->each(function (Assessment $assessment) {
            if ($assessment->questions()->exists()) {
                return; // idempotent re-run
            }

            $topic = str_replace(' Quiz', '', $assessment->title);

            // 1) single_choice
            $q1 = Question::create([
                'assessment_id' => $assessment->id,
                'question_text' => "Which statement best describes the core idea behind {$topic}?",
                'type'          => 'single_choice',
                'points'        => 25,
                'position'      => 0,
            ]);
            $this->options($q1, [
                ['It is the most important and only correct approach.', false],
                ['It is one valid technique among several, suited to specific use cases.', true],
                ['It has been deprecated and should be avoided entirely.', false],
                ['It only applies to legacy systems.', false],
            ]);

            // 2) single_choice
            $q2 = Question::create([
                'assessment_id' => $assessment->id,
                'question_text' => "What is the primary benefit of applying {$topic} correctly?",
                'type'          => 'single_choice',
                'points'        => 25,
                'position'      => 1,
            ]);
            $this->options($q2, [
                ['Improved maintainability and clarity.', true],
                ['Guaranteed zero bugs.', false],
                ['Removes the need for testing.', false],
                ['Automatically improves server hardware.', false],
            ]);

            // 3) multiple_choice — 2 correct out of 4
            $q3 = Question::create([
                'assessment_id' => $assessment->id,
                'question_text' => "Which TWO of the following are commonly associated with {$topic}? (Select all that apply)",
                'type'          => 'multiple_choice',
                'points'        => 30,
                'position'      => 2,
            ]);
            $this->options($q3, [
                ['Consistent, repeatable patterns.', true],
                ['Random, undocumented behavior.', false],
                ['Clear separation of concerns.', true],
                ['Ignoring edge cases entirely.', false],
            ]);

            // 4) true_false
            $q4 = Question::create([
                'assessment_id' => $assessment->id,
                'question_text' => "True or false: understanding {$topic} is optional for building production-quality software.",
                'type'          => 'true_false',
                'points'        => 20,
                'position'      => 3,
            ]);
            $this->options($q4, [
                ['True', false],
                ['False', true],
            ]);
        });

        $this->command->info('✅  Questions & options seeded.');
    }

    /**
     * @param  array<int, array{0: string, 1: bool}>  $options
     */
    private function options(Question $question, array $options): void
    {
        foreach ($options as $option) {
            QuestionOption::create([
                'question_id' => $question->id,
                'option_text' => $option[0],
                'is_correct'  => $option[1],
            ]);
        }
    }
}