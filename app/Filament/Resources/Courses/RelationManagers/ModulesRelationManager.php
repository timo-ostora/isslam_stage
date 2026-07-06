<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use App\Filament\Resources\Modules\ModuleResource;
use App\Filament\Resources\Courses\CourseResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ModulesRelationManager extends RelationManager
{
    protected static string $relationship = 'Modules';

    protected static ?string $relatedResource = ModuleResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
                // Action::make('createModule')
                //     ->label('Ajouter un module')
                //     ->url(fn ($record) => CourseResource::getUrl('create', ['course_id' => $record->id]))
            ]);
    }
}
