<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillingResource\Pages;
use App\Filament\Resources\BillingResource\RelationManagers\TransactionsRelationManager;
use App\Models\Billing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                Tables\Columns\TextColumn::make('no')->rowIndex(),
                Tables\Columns\TextColumn::make('inv_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('month')
                    ->searchable(),
                Tables\Columns\TextColumn::make('year')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unit.no_unit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'unpaid' => 'danger',
                        'paid' => 'success',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Invoice')
                    ->icon('heroicon-m-shopping-bag')
                    ->schema([
                        TextEntry::make('inv_no')
                            ->label('Invoice Number'),
                        TextEntry::make('unit.no_unit')
                            ->label('Unit'),
                        TextEntry::make('unit.user.name')
                            ->label('Owner'),
                        TextEntry::make('month'),
                        TextEntry::make('year'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'unpaid' => 'danger',
                                'paid' => 'success',
                            }),
                        TextEntry::make('service_charge')->placeholder('-')->numeric(),
                        TextEntry::make('sinking_fund')->placeholder('-')->numeric(),
                        TextEntry::make('add_charge')->placeholder('-'),
                        TextEntry::make('s4_mbase_amt')->placeholder('-')->numeric(),
                        TextEntry::make('s4_mtax_amt')->placeholder('-')->numeric(),
                        TextEntry::make('sd_mbase_amt')->placeholder('-')->numeric(),
                        TextEntry::make('previous_transaction')->placeholder('-'),
                        TextEntry::make('total')->placeholder('-')->numeric(),
                        TextEntry::make(''),
                        Section::make('Electric')
                            ->icon('heroicon-m-bolt')
                            ->aside()
                            ->schema([
                                TextEntry::make('mcb')->placeholder('-')->numeric(),
                                TextEntry::make('electric_previous')->placeholder('-')->numeric(),
                                TextEntry::make('electric_current')->placeholder('-')->numeric(),
                                TextEntry::make('electric_read')->placeholder('-')->numeric(),
                                TextEntry::make('electric_fixed')->placeholder('-')->numeric(),
                                TextEntry::make('electric_administration')->placeholder('-')->numeric(),
                                TextEntry::make('electric_tax')->placeholder('-')->numeric(),
                                TextEntry::make('electric_total')->placeholder('-')->numeric(),
                            ])->columns(3),
                        Section::make('Water')
                            ->icon('heroicon-m-cloud')
                            ->aside()
                            ->schema([
                                TextEntry::make('tube')->placeholder('-'),
                                TextEntry::make('water_previous')->placeholder('-')->numeric(),
                                TextEntry::make('water_current')->placeholder('-')->numeric(),
                                TextEntry::make('water_read')->placeholder('-')->numeric(),
                                TextEntry::make('water_fixed')->placeholder('-')->numeric(),
                                TextEntry::make('water_mbase')->placeholder('-')->numeric(),
                                TextEntry::make('water_administration')->placeholder('-')->numeric(),
                                TextEntry::make('water_tax')->placeholder('-')->numeric(),
                                TextEntry::make('water_total')->placeholder('-')->numeric(),
                            ])->columns(3),
                        Section::make('Bank')
                            ->icon('heroicon-m-credit-card')
                            ->aside()
                            ->schema([
                                TextEntry::make('panin')->placeholder('-'),
                                TextEntry::make('bca')->placeholder('-'),
                                TextEntry::make('cimb')->placeholder('-'),
                                TextEntry::make('mandiri')->placeholder('-'),
                            ])->columns(3),
                    ])->columns(3),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TransactionsRelationManager::class,
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
