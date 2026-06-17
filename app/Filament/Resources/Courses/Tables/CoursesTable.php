<?php

namespace App\Filament\Resources\Courses\Tables;

use App\Models\Enrollment;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                ImageColumn::make('thumbnail_url')
                    ->label('Thumbnail')
                    ->disk('public') 
                    ->toggleable(),
                TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('category.title')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('enrollments_count')
                    ->counts('enrollees')
                    ->label('Enrollment')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
