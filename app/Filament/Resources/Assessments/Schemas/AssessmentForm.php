<?php

namespace App\Filament\Resources\Assessments\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Str;

class AssessmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Tabs::make('Assessment')

                    ->tabs([

                        Tab::make('Details')
                            ->icon('heroicon-o-document-text')
                            ->schema([

                                TextInput::make('title')
                                    ->label('Title')
                                    ->placeholder('PHP Fundamentals Quiz')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                RichEditor::make('description')
                                    ->label('Description')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'bulletList',
                                        'orderedList',
                                        'link',
                                    ])
                                    ->columnSpanFull(),

                                Radio::make('type')
                                            ->label('Assessment Type')
                                            ->required()
                                            ->default('quiz')
                                            ->inline()
                                            ->inlineLabel(false)
                                            ->options([
                                                'quiz' => 'Quiz',
                                                'assignment' => 'Assignment',
                                                'exam' => 'Exam',
                                            ]),
                            ]),

                        Tab::make('Configuration')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([

                                        TextInput::make('duration_seconds')
                                            ->label('Duration')
                                            ->numeric()
                                            ->suffix('sec')
                                            ->minValue(1)
                                            ->helperText('Leave empty for unlimited time.'),

                                        TextInput::make('passing_score')
                                            ->label('Passing Score')
                                            ->numeric()
                                            ->default(70)
                                            ->suffix('%')
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->required(),

                                        TextInput::make('max_attempts')
                                            ->label('Attempts')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->helperText('Maximum number of attempts.'),
                            ]),

                        Tab::make('Questions')
                            ->icon('heroicon-o-question-mark-circle')
                            ->schema([

                                Placeholder::make('total_points')
                                    ->label('Total Points')
                                    ->content(function (Get $get): int {
                                        return collect($get('questions') ?? [])
                                            ->sum(fn ($question) => (int) ($question['points'] ?? 0));
                                    }),

                                Repeater::make('questions')
                                    ->relationship()
                                    ->label('Questions')
                                    ->orderColumn('position')
                                    ->defaultItems(1)
                                    ->minItems(1)
                                    ->cloneable()
                                    ->collapsible()
                                    ->collapsed()
                                    ->reorderableWithButtons()
                                    ->addActionLabel('Add Question')
                                    ->itemLabel(fn(array $state): ?string => filled($state['question_text'] ?? null)
                                        ? Str::limit($state['question_text'], 60)
                                        : 'New Question')
                                    ->schema([

                                        TextInput::make('question_text')
                                            ->label('Question')
                                            ->placeholder('Enter your question...')
                                            ->required()
                                            ->columnSpanFull(),

                                        TextInput::make('points')
                                            ->label('Points')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->required(),

                                        Repeater::make('options')
                                            ->relationship()
                                            ->label('Answer Options')
                                            ->defaultItems(4)
                                            ->minItems(2)
                                            ->cloneable()
                                            ->collapsible()
                                            ->collapsed()
                                            ->reorderableWithButtons()
                                            ->addActionLabel('Add Option')
                                            ->itemLabel(fn (array $state): string => filled($state['option_text'] ?? null)
                                                ? Str::limit($state['option_text'], 50)
                                                : 'New Option')
                                            ->columns(6)
                                            ->schema([

                                                TextInput::make('option_text')
                                                    ->label('Option')
                                                    ->placeholder('Answer...')
                                                    ->required()
                                                    ->columnSpan(5),

                                                Toggle::make('is_correct')
                                                    ->label('Correct')
                                                    ->inline(false)
                                                    ->columnSpan(1),

                                            ])
                                            ->columnSpanFull()
                                            ->visible(fn(Get $get) => $get('../../type') !== 'assignment'),

                                    ])
                                    ->columnSpanFull(),

                            ]),

                    ])

                    ->columnSpanFull(),

            ])
            ->columns(1);
    }
}