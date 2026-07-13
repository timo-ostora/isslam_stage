<?php

namespace App\Filament\Resources\Lessons\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;


class LessonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('title')
                    ->required(),
                    
                Textarea::make('description')
                    ->required(),
                
                // Select the content type for the lesson
                Select::make('type')
                    ->label('Content Type')
                    ->options([
                        'article' => 'Article',
                        'video' => 'Video',
                        'pdf' => 'PDF',
                        'link' => 'Link',
                    ])
                    ->required()
                    ->live(), // Crucial: Makes the form re-render instantly when changed

                // Show if content type is video, pdf, or link
                TextInput::make('content_url')
                    ->label('Content URL')
                    ->nullable()
                    ->visible(fn (get $get): bool => in_array($get('type'), ['video', 'pdf', 'link'])),

                // Show if content type is article
                MarkdownEditor::make('content_text')
                    ->label('Content Text')
                    ->nullable()
                    ->visible(fn (get $get): bool => $get('type') === 'article')
                    ,

                
                // Textarea::make('metadata')
                //     ->columnSpanFull(),
                // TextInput::make('duration_seconds')
                //     ->required()
                //     ->numeric()
                //     ->default(0),
            ])->columns(1);
    }
}
