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
use Filament\Tables\Columns\BadgeColumn;
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
                    ->visibility('private')
                    ->toggleable(),
                    
                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('category.title')
                    ->label('Category')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                BadgeColumn::make('difficulty_level')
                    ->label('defficulty')
                    ->colors([
                        'warning' => 'medium',
                        'success' => 'easy',
                        'danger' => 'hard',
                    ])
                    ->toggleable(),
                BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'success' => 'published',
                        'warning' => 'archived',
                    ])
                    ->toggleable(),
                textColumn::make('duration_seconds')
                    ->label('duration')
                    ->prefix('s')
                    ->toggleable(),
                TextColumn::make('modules_count')
                    ->counts('modules')
                    ->label('modules')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('enrollments_count')
                    ->counts('enrollments')
                    ->label('Enrollment')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('certificates_count')
                    ->counts('certificates')
                    ->label('certificates')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
