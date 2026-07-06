<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
// use Filament\Schemas\Components\Section; 
use Filament\Forms\Set; 
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder; 
use App\Models\Category; 

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Section::make('Category Details')
                //     ->columns(2)
                //     ->schema([
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Set $set) =>
                                $set('slug', str($state)->slug()))
                            ->columnSpan(1),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpan(1),

                        Select::make('parent_id')
                            ->label('Parent Category')
                            ->relationship(
                                name: 'parent',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn (Builder $query, ?Category $record) =>
                                    $record ? $query->whereKeyNot($record->id) : $query
                            )
                            ->searchable()
                            ->preload()
                            ->placeholder('— None (Root Category) —')
                            ->columnSpan(2),

                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpan(2),
                    // ]),
            ]);
    }
}
