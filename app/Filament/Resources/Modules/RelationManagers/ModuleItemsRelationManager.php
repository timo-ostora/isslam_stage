<?php

namespace App\Filament\Resources\Modules\RelationManagers;

use App\Models\Assessment;
use App\Models\Lesson;
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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Group;
use Filament\Schemas\Components\Utilities\Get;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModuleItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'moduleItems';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Select::make('itemable_type')
                //     ->label('Content Type')
                //     ->options([
                //         Lesson::class     => 'Lesson',
                //         Assessment::class => 'Assessment',
                //     ])
                //     ->required()
                //     ->live()
                //     ->native(false),


                // Section::make()
                //     ->schema(fn (Get $get) => match ($get('itemable_type')) {


                //         // lesson type selected, show the lesson fields
                //         Lesson::class => [

                //             TextInput::make('title')
                //                 ->required(),
                            
                //             Textarea::make('description')
                //                 ->required(),
                            
                //             // Select the content type for the lesson
                //             Select::make('type')
                //                 ->label('Content Type')
                //                 ->options([
                //                     'article' => 'Article',
                //                     'video' => 'Video',
                //                     'pdf' => 'PDF',
                //                     'link' => 'Link',
                //                 ])
                //                 ->default('article')
                //                 ->required()
                //                 ->live(), // Crucial: Makes the form re-render instantly when changed

                //             // Show if content type is video, pdf, or link
                //             TextInput::make('content_url')
                //                 ->label('Content URL')
                //                 ->nullable()
                //                 ->visible(fn (get $get): bool => in_array($get('type'), ['video', 'pdf', 'link'])),

                //             // Show if content type is article
                //             Textarea::make('content_text')
                //                 ->label('Content Text')
                //                 ->nullable()
                //                 ->visible(fn (get $get): bool => $get('type') === 'article'),

                //             TextInput::make('duration_seconds')
                //                 ->label('Duration (seconds)')
                //                 ->numeric()
                //                 ->default(0),
                //             ],

                        
                //         // assessment type selected, show the assessment fields
                //         Assessment::class => [
                //             TextInput::make('title')
                //                 ->required(),

                //             TextInput::make('passing_score')
                //                 ->numeric()
                //                 ->required(),
                //         ],

                //         default => [],
                //     }),
                
                //     TextInput::make('position')
                //         ->label('Position')
                //         ->numeric()
                //         ->default(1)
                //         ->required(),
            
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Module Items')
            ->columns([

                TextColumn::make('itemable_type')
                    ->label('Content Type')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        Lesson::class     => 'Lesson',
                        Assessment::class => 'Assessment',
                        default           => $state,
                    }),

                TextColumn::make('itemable.title')
                    ->label('Title')
                    ->toggleable()
                    ->searchable(),
                
                TextColumn::make('itemable.description')
                    ->label('Description')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('position')
                    ->label('Position')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([

                CreateAction::make()
                    ->using(function (array $data) {

                        if ($data['itemable_type'] === Lesson::class) {

                            $item = Lesson::create([
                                'title'   => $data['title'],
                                'description' => $data['description'] ?? null,
                                'type'    => $data['type'] ?? 'article', // Default type, you can modify this as needed
                                'content_url' => $data['content_url'] ?? null,
                                'content_text' => $data['content_text'] ?? null,
                                'duration_seconds' => $data['duration_seconds'] ?? 0,
                            ]);

                        } else {

                            $item = Assessment::create([
                                'title' => $data['title'],
                                'passing_score' => $data['passing_score'],
                            ]);

                        }

                        return $this->getRelationship()->create([
                            'itemable_type' => $item::class,
                            'itemable_id'   => $item->id,
                            'position'      => $data['position'],
                        ]);
                    }),


                AssociateAction::make(),
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
