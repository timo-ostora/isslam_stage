<?php

namespace App\Filament\Resources\Modules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema; 
use Filament\Schemas\Components\Actions; 
use Filament\Actions\Action;             
use Filament\Schemas\Components\Utilities\Get;
use App\Filament\Resources\Lessons\LessonResource;
use App\Filament\Resources\Assessments\AssessmentResource;
use Filament\Schemas\Components\Section;

class ModuleForm
{
    /**
     * Configure the form using Filament v5 Schemas.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->hidden(fn () => request()->has('course_id'))
                    ->default(fn () => request()->query('course_id'))
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('position')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('description')
                    ->columnSpanFull(),

                Repeater::make('module_items')
                    ->relationship('moduleItems')
                    ->orderColumn('position') 
                    ->schema([
                        // 1. Select the Morph Type
                        Select::make('itemable_type')
                            ->label('Item Type')
                            ->options([
                                \App\Models\Lesson::class => 'Lesson',
                                \App\Models\Assessment::class => 'Assessment',
                            ])
                            ->live()
                            ->afterStateUpdated(fn (Select $component) => $component->getContainer()->getComponent('itemable_id')?->state(null)),

                        // 2. Select Item with Built-in "Create New" Suffix Action
                        Select::make('itemable_id')
                            ->label('Select Item')
                            ->id('itemable_id')
                            ->searchable() // Professional UX addition: makes large option lists easy to search
                            ->preload()    // Preloads choices for snappy performance
                            ->disabled(fn (Get $get) => ! $get('itemable_type')) 
                            ->options(function (Get $get) {
                                $type = $get('itemable_type');
                                
                                if (! $type) {
                                    return [];
                                }

                                return $type::pluck('title', 'id');
                            })
                            // Clean suffix button that updates contextually depending on selected Type
                            ->suffixAction(
                                Action::make('create_new_item')
                                    ->icon('heroicon-m-plus')
                                    ->color('success')
                                    ->tooltip(fn (Get $get) => match ($get('itemable_type')) {
                                        \App\Models\Lesson::class => 'Create New Lesson',
                                        \App\Models\Assessment::class => 'Create New Assessment',
                                        default => 'Select an Item Type first',
                                    })
                                    ->url(function (Get $get) {
                                        $type = $get('itemable_type');

                                        return match ($type) {
                                            \App\Models\Lesson::class => LessonResource::getUrl('create'),
                                            \App\Models\Assessment::class => AssessmentResource::getUrl('create'),
                                            default => null,
                                        };
                                    })
                                    ->openUrlInNewTab()
                                    // Professional UX rule: Hidden until the user chooses an "Item Type"
                                    ->visible(fn (Get $get) => filled($get('itemable_type')))
                            ),

                        // 3. Action button wrapper to edit existing selections
                        Actions::make([
                            Action::make('edit_related')
                                ->label('Edit Full Details')
                                ->icon('heroicon-m-pencil-square')
                                ->color('warning')
                                ->url(function ($record) {
                                    if (! $record || ! $record->itemable_id || ! $record->itemable_type) {
                                        return null; 
                                    }

                                    return match ($record->itemable_type) {
                                        \App\Models\Lesson::class => LessonResource::getUrl('edit', ['record' => $record->itemable_id]),
                                        \App\Models\Assessment::class => AssessmentResource::getUrl('edit', ['record' => $record->itemable_id]),
                                        default => null,
                                    };
                                })
                                ->openUrlInNewTab(),
                        ])->alignEnd(),

                    ])->columnSpanFull(),
            ]);
    }
}
