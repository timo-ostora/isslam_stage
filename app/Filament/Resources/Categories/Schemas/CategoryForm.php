<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Group; 
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
                Group::make()
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Set $set) =>
                                $set('slug', str($state)->slug())),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),

                    ])->columns(2),


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
                    ->placeholder('— None (Root Category) —'),

                Textarea::make('description')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}
