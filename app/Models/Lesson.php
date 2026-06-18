<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'type',
        'content_url',
        'content_text',
        'metadata',
        'duration_seconds',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'metadata' => 'array',
            'duration_seconds' => 'integer',
        ];
    }

    public function module(): MorphOne
    {
        return $this->morphOne(Module::class, 'itemable');
    }

    // public function lessonProgresses(): HasMany
    // {
    //     return $this->hasMany(LessonProgress::class);
    // }
}
