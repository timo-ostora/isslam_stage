<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attempt extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'assessment_id',
        'score',
        'passed',
        'started_at',
        'submitted_at',
        'time_taken_seconds',
    ];

    protected $casts = [
        'passed'       => 'boolean',
        'started_at'   => 'datetime',
        'submitted_at' => 'datetime',
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

    public function scopePassed($query)
    {
        return $query->where('passed', true);
    }

    public function scopeFailed($query)
    {
        return $query->where('passed', false);
    }

    // Calcule le score en % par rapport au total de points de l'assessment
    public function scorePercentage(): float
    {
        $total = $this->assessment->totalPoints();

        if ($total === 0) return 0;

        return round(($this->score / $total) * 100, 2);
    }
}