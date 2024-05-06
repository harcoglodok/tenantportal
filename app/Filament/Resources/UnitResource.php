<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Unit;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\UnitResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UnitResource\RelationManagers\BillingsRelationManager;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('')->schema([
                    Forms\Components\TextInput::make('no_unit')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('business_id')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('name')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->maxLength(255),
                    // Forms\Components\TextInput::make('number')
                    //     ->maxLength(255),
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name', function (Builder $query) {
                            $query->where('role', 'tenant');
                        })
                        ->required(),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')->rowIndex(),
                Tables\Columns\TextColumn::make('no_unit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Owner')
                    ->sortable(),
                Tables\Columns\TextColumn::make('business_id')
                    ->default('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->default('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->default('-')
                    ->badge()
                    ->separator(';')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->default('-')
                    ->badge()
                    ->separator(';')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('')
                    ->schema([
                        TextEntry::make('no_unit')
                            ->default('-')
                            ->label('No Unit'),
                        TextEntry::make('user.name')
                            ->default('-')
                            ->label('Owner'),
                        TextEntry::make('name')
                            ->default('-'),
                        TextEntry::make('business_id')
                            ->default('-')
                            ->label('Business ID'),
                        TextEntry::make('email')
                            ->badge()
                            ->separator(';')
                            ->default('-'),
                        TextEntry::make('phone')
                            ->badge()
                            ->separator(';')
                            ->default('-'),
                    ])->columns(3),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BillingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'view' => Pages\ViewUnit::route('/{record}'),
            'edit' => Pages\EditUnit::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
