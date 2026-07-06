<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->toggleable(),

                TextColumn::make('parent.title')
                    ->label('Parent Category')
                    ->placeholder('— Root —')
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                IconColumn::make('is_root')
                    ->label('Root?')
                    ->getStateUsing(fn (\App\Models\Category $record) => $record->isRoot())
                    ->boolean()
                    ->toggleable(),

                TextColumn::make('description')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),

                BadgeColumn::make('courses_count')
                    ->counts('courses')
                    ->toggleable()
                    ->label('Courses'),
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
                SelectFilter::make('parent_id')
                    ->label('Parent Category')
                    ->relationship('parent', 'title')
                    ->searchable()
                    ->preload(),

                Filter::make('is_root')
                    ->label('Root categories only')
                    ->query(fn (Builder $query) => $query->whereNull('parent_id')),
            ])
            ->recordActions([

                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ])   
                ->icon('heroicon-m-ellipsis-vertical')
                ->color('primary')
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
