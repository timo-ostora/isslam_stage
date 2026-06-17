<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use App\Filament\Resources\Modules\ModuleResource;
use Filament\Actions\CreateAction;
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
            ]);
    }
}
