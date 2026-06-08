<?php

namespace App\Filament\Resources\Courses\Resources\Modules\Resources\Lessons\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LessonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description'),
                TextInput::make('order_index')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('duration_seconds')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('type')
                    ->options([
                        'video' => 'Video',
                        'article' => 'Article',
                        'quiz' => 'Quiz',
                    ])
                    ->required()
                    ->default('article'),
                FileUpload::make('content_url')
                    ->label('Content File')
                    ->directory('lessons')
                    ->visibility('private')
                    ->acceptedFileTypes(['video/*', 'application/pdf', 'text/markdown'])
                    ->helperText('Upload a video, PDF, or Markdown file for this lesson.'),
                MarkdownEditor::make('content_text')
                    ->columnSpanFull(),

                KeyValue::make('metadata')
                    ->columnSpanFull(),
            ]);
    }
}
