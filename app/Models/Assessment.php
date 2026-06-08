<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\AssessmentType;

class Assessment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'module_id',
        'order_index',
        'title',
        'description',
        'type',
        'duration_seconds',
        'passing_score',
        'max_attempts',
    ];

    protected $casts = [
        'type' => AssessmentType::class,
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order_index');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    public function totalPoints(): int
    {
        return $this->questions()->sum('points');
    }

    public function userAttempts(int $userId): HasMany
    {
        return $this->attempts()->where('user_id', $userId);
    }

    public function hasReachedMaxAttempts(int $userId): bool
    {
        if (is_null($this->max_attempts)) {
            return false;
        }

        return $this->userAttempts($userId)->count() >= $this->max_attempts;
    }
}