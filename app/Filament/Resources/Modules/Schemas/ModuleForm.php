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
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;

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
                    ->label('Description')
                    ->columnSpanFull(),

                Repeater::make('module_items')
                    ->relationship('moduleItems')
                    ->orderColumn('position') 
                    ->columnSpanFull()
                    ->schema([
                        // 1. Select the Morph Type
                        Select::make('itemable_type')
                            ->label('Item Type')
                            ->required()
                            ->options([
                                \App\Models\Lesson::class => 'Lesson',
                                \App\Models\Assessment::class => 'Assessment',
                            ])
                            ->live()
                            ->afterStateUpdated(fn (Select $component) => $component->getContainer()->getComponent('itemable_id')?->state(null)),

                        // 2. Select Item with Built-in "Create New" Suffix Action
                        Select::make('itemable_id')
                            ->columnSpan(2)
                            ->label('Select Item')
                            ->required()
                            ->id('itemable_id')
                            ->searchable()
                            ->preload() 
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


                    ])
                    ->collapsed()
                    ->itemLabel(function (array $state): ?HtmlString {
                        if (! isset($state['itemable_type'], $state['itemable_id'])) {
                            return null;
                        }

                        $type = $state['itemable_type'];
                        $id = $state['itemable_id'];

                        if (! $type || ! $id || ! class_exists($type)) {
                            return null;
                        }

                        $model = $type::find($id);
                        if (! $model) {
                            return null;
                        }

                        // 1. Map your Morph models to specific Heroicons and Tailwind color sets
                        [$icon, $textColor, $bgColor] = match ($type) {
                            \App\Models\Lesson::class => [
                                'heroicon-m-book-open', 
                                'text-blue-600 dark:text-blue-400', 
                                'bg-blue-50 dark:bg-blue-950/30'
                            ],
                            \App\Models\Assessment::class => [
                                'heroicon-m-clipboard-document-check', 
                                'text-purple-600 dark:text-purple-400', 
                                'bg-purple-50 dark:bg-purple-950/30'
                            ],
                            default => [
                                'heroicon-m-document', 
                                'text-gray-500 dark:text-gray-400', 
                                'bg-gray-100 dark:bg-gray-800'
                            ],
                        };

                        $title = $model->title ?? $model->name ?? 'Untitled Item';

                        // 2. Updated clean HTML wrapper to prevent standard line-breaks and constraints icon scale
                        return new HtmlString(
                            Blade::render("
                                <span class='inline-flex items-center gap-x-2'>
                                    <span class='inline-flex p-1 rounded {$bgColor} {$textColor} shrink-0'>
                                        <x-{$icon} class='w-4 h-4 min-w-[16px] min-h-[16px]' style='width: 16px; height: 16px;' />
                                    </span>
                                    <span class='font-medium text-gray-700 dark:text-gray-200 truncate max-w-[300px]'>
                                        {{ \$title }}
                                    </span>
                                </span>
                            ", ['title' => $title])
                        );
                    })
                    ->extraItemActions([
                        Action::make('edit_related')
                            ->label('Edit Full Details')
                            ->icon('heroicon-m-pencil-square')
                            ->color('info')                                                                         
                            ->url(function (array $arguments, Repeater $component) {
                                // 1. Fetch the exact state data for this specific repeater row item
                                $itemData = $component->getRawItemState($arguments['item']);

                                $type = $itemData['itemable_type'] ?? null;
                                $id = $itemData['itemable_id'] ?? null;

                                if (! $type || ! $id) {
                                    return null; 
                                }

                                // 2. Generate URLs dynamically based on the current row state data
                                return match ($type) {
                                    \App\Models\Lesson::class => LessonResource::getUrl('edit', ['record' => $id]),
                                    \App\Models\Assessment::class => AssessmentResource::getUrl('edit', ['record' => $id]),
                                    default => null,
                                };
                            })
                            ->openUrlInNewTab(),
                    ])
                    // ->itemDescription(fn (array $state): ?string => $state['itemable_id'] ? 'ID: ' . $state['itemable_id'] . ' | Type: ' . ($state['itemable_type'] ?? 'Unknown') : null)
                    // ->itemIcon(fn (array $state): ?string => match ($state['itemable_type'] ?? null) {
                    //     Lesson::class => 'heroicon-o-book-open',
                    //     Assessment::class => 'heroicon-o-clipboard-list',
                    //     default => 'heroicon-o-question-mark-circle',
                    // })
                    // ->itemColor(fn (array $state): ?string => match ($state['itemable_type'] ?? null) {
                    //     \App\Models\Lesson::class => 'primary',
                    //     \App\Models\Assessment::class => 'success',
                    //     default => 'secondary',
                    // })
                    ->columns(3),
            ]);

    }
}
