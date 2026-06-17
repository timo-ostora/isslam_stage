<?php

namespace App\Filament\Resources\Assessments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AssessmentForm
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
                TextInput::make('duration_seconds')
                    ->numeric(),
                TextInput::make('passing_score')
                    ->required()
                    ->numeric()
                    ->default(50),
                TextInput::make('max_attempts')
                    ->numeric(),
            ]);
    }
}
