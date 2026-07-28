<?php

namespace App\Filament\Resources\Enrollments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class EnrollmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Student')
                    ->icon(Heroicon::User)
                    ->iconColor('primary')
                    ->weight(FontWeight::SemiBold)
                    ->description(
                        fn ($record): ?string => $record->user?->email
                    )
                    ->searchable([
                        'name',
                        'email',
                    ])
                    ->sortable(),

                TextColumn::make('course.title')
                    ->label('Course')
                    ->icon(Heroicon::AcademicCap)
                    ->iconColor('gray')
                    ->limit(40)
                    ->tooltip(
                        fn ($record): ?string => $record->course?->title
                    )
                    ->searchable()
                    ->sortable()
                    ->grow(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (?string $state): string => match ($state) {
                            'active' => 'Active',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                            'expired' => 'Expired',
                            default => ucfirst($state ?? 'Unknown'),
                        }
                    )
                    ->color(
                        fn (?string $state): string => match ($state) {
                            'active' => 'primary',
                            'completed' => 'success',
                            'cancelled' => 'danger',
                            'expired' => 'warning',
                            default => 'gray',
                        }
                    )
                    ->icon(
                        fn (?string $state): Heroicon => match ($state) {
                            'active' => Heroicon::PlayCircle,
                            'completed' => Heroicon::CheckCircle,
                            'cancelled' => Heroicon::XCircle,
                            'expired' => Heroicon::Clock,
                            default => Heroicon::QuestionMarkCircle,
                        }
                    )
                    ->sortable(),

                TextColumn::make('progress_percentage')
                    ->label('Progress')
                    ->formatStateUsing(
                        fn ($state): string => number_format(
                            (float) ($state ?? 0),
                            0
                        ) . '%'
                    )
                    ->badge()
                    ->color(
                        fn ($state): string => match (true) {
                            (float) $state >= 100 => 'success',
                            (float) $state >= 50 => 'primary',
                            (float) $state > 0 => 'warning',
                            default => 'gray',
                        }
                    )
                    ->icon(Heroicon::ChartBar)
                    ->sortable(),

                TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime('M d, Y · H:i')
                    ->placeholder('Not completed')
                    ->icon(Heroicon::CalendarDays)
                    ->color(
                        fn ($state): string => filled($state)
                            ? 'success'
                            : 'gray'
                    )
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Enrolled')
                    ->since()
                    ->dateTimeTooltip()
                    ->icon(Heroicon::Clock)
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
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
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'expired' => 'Expired',
                    ])
                    ->multiple(),

                SelectFilter::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('user_id')
                    ->label('Student')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->iconButton()
                    ->tooltip('View enrollment'),

                EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit enrollment'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
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