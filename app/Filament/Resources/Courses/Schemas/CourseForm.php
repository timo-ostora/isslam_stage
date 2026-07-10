<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Enums\CourseStatus;
use App\Enums\DifficultyLevel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Basic Information')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                            $set('slug', str($state)->slug())),

                    TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true),

                    Select::make('creator_id')
                        ->label('Instructor / Creator')
                        ->relationship('creator', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('category_id')
                        ->relationship('category', 'title')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Textarea::make('description')
                        ->rows(4)
                        ->columnSpan(2),

                    // FileUpload::make('thumbnail_url')
                    //     ->label('Thumbnail')
                    //     ->image()
                    //     ->directory('course-thumbnails')
                    //     ->imageEditor()
                    //     ->columnSpan(2),

                    TextInput::make('thumbnail_url')
                        ->label('Thumbnail URL')
                        ->url()
                        ->prefix('url')
                        ->columnSpan(2),

                    TextInput::make('duration_seconds')
                        ->label('Total Duration (minutes)')
                        ->numeric()
                        ->dehydrateStateUsing(fn ($state) => $state * 60)
                        ->formatStateUsing(fn ($state) => $state ? intdiv($state, 60) : null)
                        ->suffix('min')
                        ->required(),
                    Select::make('language')
                        ->options([
                            'en' => 'English',
                            'fr' => 'French',
                            'ar' => 'Arabic',
                        ])
                        ->required()
                        ->default('en'),
                    Select::make('difficulty_level')
                        ->options([
                            'easy' => 'Easy',
                            'medium' => 'Medium',
                            'hard' => 'Hard',
                        ])
                        ->required()
                        ->default('easy'),
                    Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                            'archived' => 'Archived',
                        ])
                        ->required()
                        ->default('draft')
                ])->columnSpanFull(),

                        // Section::make('Classification')
                        //     ->columns(3)
                        //     ->schema([
                        //         Select::make('status')
                        //             ->options(CourseStatus::class)
                        //             ->default('draft')
                        //             ->native(false)
                        //             ->required(),

                        //         Select::make('difficulty_level')
                        //             ->options(DifficultyLevel::class)
                        //             ->default('easy')
                        //             ->native(false)
                        //             ->required(),

                        //         Select::make('language')
                        //             ->options([
                        //                 'en' => 'English',
                        //                 'fr' => 'French',
                        //                 'ar' => 'Arabic',
                        //             ])
                        //             ->native(false)
                        //             ->required(),

                        //         TextInput::make('duration_seconds')
                        //             ->label('Total Duration (minutes)')
                        //             ->numeric()
                        //             ->dehydrateStateUsing(fn ($state) => $state * 60)
                        //             ->formatStateUsing(fn ($state) => $state ? intdiv($state, 60) : null)
                        //             ->suffix('min')
                        //             ->columnSpan(3),
                        //     ]),
                    // ])


        
            // Grid::make([
            //     'default' => 1,
            //     'md' => 2,
            // ])
            //     ->schema([
            //         TextInput::make('title')
            //             ->required()
            //             ->maxLength(255)
            //             ->live(onBlur: true)
            //             ->afterStateUpdated(fn (string $state, callable $set) =>
            //                 $set('slug', str($state)->slug())
            //             ),

            //         TextInput::make('slug')
            //             ->required()
            //             ->unique(ignoreRecord: true)
            //             ->prefix('/')
            //             ->helperText('Auto-generated from title'),
            //     ])
            //     ->columnSpanFull(),

            // Textarea::make('description')
            //     ->rows(3)
            //     ->columnSpanFull(),

            // Select::make('category_id')
            //     ->relationship('category', 'title')
            //     ->searchable()
            //     ->preload()
            //     ->required(),

            // Select::make('creator_id')
            //             ->relationship('creator', 'name')
            //             ->searchable()
            //             ->preload()
            //             ->required()
            //             ->label('Instructor'),

            // Section::make('Thumbnail')
            //     ->icon('heroicon-o-photo')
            //     ->schema([
            //         FileUpload::make('thumbnail_url')
            //             ->label('Course Thumbnail')
            //             ->image() 
            //             ->disk('public') 
            //             ->directory('course-thumbnails')
            //             ->visibility('public')
            //             ->nullable(),
            //     ]),

            // Section::make('Settings')
            //     ->icon('heroicon-o-adjustments-horizontal')
            //     ->columns(2)
            //     ->schema([
            //         Select::make('difficulty_level')
            //             ->options(DifficultyLevel::class)
            //             ->default('easy')
            //             ->required()
            //             ->native(false),

            //         Select::make('status')
            //             ->options(CourseStatus::class)
            //             ->default('draft')
            //             ->required()
            //             ->native(false),

            //         TextInput::make('language')
            //             ->required()
            //             ->default('en'),

            //         TextInput::make('duration_seconds')
            //             ->numeric()
            //             ->default(0)
            //             ->suffix('sec')
            //             ->required(),
            //     ]),
        

        
        ]);
    }
}
