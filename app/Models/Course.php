<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\CourseStatus; 
use App\Enums\DifficultyLevel;                  
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug', 'category_id', 'created_by', 'title', 'description',
        'thumbnail_url', 'status', 'duration_seconds', 'difficulty_level', 'language',
    ];

    protected $casts = [
        'status' => CourseStatus::class,
        'difficulty_level' => DifficultyLevel::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class)->orderBy('order_index');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }
}
