<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Attempt extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'assessment_id',
        'score',
        'passed',
        'started_at', // replaced with created_at
        'submitted_at',
        'time_taken_seconds',
        'questions_snapshot',
    ];

    protected $casts = [
        'user_id'             => 'integer',
        'assessment_id'       => 'integer',
        'score'               => 'integer',
        'passed'              => 'boolean',
        'started_at'          => 'datetime', // replaced with created_at
        'submitted_at'        => 'datetime',
        'time_taken_seconds'  => 'integer',
        'questions_snapshot'  => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class);
    }

    public function isInProgress(): bool
    {
        return is_null($this->submitted_at);
    }

    public function getFormattedTimeTakenAttribute(): string
    {
        $total = $this->time_taken_seconds ?? 0;
        $m     = intdiv($total, 60);
        $s     = $total % 60;
        return "{$m}m {$s}s";
    }
}