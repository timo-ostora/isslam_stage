<?php

namespace App\Filament\Resources\Courses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\SelectColumn;
use App\Enums\CourseStatus;
use Filament\Tables\Columns\ImageColumn;


class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    ImageColumn::make('thumbnail_url')
                        ->label('Thumbnail')
                        ->square()
                        ->width(100),
                    TextColumn::make('title')
                        ->searchable(),
                    TextColumn::make('slug')
                        ->searchable(),
                    TextColumn::make('creator.name')
                        ->label('Instructor')
                        ->searchable(),

                    TextColumn::make('category.title')
                        ->searchable(),

                    TextColumn::make('modules_count')
                        ->counts('modules')
                        ->label('Modules'),

                    SelectColumn::make('status')
                        ->options(CourseStatus::class)
                        ->toggleable(),

                    TextColumn::make('duration_seconds')
                        ->label('Duration')
                        ->suffix(' mins')
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),

                    TextColumn::make('difficulty_level')
                        ->badge()
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: true),

                    TextColumn::make('language')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: true),

                    TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),

                    TextColumn::make('updated_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),

                    TextColumn::make('deleted_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
