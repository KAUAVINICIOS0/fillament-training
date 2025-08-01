<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Category;
use App\Models\Post;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Post information')
                    ->description(function ($operation) {
                        if ($operation == 'create') {
                            return 'Create a new post';
                        }
                        return 'Edit post';
                    })
                    ->columns(2)
                    ->schema([

                        TextInput::make('title')
                            ->helperText('post title')
                            ->hint('post title')
                            ->label('title')
                            ->required()
                            ->live(onBlur:true)
                            ->afterStateUpdated(function ($state, $set) {
                                $set('slug', Str::slug($state));
                            })
                            ->placeholder('post title'),

                        TextInput::make('slug')
                            ->helperText('Post slug')
                            ->hint('Post slug')
                            ->label('slug')
                            ->required()
                            ->placeholder('post slug'),
                    ])->collapsible(),

                Section::make('content')
                    ->description('content')
                    ->schema([
                        RichEditor::make('content')
                            ->helperText('Post Content')
                            ->hint('Post content')
                            ->placeholder('Post content')
                            ->label('Content')
                            ->required(),
                    ])->columnSpanFull()->collapsible(),

                Section::make('thumbnail')
                    ->description('thumbnail')
                    ->schema([
                        FileUpload::make('thumbnail')
                        ->image()
                        ->directory('thumbs')
                            ->helperText('thumbnail')
                            ->hint('thumbnail')
                            ->label('thumbnail')
                            ->required()
                    ])->columnSpanFull()->collapsible(),

                Section::make('Categories and tags')
                    ->description('select categories and tags')
                    ->schema([
                        Select::make('category_id')
                            ->label('category')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->relationship('category', 'name'),

                        TagsInput::make('tags')
                            ->label('Tags'),
                        // Select::make('tags')
                        //     ->multiple()
                        //     ->label('tags')
                        //     ->searchable()
                        //     ->preload()
                        //     ->required()
                        //     ->relationship('tags', 'tag_name'),

                    ])->columns(2)->collapsible(),

                Section::make('is_published')
                        ->label('Foi publicado')
                        ->description('the post is published? ')
                        ->schema([
                            Select::make('is_published')
                                ->label('Está publicado?')
                                ->options([
                                    0 => 'No',
                                    1 => 'yes',
                                ])
                                ->default(0)
                                ->required(),
                        ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('titulo')
                    ->searchable()
                    ->sortable()
                    ->limit(20),

                IconColumn::make('is_published')
                    ->label('Foi publicado')
                    ->boolean(),

                TextColumn::make('user.name')
                    ->sortable()
                    ->searchable()
                    ->label('Author')
                    ->limit(20),

                TextColumn::make('category.name')
                    ->limit(20)
                    ->sortable()
                    ->searchable()
                    ->label('Categoria'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
