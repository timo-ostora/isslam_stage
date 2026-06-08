<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\EnrollmentStatus;

class Enrollment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'progress_percentage',
        'completed_at',
    ];

    protected $casts = [
        'status'              => EnrollmentStatus::class,
        'progress_percentage' => 'decimal:2',
        'completed_at'        => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', EnrollmentStatus::Active);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', EnrollmentStatus::Completed);
    }

    public function markCompleted(): void
    {
        $this->update([
            'status' => EnrollmentStatus::Completed,
            'progress_percentage' => 100.00,
            'completed_at' => now()
        ]);
    }
}