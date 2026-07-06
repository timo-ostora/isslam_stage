<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'type',
        'duration_seconds',
        'passing_score',
        'max_attempts',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'passing_score'    => 'integer',
        'max_attempts'     => 'integer',
        'type'             => 'string',
    ];

    /**
     * Inverse of ModuleItem::itemable() MorphTo.
     */
    public function moduleItem(): MorphOne
    {
        return $this->morphOne(ModuleItem::class, 'itemable');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('position');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    public function getTotalPointsAttribute(): int
    {
        return $this->questions()->sum('points');
    }

    public function isPassing(int $score): bool
    {
        return $score >= $this->passing_score;
    }
}