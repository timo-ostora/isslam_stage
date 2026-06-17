<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use App\Models\Enrollment;
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
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnrolleesRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollees';


    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
                TextInput::make('progress_percentage')
                    ->required()
                    ->numeric()
                    ->default(0),
                DateTimePicker::make('completed_at'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Enrollment')
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('progress_percentage')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable(),
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
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make()
                        ->recordSelect(
                        fn (Select $select) => $select
                            ->placeholder('Sélectionnez un enregistrement')
                            // 1. Laissez impérativement le constructeur vide ou utilisez 'recordId' s'il est redéfini à partir de zéro
                            // 2. Utilisez la méthode ->relationship() pour lier la table et la colonne à afficher
                            ->relationship('users', 'name') 
                            ->searchable()
                    )
                    ->schema([
                        Select::make('users')
                            ->relationship('users', 'name')
                            ->required(),
                        TextInput::make('status')
                            ->required()
                            ->default('active'),
                        TextInput::make('progress_percentage')
                            ->required()
                            ->numeric()
                            ->default(0),
                        DateTimePicker::make('completed_at'),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
