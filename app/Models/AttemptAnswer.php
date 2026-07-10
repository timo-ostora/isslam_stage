<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttemptAnswer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'attempt_id',
        'question_id',
        'is_correct',
        'points_awarded',
    ];

    protected $casts = [
        'attempt_id'      => 'integer',
        'question_id'     => 'integer',
        'is_correct'      => 'boolean',
        'points_awarded'  => 'integer',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(Attempt::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * The join rows recording which option(s) were selected.
     */
    public function selectedOptionPivots(): HasMany
    {
        return $this->hasMany(AttemptAnswerOption::class);
    }

    /**
     * Convenience many-to-many accessor for the selected QuestionOption records.
     * Works for single_choice, true_false (1 row) and multiple_choice (N rows).
     */
    public function selectedOptions(): BelongsToMany
    {
        return $this->belongsToMany(
            QuestionOption::class,
            'attempt_answer_options'
        )->withTimestamps();
    }

    /**
     * Grades this answer against the current QuestionOption.is_correct values
     * and persists the result. Call this once, at submission time, then rely
     * on the stored is_correct/points_awarded afterwards — do not re-grade
     * live against QuestionOption, since those can change after the fact.
     */
    public function grade(): void
    {
        $correctOptionIds = $this->question->options()->where('is_correct', true)->pluck('id');
        $selectedOptionIds = $this->selectedOptions()->pluck('question_options.id');

        $isCorrect = $correctOptionIds->sort()->values()->all()
            === $selectedOptionIds->sort()->values()->all();

        $this->update([
            'is_correct'     => $isCorrect,
            'points_awarded' => $isCorrect ? $this->question->points : 0,
        ]);
    }
}