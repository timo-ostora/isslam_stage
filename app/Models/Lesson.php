<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'module_id',
        'title',
        'description',
        'type',
        'content_url',
        'content_text',
        'metadata',
        'duration_seconds',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

}
