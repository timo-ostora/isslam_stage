<?php

namespace App\Filament\Resources\Courses\Resources\Modules\Resources\Lessons\Pages;

use App\Filament\Resources\Courses\Resources\Modules\Resources\Lessons\LessonResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLesson extends CreateRecord
{
    protected static string $resource = LessonResource::class;
}
