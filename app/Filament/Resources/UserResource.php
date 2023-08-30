<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationLabel = 'Usuarios';

    protected static ?string $navigationGroup = 'Control';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('id', '>', 0);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre Usuario')
                            ->placeholder('teclear nombre propio')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->placeholder('teclear dirección email')
                            ->required()
                            ->email()
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Ingresar Contraseña')
                            ->placeholder('aqui la password')
                            ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                            ->password()
                            ->minLength(8)
                            ->same('passwordConfirmation')
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                        TextInput::make('passwordConfirmation')
                            ->label('Repetir Contraseña')
                            ->placeholder('igual que la primerra')
                            ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                            ->password()
                            ->minLength(8)
                            ->dehydrated(false),
                        Select::make('querol')
                            ->label('Tipo de Usuario')
                            ->options([
                                '1' => 'Sin Equipo',
                                '2' => 'Con Equipo',
                            ])
                            ->required(),
                        Select::make('equipo')
                            ->label('Asignar Equipo')
                            ->options([
                                '1' => 'Sin Equipo',
                                '2' => 'Equipo de Patty',
                                '3' => 'Equipo de Prueba',
                            ])
                            ->required(),
                        Select::make('roles')
                            ->relationship('roles', 'name'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('querol')->sortable(),
                TextColumn::make('equipo')->sortable(),
                TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime('d-m-Y h:i A'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
}
