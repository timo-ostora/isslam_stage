<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttemptAnswerOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_answer_id',
        'question_option_id',
    ];

    protected $casts = [
        'attempt_answer_id'   => 'integer',
        'question_option_id'  => 'integer',
    ];

    public function attemptAnswer(): BelongsTo
    {
        return $this->belongsTo(AttemptAnswer::class);
    }

    public function questionOption(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class);
    }
}