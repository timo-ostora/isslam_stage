<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnrolleesRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';

    protected static ?string $title = 'Course Enrollments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Student')
                    ->relationship('user', 'name')
                    ->searchable(['name', 'email'])
                    ->preload()
                    ->required()
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Enrollment Status')
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                    ])
                    ->default('active')
                    ->native(false)
                    ->required(),

                TextInput::make('progress_percentage')
                    ->label('Progress')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(1)
                    ->suffix('%')
                    ->default(0)
                    ->required(),

                DateTimePicker::make('completed_at')
                    ->label('Completion Date')
                    ->seconds(false)
                    ->native(false)
                    ->helperText(
                        'Leave empty until the student completes the course.'
                    ),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(
                fn ($record): string =>
                    $record->user?->name ?? 'Enrollment'
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('Student')
                    ->icon('heroicon-o-user')
                    ->iconColor('primary')
                    ->weight('semibold')
                    ->description(
                        fn ($record): ?string => $record->user?->email
                    )
                    ->searchable([
                        'name',
                        'email',
                    ])
                    ->sortable()
                    ->grow(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (?string $state): string => match ($state) {
                            'active' => 'Active',
                            'completed' => 'Completed',
                            default => ucfirst($state ?? 'Unknown'),
                        }
                    )
                    ->color(
                        fn (?string $state): string => match ($state) {
                            'active' => 'primary',
                            'completed' => 'success',
                            default => 'gray',
                        }
                    )
                    ->icon(
                        fn (?string $state): string => match ($state) {
                            'active' => 'heroicon-o-play-circle',
                            'completed' => 'heroicon-o-check-circle',
                            default => 'heroicon-o-question-mark-circle',
                        }
                    )
                    ->sortable(),

                TextColumn::make('progress_percentage')
                    ->label('Progress')
                    ->formatStateUsing(
                        fn ($state): string =>
                            number_format((float) ($state ?? 0), 0) . '%'
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
                    ->icon(
                        fn ($state): string => match (true) {
                            (float) $state >= 100 => 'heroicon-o-check-circle',
                            (float) $state > 0 => 'heroicon-o-chart-bar',
                            default => 'heroicon-o-minus-circle',
                        }
                    )
                    ->sortable(),

                TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime('M d, Y · H:i')
                    ->placeholder('Not completed')
                    ->icon('heroicon-o-calendar-days')
                    ->color(
                        fn ($state): string =>
                            filled($state) ? 'success' : 'gray'
                    )
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Enrolled')
                    ->since()
                    ->dateTimeTooltip()
                    ->icon('heroicon-o-clock')
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

                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                    ])
                    ->multiple(),

                SelectFilter::make('user_id')
                    ->label('Student')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Enroll Student')
                    ->icon('heroicon-o-user-plus')
                    ->modalHeading('Enroll a student in this course')
                    ->modalSubmitActionLabel('Enroll Student'),

                AssociateAction::make()
                    ->label('Associate Enrollment')
                    ->icon('heroicon-o-link')
                    ->recordSelectSearchColumns([
                        'id',
                    ])
                    ->preloadRecordSelect(),
            ])
            ->recordActions([
                EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit enrollment'),

                DissociateAction::make()
                    ->iconButton()
                    ->tooltip('Remove from this course')
                    ->requiresConfirmation(),

                DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Delete enrollment'),

                RestoreAction::make()
                    ->iconButton()
                    ->tooltip('Restore enrollment'),

                ForceDeleteAction::make()
                    ->iconButton()
                    ->tooltip('Permanently delete enrollment'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(
                fn (Builder $query): Builder => $query
                    ->withoutGlobalScopes([
                        SoftDeletingScope::class,
                    ])
            )
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