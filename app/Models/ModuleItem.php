<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuleItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'module_id',
        'itemable_id',
        'itemable_type',
        'position',
    ];

    protected $casts = [
        'module_id'   => 'integer',
        'itemable_id' => 'integer',
        'position'    => 'integer',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Polymorphic: resolves to Lesson or Assessment.
     * itemable_type stores the model class, itemable_id stores the PK.
     */
    public function itemable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getContentTypeAttribute(): string
    {
        return match ($this->itemable_type) {
            Lesson::class     => 'lesson',
            Assessment::class => 'assessment',
            default           => 'unknown',
        };
    }
}