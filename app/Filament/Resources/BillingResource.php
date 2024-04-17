<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillingResource\Pages;
use App\Filament\Resources\BillingResource\RelationManagers;
use App\Models\Billing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BillingResource extends Resource
{
    protected static ?string $model = Billing::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('inv_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('month')
                    ->searchable(),
                Tables\Columns\TextColumn::make('year')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tenant_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('s4_mbase_amt')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('s4_mtax_amt')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sd_mbase_amt')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service_charge')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sinking_fund')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('electric_previous')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('electric_current')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('electric_read')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('electric_fixed')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('electric_administration')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('electric_tax')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('electric_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mcb')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('water_previous')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('water_current')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('water_read')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('water_fixed')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('water_mbase')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('water_administration')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('water_tax')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('water_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tube')
                    ->searchable(),
                Tables\Columns\TextColumn::make('panin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bca')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cimb')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mandiri')
                    ->searchable(),
                Tables\Columns\TextColumn::make('add_charge')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('previous_transaction')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListBillings::route('/'),
            'create' => Pages\CreateBilling::route('/create'),
            'view' => Pages\ViewBilling::route('/{record}'),
            'edit' => Pages\EditBilling::route('/{record}/edit'),
        ];
    }
}
