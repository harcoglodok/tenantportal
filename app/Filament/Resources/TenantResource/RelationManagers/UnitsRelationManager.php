<?php

namespace App\Filament\Resources\TenantResource\RelationManagers;

use Filament\Forms;
use App\Models\Unit;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Resources\UnitResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class UnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'units';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('no_unit')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('business_id'),
                Forms\Components\TextInput::make('phone')
                    ->tel(),
                Forms\Components\TextInput::make('email')
                    ->email(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('no_unit')
            ->columns([
                Tables\Columns\TextColumn::make('no')->rowIndex(),
                Tables\Columns\TextColumn::make('no_unit')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('name')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('business_id')
                    ->placeholder('-'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (Unit $record): string => UnitResource::getUrl('view', ['record' => $record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
