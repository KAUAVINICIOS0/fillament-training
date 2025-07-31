<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use League\CommonMark\Extension\DescriptionList\Node\Description;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    // protected static ?string $slug = 'users/index';

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'Usuários';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Tabs::make('tabs')
                    ->tabs([
                        Tab::make('User Data')
                            ->schema([
                                TextInput::make('name')
                                    ->helperText('nome do usuário')
                                    ->hint(function ($operation) {
                                        if ($operation == 'create') {
                                            return 'Nome de usuário';
                                        }
                                        return 'atualize o nome de usuário';
                                    })
                                    ->rules(['required'])
                                    ->label('nome')
                                    ->placeholder('nome do usuário')
                                    ->required()
                                    ->required(),

                                TextInput::make('email')
                                    ->rules(['required'])
                                    ->helperText('email@exemple.com')
                                    ->hint('email@exemple.com')
                                    ->placeholder('email@exemple.com')
                                    ->required()
                                    ->email()
                                    ->unique(ignoreRecord: True),

                                TextInput::make('password')
                                    ->rules(['required'])
                                    ->helperText('senha do usuário')
                                    ->hint('senha do usuário')
                                    ->placeholder('password')
                                    ->password()
                                    ->required()
                                    ->visibleOn('create'),

                                TextInput::make('phone')
                                    ->helperText('telefone do usuário')
                                    ->hint('telefone do usuário')
                                    ->label('telefone')
                                    ->mask('(99) 99999-9999')
                                    ->placeholder('(__) _____-____'),
                            ]),
                        Tab::make('avatar')
                            ->schema([
                                FileUpload::make('avatar')
                                    ->image()
                                    ->directory('avatars')
                                    ->imageEditor(),
                            ]),

                        Tab::make('Termos de administrador')
                            ->schema([
                                Toggle::make('is_admin')
                                    ->helperText('Usuário é administrador?')
                                    ->hint('Escolha status do usuário')
                                    ->label('Administrador'),
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),

                ImageColumn::make('avatar')
                    ->circular(),

                TextColumn::make('email')
                    ->label('Email'),

                IconColumn::make('is_admin')
                    ->label('admin?')
                    ->boolean(),

                TextColumn::make('phone')
                    ->label('Telefone')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('comments_count')
                    ->label('Comentários')
                    ->counts('comments'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }
}
