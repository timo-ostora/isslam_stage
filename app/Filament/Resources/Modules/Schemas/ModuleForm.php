<?php

namespace App\Filament\Resources\Modules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;

class ModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')
                    ->relationship('course', 'title')
                    // Masque le champ si le cours est déjà défini dans l'URL
                    ->hidden(fn () => request()->has('course_id'))
                    // Récupère la valeur par défaut depuis l'URL
                    ->default(fn () => request()->query('course_id'))
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('position')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('description')
                    ->columnSpanFull(),
                // Repeater::make('moduleItems')
                //     ->schema([
                //         TextInput::make('title')->required(),
                //         // Select::make('role')
                //         //     ->options([
                //         //         'member' => 'Member',
                //         //         'administrator' => 'Administrator',
                //         //         'owner' => 'Owner',
                //         //     ])
                //         //     ->required(),
                //     ])
                //     ->columns(2)
            ]);
    }
}
