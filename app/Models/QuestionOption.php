<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionOption extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'question_id',
        'option_text',
        'is_correct',
    ];

    protected $casts = [
        'question_id' => 'integer',
        'is_correct'  => 'boolean',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Join rows recording every time this option was selected as part of an answer.
     */
    public function attemptAnswerOptions(): HasMany
    {
        return $this->hasMany(AttemptAnswerOption::class);
    }
 
    public function attemptAnswers(): BelongsToMany
    {
        return $this->belongsToMany(
            AttemptAnswer::class,
            'attempt_answer_options'
        )->withTimestamps();
    }
}