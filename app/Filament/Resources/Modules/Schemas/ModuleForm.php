<?php

namespace App\Filament\Resources\Modules\Schemas;


use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Actions\Action;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
// use Filament\Forms\Components\TagsInput;
use App\Filament\Resources\Questions\QuestionResource;

class ModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('order_index')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('description')
                    ->columnSpanFull(),


                Section::make('Lessons')
                    ->icon('heroicon-o-play-circle')
                    ->collapsible()
                    ->schema([
                        Repeater::make('lessons')
                            ->relationship()
                            ->label('')
                            ->addActionLabel('Add Lesson')
                            ->orderColumn('order')
                            ->collapsible()
                            ->cloneable()
                            ->itemLabel(fn (array $state): ?string =>
                                $state['title'] ?? 'Untitled Lesson'
                            )
                            ->schema([
                                Grid::make(3)->schema([
                                    TextInput::make('title')
                                        ->required()
                                        ->live(onBlur: true)
                                        ->columnSpan(2),

                                    TextInput::make('duration_seconds')
                                        ->numeric()
                                        ->default(0)
                                        ->suffix('sec')
                                        ->label('Duration'),
                                ]),

                                RichEditor::make('content')
                                    ->toolbarButtons([
                                        'bold', 'italic',
                                        'bulletList', 'orderedList',
                                        'link', 'codeBlock',
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull(),
                    ]),

                
                Section::make('Assignments')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->collapsible()
                    ->schema([
                        Repeater::make('assessments')
                            ->relationship()
                            ->label('')
                            ->addActionLabel('Add Assignment')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string =>
                                $state['title'] ?? 'Untitled Assignment'
                            )
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->columnSpanFull(),
                                Textarea::make('description')
                                    ->columnSpanFull(),
                                Select::make('type')
                                    ->options(\App\Enums\AssessmentType::class)
                                    ->default(\App\Enums\AssessmentType::Quiz)
                                    ->native(false)
                                    ->live(),
                                TextInput::make('duration_seconds')
                                    ->numeric()
                                    ->default(0)
                                    ->suffix('sec')
                                    ->label('Duration'),
                                TextInput::make('passing_score')
                                    ->numeric()
                                    ->default(0)
                                    ->suffix('points')
                                    ->label('Passing Score'),
                                TextInput::make('max_attempts')
                                    ->numeric()
                                    ->default(0)
                                    ->suffix('attempts')
                                    ->label('Max Attempts'),
                                

                                // // ── Questions inside Assignment ──
                                // Repeater::make('questions')
                                //     ->relationship()
                                //     ->label('Questions')
                                //     ->addActionLabel('Add Question')
                                //     ->collapsible()
                                //     ->itemLabel(fn (array $state): ?string =>
                                //         $state['question_text'] ?? 'New Question'
                                //     )
                                //     ->schema([
                                //         Textarea::make('question_text')
                                //             ->required()
                                //             ->rows(2)
                                //             ->label('Question')
                                //             ->live(onBlur: true)
                                //             ->columnSpanFull(),

                                //         Select::make('type')
                                //             ->options([
                                //                 'text'            => 'Free text',
                                //                 'multiple_choice' => 'Multiple choice',
                                //                 'true_false'      => 'True / False',
                                //             ])
                                //             ->default('text')
                                //             ->native(false)
                                //             ->live(),

                                //         TagsInput::make('options')
                                //             ->label('Answer options')
                                //             ->visible(fn ($get) =>
                                //                 $get('type') === 'multiple_choice'
                                //             )
                                //             ->placeholder('Add option, press Enter'),

                                            
                                //     ])
                                //     ->columnSpanFull(),
                            ])
                            ->extraItemActions([
                                

                                Action::make('manage_questions')
                                    ->label('Manage Questions')
                                    ->icon('heroicon-o-question-mark-circle')
                                    ->color('blue')
                                    ->action(function (array $arguments, Repeater $component): void {
                                        $itemState = $component->getRawItemState($arguments['item']);
                                        $assessmentId = $itemState['id'] ?? null;

                                        if (! $assessmentId) {
                                            \Filament\Notifications\Notification::make()
                                                ->warning()
                                                ->title('Save the module first before managing questions.')
                                                ->send();
                                            return;
                                        }

                                        redirect(QuestionResource::getUrl('index'));
                                    }),


                            ])
                            ->columnSpanFull(),
                ]),

            ]);
    }
}
