<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'type',
        'content_url',
        'content_text',
        'metadata',
        'duration_seconds',
    ];

    protected $casts = [
        'metadata'         => 'array',
        'duration_seconds' => 'integer',
        'type'             => 'string',
    ];

    /**
     * Inverse of ModuleItem::itemable() MorphTo.
     */
    public function moduleItem(): MorphOne
    {
        return $this->morphOne(ModuleItem::class, 'itemable');
    }

    public function hasUrl(): bool
    {
        return !empty($this->content_url);
    }

    public function getFormattedDurationAttribute(): string
    {
        $m = intdiv($this->duration_seconds ?? 0, 60);
        $s = ($this->duration_seconds ?? 0) % 60;
        return sprintf('%d:%02d', $m, $s);
    }
}