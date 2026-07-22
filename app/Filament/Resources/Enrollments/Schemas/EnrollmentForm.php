<?php

namespace App\Filament\Resources\Enrollments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required(),
                Select::make('status')
                    ->options(['active' => 'Active', 'completed' => 'Completed', 'cancelled' => 'Cancelled'])
                    ->default('active')
                    ->required(),
                TextInput::make('progress_percentage')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                DateTimePicker::make('completed_at'),
            ]);
    }
}
