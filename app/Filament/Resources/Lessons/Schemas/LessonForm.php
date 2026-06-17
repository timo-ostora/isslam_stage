<?php

namespace App\Filament\Resources\Lessons\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LessonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('description'),
                TextInput::make('type')
                    ->required(),
                TextInput::make('content_url')
                    ->url(),
                Textarea::make('content_text')
                    ->columnSpanFull(),
                Textarea::make('metadata')
                    ->columnSpanFull(),
                TextInput::make('duration_seconds')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
