<?php

namespace App\Filament\Resources\Courses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail_url')
                    ->label('Thumbnail')
                    ->disk('public')
                    ->visibility('public')
                    ->imageHeight(60)
                    ->square()
                    ->defaultImageUrl(
                        fn (): string => 'https://placehold.co/120x120?text=Course'
                    ),

                TextColumn::make('title')
                    ->label('Course')
                    ->description(
                        fn ($record): ?string => $record->slug
                    )
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->limit(45)
                    ->tooltip(
                        fn ($record): ?string => $record->title
                    )
                    ->grow(),

                TextColumn::make('creator.name')
                    ->label('Instructor')
                    ->icon('heroicon-o-user')
                    ->searchable()
                    ->sortable()
                    ->placeholder('No instructor'),

                TextColumn::make('category.title')
                    ->label('Category')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Uncategorized'),

                TextColumn::make('difficulty_level')
                    ->label('Difficulty')
                    ->badge()
                    ->formatStateUsing(
                        fn (?string $state): string => match ($state) {
                            'easy' => 'Easy',
                            'medium' => 'Medium',
                            'hard' => 'Hard',
                            default => ucfirst($state ?? 'Unknown'),
                        }
                    )
                    ->color(
                        fn (?string $state): string => match ($state) {
                            'easy' => 'success',
                            'medium' => 'warning',
                            'hard' => 'danger',
                            default => 'gray',
                        }
                    )
                    ->icon(
                        fn (?string $state): string => match ($state) {
                            'easy' => 'heroicon-o-face-smile',
                            'medium' => 'heroicon-o-adjustments-horizontal',
                            'hard' => 'heroicon-o-fire',
                            default => 'heroicon-o-question-mark-circle',
                        }
                    )
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (?string $state): string => match ($state) {
                            'draft' => 'Draft',
                            'published' => 'Published',
                            'archived' => 'Archived',
                            default => ucfirst($state ?? 'Unknown'),
                        }
                    )
                    ->color(
                        fn (?string $state): string => match ($state) {
                            'draft' => 'gray',
                            'published' => 'success',
                            'archived' => 'warning',
                            default => 'gray',
                        }
                    )
                    ->icon(
                        fn (?string $state): string => match ($state) {
                            'draft' => 'heroicon-o-pencil-square',
                            'published' => 'heroicon-o-check-circle',
                            'archived' => 'heroicon-o-archive-box',
                            default => 'heroicon-o-question-mark-circle',
                        }
                    )
                    ->sortable(),

                TextColumn::make('duration_seconds')
                    ->label('Duration')
                    ->icon('heroicon-o-clock')
                    ->formatStateUsing(
                        function ($state): string {
                            $seconds = (int) ($state ?? 0);

                            if ($seconds <= 0) {
                                return '0 min';
                            }

                            $hours = intdiv($seconds, 3600);
                            $minutes = intdiv($seconds % 3600, 60);

                            if ($hours > 0) {
                                return "{$hours}h {$minutes}m";
                            }

                            return "{$minutes} min";
                        }
                    )
                    ->sortable(),

                TextColumn::make('modules_count')
                    ->counts('modules')
                    ->label('Modules')
                    ->icon('heroicon-o-rectangle-stack')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('enrollments_count')
                    ->counts('enrollments')
                    ->label('Students')
                    ->icon('heroicon-o-users')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('certificates_count')
                    ->counts('certificates')
                    ->label('Certificates')
                    ->icon('heroicon-o-academic-cap')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('language')
                    ->label('Language')
                    ->badge()
                    ->formatStateUsing(
                        fn (?string $state): string => match ($state) {
                            'en' => 'English',
                            'fr' => 'French',
                            'ar' => 'Arabic',
                            default => strtoupper($state ?? 'N/A'),
                        }
                    )
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('Deleted')
                    ->dateTime('M d, Y · H:i')
                    ->placeholder('—')
                    ->color('danger')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ])
                    ->multiple(),

                SelectFilter::make('difficulty_level')
                    ->label('Difficulty')
                    ->options([
                        'easy' => 'Easy',
                        'medium' => 'Medium',
                        'hard' => 'Hard',
                    ])
                    ->multiple(),

                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'title')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('creator_id')
                    ->label('Instructor')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('language')
                    ->options([
                        'en' => 'English',
                        'fr' => 'French',
                        'ar' => 'Arabic',
                    ]),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->iconButton()
                    ->tooltip('View course'),

                EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit course'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([
                10,
                25,
                50,
                100,
            ])
            ->defaultPaginationPageOption(25);
    }
}